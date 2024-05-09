<?php

namespace App\Http\Controllers\Customer;

use App\Events\MessageReceived;
use App\Helpers\Worker;
use App\Http\Controllers\Controller;
use App\Models\ChatBox;
use App\Models\ChatBoxMessage;
use App\Models\Todos;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ChatBoxController extends Controller {

    /**
     * get all chat box
     * @param \App\Models\Todos $task
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function open( Todos $task, ChatBox $box ) {
        $this->authorize( 'view_chat' );

        if ( auth()->user()->id != $task->user_id ) {
            abort( 401 );
        }

        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'bodyClass' => 'chat-application',
        ];

        $chat_box = ChatBox::where( 'user_id', Auth::user()->id )->take( 1000 )->orderBy( 'updated_at', 'desc' )->cursor();

        return view( 'customer.chatbox.open', [
            'pageConfigs' => $pageConfigs,
            'chat_box' => $chat_box,
            'task' => $task,
            'box' => $box,
        ] );
    }
    /**
     * get all chat box
     * @param \App\Models\Todos $task
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function receiver( Todos $task ) {

        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'bodyClass' => 'chat-application',
        ];

        if ( ChatBox::where( 'to', auth()->user()->id )->count() < 1 ) {
            abort( 404 );
        }

        $chat_box = ChatBox::where( 'to', auth()->user()->id )->take( 1000 )
            ->orderBy( 'updated_at', 'desc' )->first();

        return view( 'customer.chatbox.receiver.index', [
            'pageConfigs' => $pageConfigs,
            'chat_box' => $chat_box,
            'task' => $task,
        ] );
    }

    /**
     * start new conversation
     *
     * @param \App\Models\Todos $task
     * @return Application|Factory|View|RedirectResponse
     * @throws AuthorizationException
     *
     */
    public function new ( Todos $task ) {
        $this->authorize( 'create_chat' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['link' => url( 'chat-box' ), 'name' => __( 'locale.menu.Chat Box' )],
            ['name' => 'add to chat'],
        ];

        $users = [];

        $exist_users = ChatBox::where( 'user_id', Auth::id() )
            ->where( 'todo_id', $task->id )->pluck( 'to' );

        if ( in_array( 'all', $task->assigned() ) ) {
            $users = User::where( 'active_portal', 'customer' )
                ->where( 'id', '!=', auth()->user()->id )
                ->whereNotIn( 'id', $exist_users )
                ->get();
        } else {
            foreach ( $task->assigned() as $user_id ) {
                User::where( 'id', $user_id )->whereNotIn( 'id', $exist_users );
                if ( User::where( 'id', $user_id )->whereNotIn( 'id', $exist_users )->count() > 0 ) {
                    $users[] = User::where( 'id', $user_id )->whereNotIn( 'id', $exist_users )->first();
                }
            }
        }

        $chatbox = ChatBox::where( 'user_id', auth()->user()->id )->where( 'todo_id', $task->id )->get();

        return view( 'customer.chatbox.new', compact( 'breadcrumbs', 'users', 'task' ) );
    }

    /**
     * start new conversion
     * @param  Request  $request
     */
    public function store( Request $request ) {

        if ( $this->checks() ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->authorize( 'create_chat' );

        $request->validate( [
            'todo_id' => 'required|exists:todos,id',
            'user_id' => 'required|exists:users,id',
        ] );

        $task = Todos::find( $request->todo_id );
        $user = User::find( $request->user_id );

        $chatbox = ChatBox::where( 'user_id', Auth::user()->id )
            ->where( 'todo_id', $request->todo_id )
            ->where( 'to', $request->user_id )->count();

        if ( $chatbox > 0 ) {
            return redirect()->route( 'customer.chat.new', $task->uid )->with( [
                'status' => 'error',
                'message' => __( 'Already exists chat with this user!' ),
            ] );
        }

        $chatbox = new ChatBox();

        $createBox = $chatbox->create( [
            'user_id' => Auth::user()->id,
            'from' => auth()->user()->id,
            'to' => $request->user_id,
            'todo_id' => $task->id,
            'notification' => 1,
        ] );

        if ( $createBox->save() ) {

            ChatBoxMessage::create( [
                'box_id' => $createBox->id,
                'message' => 'Task: ' . $task->name,
                'send_by' => 'from',
            ] );

            $createBox->touch();

            return redirect()->route( 'customer.chat.opne_with_user', [$task->uid, $createBox->uid] )->with( [
                'status' => 'success',
                'message' => __( 'chat box has been created successfully' ),
            ] );
        }

        return redirect()->route( 'customer.chat.new', $task->uid )->with( [
            'status' => 'error',
            'message' => __( 'unale to create chat box' ),
        ] );
    }

    /**
     * get chat messages
     *
     * @param  ChatBox  $box
     *
     * @return JsonResponse
     */
    public function messages( ChatBox $box ): JsonResponse {
        $box->update( [
            'notification' => 0,
        ] );

        $data = ChatBoxMessage::where( 'box_id', $box->id )->select( 'message', 'send_by', 'media_url' )->cursor()->toJson();

        return response()->json( [
            'status' => 'success',
            'data' => $data,
        ] );
    }

    /**
     * get chat messages
     *
     * @param  ChatBox  $box
     *
     * @return JsonResponse
     */
    public function messagesWithNotification( ChatBox $box ): JsonResponse {
        $data = ChatBoxMessage::where( 'box_id', $box->id )->select( 'message', 'send_by', 'media_url' )->cursor()->toJson();

        return response()->json( [
            'status' => 'success',
            'data' => $data,
        ] );
    }

    /**
     * reply message
     *
     * @param  ChatBox  $box
     * @param  Request  $request
     */
    public function reply( ChatBox $box, Request $request ) {

        if ( $this->checks() ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->authorize( 'create_chat' );

        if ( empty( $request->message ) ) {
            return response()->json( [
                'status' => 'error',
                'message' => __( 'locale.campaigns.insert_your_message' ),
            ] );
        }

        try {

            $message = Worker::linkify( $request->message );

            ChatBoxMessage::create( [
                'box_id' => $box->id,
                'message' => $message,
                'send_by' => 'from',
            ] );

            $box->touch();

            event( new MessageReceived( auth()->user(), $message, [
                'recipient_id' => $box->to,
            ] ) );

            return 'sent';
            ///s
        } catch ( Throwable $th ) {
            return response()->json( [
                'status' => 'error',
                'message' => $th->getMessage(),
            ] );
        }
    }
    /**
     * reply message
     *
     * @param  ChatBox  $box
     * @param  Request  $request
     */
    public function replyTo( ChatBox $box, Request $request ) {

        if ( $this->checks() ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->authorize( 'create_chat' );

        if ( empty( $request->message ) ) {
            return response()->json( [
                'status' => 'error',
                'message' => __( 'locale.campaigns.insert_your_message' ),
            ] );
        }

        try {

            $message = Worker::linkify( $request->message );

            ChatBoxMessage::create( [
                'box_id' => $box->id,
                'message' => $message,
                'send_by' => 'to',
            ] );

            event( new MessageReceived( auth()->user(), $message, [
                'recipient_id' => $box->user_id,
            ] ) );

            $box->touch();

            return 'sent';
        } catch ( Throwable $th ) {
            return response()->json( [
                'status' => 'error',
                'message' => $th->getMessage(),
            ] );
        }
    }

    /**
     * delete chatbox messages
     *
     * @param  ChatBox  $box
     *
     * @return JsonResponse
     */
    public function delete( ChatBox $box ): JsonResponse {

        if ( $this->checks() ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }
        $messages = ChatBoxMessage::where( 'box_id', $box->id )->delete();
        if ( $messages ) {
            $box->delete();

            return response()->json( [
                'status' => 'success',
                'message' => __( 'message deleted' ),
            ] );
        }

        return response()->json( [
            'status' => 'error',
            'message' => __( 'locale.exceptions.something_went_wrong' ),
        ] );
    }

    /**
     * add to blacklist
     *
     * @param  ChatBox  $box
     *
     * @return JsonResponse
     */
    public function block(): JsonResponse {
        return response()->json( [
            'status' => 'error',
            'message' => __( 'under development' ),
        ] );
    }
}
