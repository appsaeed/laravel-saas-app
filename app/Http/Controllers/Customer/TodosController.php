<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Todos\StoreTodoRequest;
use App\Mail\TodoMail;
use App\Models\Notifications;
use App\Models\Todos;
use App\Models\TodosReceived;
use App\Models\User;
use App\Repositories\TodosRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TodosController extends Controller {

    /**
     * @var TodosRepository
     */
    protected $todos;

    /**
     * TodosController constructor.
     * @param  TodoRepository  $account
     */
    public function __construct( TodosRepository $todos ) {
        $this->todos = $todos;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $this->authorize( 'view_todos' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'All Tasks' )],
        ];

        return view( 'customer.tasks.index', compact( 'breadcrumbs' ) );
    }

    /**
     * @param  Request  $request
     *
     * @return void
     * @throws AuthorizationException
     */
    public function search( Request $request ) {

        $this->authorize( 'view_todos' );

        $data = [];

        $columns = [
            0 => 'responsive_id',
            1 => 'uid',
            2 => 'uid',
            3 => 'name',
            4 => 'created_by',
            5 => 'completed_by',
            6 => 'actions',
        ];

        $totalData = Todos::where( 'user_id', auth()->user()->id )->count();

        $totalFiltered = $totalData;

        $limit = $request->input( 'length' );
        $start = $request->input( 'start' );
        $order = $columns[$request->input( 'order.0.column' )];
        $dir = $request->input( 'order.0.dir' );

        if ( empty( $request->input( 'search.value' ) ) ) {

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        } else {

            $search = $request->input( 'search.value' );

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->whereLike( ['name', 'completed_by.first_name', 'completed_by.last_name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $totalFiltered = Todos::where( 'user_id', auth()->user()->id )
                ->whereLike( ['todo.name', 'completed_by.first_name'], $search )->count();
        }

        if ( !empty( $todos_data ) ) {
            foreach ( $todos_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo, [
                    'can_update' => true,
                    'can_chat' => true,
                ] );
            }
        }

        /**
         * Task received data
         */
        $totalReceived = TodosReceived::where( 'user_id', auth()->user()->id )->count();

        $limit = $request->input( 'length' );
        $start = $request->input( 'start' );
        $order = $columns[$request->input( 'order.0.column' )];
        $dir = $request->input( 'order.0.dir' );

        if ( empty( $request->input( 'search.value' ) ) ) {

            $received_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

        } else {

            $search = $request->input( 'search.value' );

            $received_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereLike( [
                    'todo.name', 'completed_by.first_name', 'completed_by.last_name',
                ], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $totalReceived = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereLike( [
                    'todo.name', 'completed_by.first_name', 'completed_by.last_name',
                ], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        }

        $totalFiltered = $totalReceived;

        if ( !empty( $received_data ) ) {
            foreach ( $received_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo->todo, [
                    'can_update' => false,
                    'can_chat' => true,
                ] );
            }
        }
        /**
         * Task received data completed
         */

        $json_data = [
            "draw" => intval( $request->input( 'draw' ) ),
            "recordsTotal" => $totalData,
            "recordsFiltered" => intval( $totalFiltered ),
            "data" => $data,
        ];

        echo json_encode( $json_data );
        exit();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mytasks() {

        $this->authorize( 'view_todos' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'locale.menu.Todos' )],
        ];

        return view( 'customer.tasks.__created', compact( 'breadcrumbs' ) );
    }

    /**
     * @param  Request  $request
     *
     * @return void
     * @throws AuthorizationException
     */
    public function mytasksSearch( Request $request ) {

        $this->authorize( 'view_todos' );

        $columns = [
            0 => 'responsive_id',
            1 => 'uid',
            2 => 'uid',
            3 => 'name',
            4 => 'created_by',
            5 => 'status',
            6 => 'actions',
        ];

        $totalData = Todos::where( 'user_id', auth()->user()->id )
            ->where( 'status', '!=', 'complete' )
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input( 'length' );
        $start = $request->input( 'start' );
        $order = $columns[$request->input( 'order.0.column' )];
        $dir = $request->input( 'order.0.dir' );

        if ( empty( $request->input( 'search.value' ) ) ) {

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', '!=', 'complete' )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        } else {

            $search = $request->input( 'search.value' );

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', '!=', 'complete' )
                ->whereLike( ['name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $totalFiltered = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', '!=', 'complete' )
                ->whereLike( ['name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->count();
        }

        $data = [];

        if ( !empty( $todos_data ) ) {
            foreach ( $todos_data as $todo ) {
                $data[] = $this->todos->nestedData( $todo, [
                    'can_update' => true,
                    'can_chat' => true,
                ] );
            }
        }

        $json_data = [
            "draw" => intval( $request->input( 'draw' ) ),
            "recordsTotal" => $totalData,
            "recordsFiltered" => intval( $totalFiltered ),
            "data" => $data,
        ];

        echo json_encode( $json_data );
        exit();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $this->authorize( 'create_todos' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'messages.Add new todo' )],
        ];

        $customers = User::whereNot( 'active_portal', 'admin' )
            ->where( 'id', '!=', auth()->user()->id )->get();

        return view( 'customer.tasks.create', compact( 'breadcrumbs', 'customers' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( StoreTodoRequest $request ) {

        if ( $this->todos->store( $request ) ) {
            return redirect()->route( 'customer.tasks.index' )->with( [
                'status' => 'success',
                'message' => __( 'Todo was successfully created' ),
            ] );
        }

        return redirect()->route( 'customer.tasks.create' )->with( [
            'status' => 'error',
            'message' => Message::wentWrong(),
        ] );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Todos $task ) {
        $this->authorize( 'view_todos' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'View Task' )],
        ];

        if ( $task->isCreator() ) {
            $reviewers = $task->getReviewers();
            return view( 'customer.tasks.show', compact( 'breadcrumbs', 'task', 'reviewers' ) );
        }

        return view( 'customer.tasks.show', compact( 'breadcrumbs', 'task' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Todos $task ) {
        $this->authorize( 'update_todos' );

        if ( auth()->user()->id != $task->user_id ) {
            abort( 401 );
        }

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'locale.buttons.update' )],
        ];

        $customers = User::where( 'active_portal', 'customer' )
            ->where( 'id', '!=', auth()->user()->id )->get();

        return view( 'customer.tasks.edit', compact( 'breadcrumbs', 'customers', 'task' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Todos $task ) {
        $this->authorize( 'update_todos' );

        if ( auth()->user()->id != $task->user_id ) {
            abort( 401 );
        }

        if ( $this->todos->update( $request, $task ) ) {

            return redirect()->route( 'customer.tasks.edit', $task->uid )->with( [
                'status' => 'success',
                'message' => 'Successfully updated',
            ] );
        }

        return redirect()->route( 'customer.tasks.edit', $task->uid )->with( [
            'status' => 'error',
            'message' => Message::wentWrong(),
        ] );
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function will_do( Todos $todo ) {
        try {

            if ( TodosReceived::where( 'user_id', auth()->user()->id )->where( 'accepted', true )->count() > 2 ) {
                return response()->json( [
                    'status' => 'error',
                    'message' => __( 'You accepted max task!' ),
                ] );
            }

            if ( $todo->hasEmployee( auth()->user()->id ) ) {
                return response()->json( [
                    'status' => 'error',
                    'message' => __( 'You are already accepted the task!' ),
                ] );
            }

            TodosReceived::where( 'todo_id', $todo->id )->update( ['accepted' => true] );

            $notifocation = new Notifications();

            $show_link = route( 'customer.tasks.show', $todo->uid );
            $view_link = "<a href='$show_link'> click here</a>";

            if ( !$todo->addEmployee( auth()->user()->id ) ) {
                return response()->json( [
                    'status' => 'error',
                    'message' => __( 'Unable to add in work please try again or contact to creator!' ),
                ] );
            };

            $subject = User::fullname() . ' is now doing your task #' . $todo->uid;
            $message = '<b>' . User::fullname() . '</b> is now doing your task: ';
            $message .= '<b>' . $todo->name . '</b>  at  ' . Carbon::now()->format( 'Y m d h:m' );
            $message .= "<br> open the task $view_link";

            $notifocation->create( [
                'user_id' => $todo->user_id,
                'type' => 'task',
                'name' => $subject,
                'message' => $message,
                'created_by' => auth()->user()->id,
            ] )->save();

            if ( config( 'task.task_send_email' ) ) {
                Mail::to( User::find( $todo->user_id ) )->send( new TodoMail( [
                    'subject' => $subject,
                    'message' => $message,
                    'taskurl' => $show_link,
                ] ) );
            }

            return response()->json( [
                'status' => 'success',
                'message' => __( 'You are ready to work!' ),
            ] );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'status' => 'error',
                'message' => $th->getMessage(),
            ] );
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function send_review( Todos $todo ) {
        try {

            if ( $todo->hasReview() ) {
                return response()->json( [
                    'status' => 'error',
                    'message' => __( 'You are already sent to review!' ),
                ] );
            }

            if ( !$todo->addReview() ) {
                return response()->json( [
                    'status' => 'error',
                    'message' => __( 'unable to make for review!' ),
                ] );
            }

            $notifocation = new Notifications();

            $show_link = route( 'customer.tasks.show', $todo->uid );
            $view_link = "<a href='$show_link'> click here</a>";

            $subject = User::fullname() . ' sent to review the task #' . $todo->uid;
            $message = "Your task to <b>" . $todo->name . "</b> has now been completed by <b>" . User::fullname() . "</b> and is ready for review. for more information: $view_link";

            $notifocation->create( [
                'user_id' => $todo->user_id,
                'type' => 'task',
                'name' => $subject,
                'message' => $message,
                'created_by' => auth()->user()->id,
            ] )->save();

            if ( config( 'task.task_send_email' ) ) {
                Mail::to( User::find( $todo->user_id ) )->send( new TodoMail( [
                    'subject' => $subject,
                    'message' => $message,
                    'taskurl' => $show_link,
                ] ) );
            }

            return response()->json( [
                'status' => 'success',
                'message' => __( 'The task sent for review!' ),
            ] );
        } catch ( \Throwable $th ) {
            return response()->json( [
                'status' => 'error',
                'message' => Message::wentWrong(),
            ] );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Todos $todo ) {
        if ( !$todo->delete() ) {
            return response()->json( [
                'status' => 'error',
                'message' => Message::wentWrong(),
            ] );
        }
        return response()->json( [
            'status' => 'success',
            'message' => __( "successfully removed" ),
        ] );
    }

    /**
     * Bulk Action with Enable, Disable and Delete
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */

    public function batchAction( Request $request ): JsonResponse {

        $action = $request->get( 'action' );
        $ids = $request->get( 'ids' );

        switch ( $action ) {

        case 'destroy':

            $this->authorize( 'delete_todos' );

            // DB::transaction(function () use ($ids) {

            // });
            if ( Todos::query()->whereIn( 'uid', $ids )->delete() ) {
                return response()->json( [
                    'status' => 'success',
                    'message' => __( "successfully removed" ),
                ] );
            }

            return response()->json( [
                'status' => 'error',
                'message' => Message::wentWrong(),
            ] );
        }

        return response()->json( [
            'status' => 'error',
            'message' => __( 'locale.exceptions.invalid_action' ),
        ] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function received() {

        $this->authorize( 'view_todos' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'locale.menu.Todos' )],
        ];

        return view( 'customer.tasks.__received', compact( 'breadcrumbs' ) );
    }

    /**
     * @param  Request  $request
     *
     * @return void
     * @throws AuthorizationException
     */
    public function receivedSearch( Request $request ) {

        $this->authorize( 'view_todos' );

        $columns = [
            0 => 'responsive_id',
            1 => 'uid',
            2 => 'uid',
            3 => 'name',
            4 => 'created_by',
            5 => 'status',
            6 => 'actions',
        ];

        $totalData = TodosReceived::where( 'user_id', auth()->user()->id )
            ->where( 'status', '!=', 'complete' )
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input( 'length' );
        $start = $request->input( 'start' );
        $order = $columns[$request->input( 'order.0.column' )];
        $dir = $request->input( 'order.0.dir' );

        if ( empty( $request->input( 'search.value' ) ) ) {
            $todo_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->where( 'status', '!=', 'complete' )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        } else {
            $search = $request->input( 'search.value' );

            $todo_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->where( 'status', '!=', 'complete' )
                ->whereLike( ['todo.name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $totalFiltered = TodosReceived::where( 'user_id', auth()->user()->id )
                ->where( 'status', '!=', 'complete' )
                ->whereLike( ['todo.name'], $search )->count();
        }

        $data = [];
        if ( !empty( $todo_data ) ) {
            foreach ( $todo_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo->todo, [
                    'can_update' => false,
                    'can_chat' => true,
                ] );
            }
        }

        $json_data = [
            "draw" => intval( $request->input( 'draw' ) ),
            "recordsTotal" => $totalData,
            "recordsFiltered" => intval( $totalFiltered ),
            "data" => $data,
        ];

        echo json_encode( $json_data );
        exit();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inProgress() {

        $this->authorize( 'view_todos' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'locale.menu.Todos' )],
        ];

        return view( 'customer.tasks.__in_progress', compact( 'breadcrumbs' ) );
    }

    /**
     * @param  Request  $request
     *
     * @return void
     * @throws AuthorizationException
     */
    public function inProgressSearch( Request $request ) {

        $this->authorize( 'view_todos' );

        $columns = [
            0 => 'responsive_id',
            1 => 'uid',
            2 => 'uid',
            3 => 'name',
            4 => 'created_by',
            5 => 'status',
            6 => 'actions',
        ];

        $totalData = TodosReceived::where( 'user_id', auth()->user()->id )
            ->whereHas( 'todo', function ( $query ) {
                $query->where( 'status', 'in_progress' );
            } )
            ->count();

        $totalData += Todos::where( 'user_id', auth()->user()->id )
            ->where( 'status', 'in_progress' )
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input( 'length' );
        $start = $request->input( 'start' );
        $order = $columns[$request->input( 'order.0.column' )];
        $dir = $request->input( 'order.0.dir' );

        if ( empty( $request->input( 'search.value' ) ) ) {

            $received_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'in_progress' );
                } )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'in_progress' )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        } else {

            $search = $request->input( 'search.value' );

            $received_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'in_progress' ); // Use '=' for exact match
                } )
                ->whereLike( ['todo.name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'in_progress' )
                ->whereLike( ['name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $totalFiltered = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'in_progress' );
                } )
                ->whereLike( ['todo.name'], $search )->count();
            $totalFiltered += Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'in_progress' )
                ->whereLike( ['todo.name'], $search )->count();
        }

        $data = [];
        if ( !empty( $received_data ) ) {
            foreach ( $received_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo->todo, [
                    'can_update' => false,
                    'can_chat' => true,
                ] );
            }
        }

        if ( !empty( $todos_data ) ) {
            foreach ( $todos_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo, [
                    'can_update' => true,
                    'can_chat' => true,
                ] );
            }
        }

        $json_data = [
            "draw" => intval( $request->input( 'draw' ) ),
            "recordsTotal" => $totalData,
            "recordsFiltered" => intval( $totalFiltered ),
            "data" => $data,
        ];

        echo json_encode( $json_data );
        exit();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function complete() {

        $this->authorize( 'view_todos' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'locale.menu.Todos' )],
        ];

        return view( 'customer.tasks.__complete', compact( 'breadcrumbs' ) );
    }

    /**
     * @param  Request  $request
     *
     * @return void
     * @throws AuthorizationException
     */
    public function completeSearch( Request $request ) {

        $this->authorize( 'view_todos' );

        $data = [];

        $columns = [
            0 => 'responsive_id',
            1 => 'uid',
            2 => 'uid',
            3 => 'name',
            4 => 'created_by',
            5 => 'completed_by',
            6 => 'actions',
        ];

        $totalData = Todos::where( 'user_id', auth()->user()->id )
            ->where( 'status', 'complete' )
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input( 'length' );
        $start = $request->input( 'start' );
        $order = $columns[$request->input( 'order.0.column' )];
        $dir = $request->input( 'order.0.dir' );

        if ( empty( $request->input( 'search.value' ) ) ) {

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'complete' )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        } else {

            $search = $request->input( 'search.value' );

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'complete' )
                ->whereLike( ['name', 'completed_by.first_name', 'completed_by.last_name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $totalFiltered = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'complete' )
                ->whereLike( ['todo.name', 'completed_by.first_name'], $search )->count();
        }

        if ( !empty( $todos_data ) ) {
            foreach ( $todos_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo, [
                    'can_update' => true,
                    'can_chat' => true,
                ] );
            }
        }

        /**
         * Task received data
         */
        $totalReceived = TodosReceived::where( 'user_id', auth()->user()->id )
            ->whereHas( 'todo', function ( $query ) {
                $query->where( 'status', 'complete' );
            } )
            ->count();

        $limit = $request->input( 'length' );
        $start = $request->input( 'start' );
        $order = $columns[$request->input( 'order.0.column' )];
        $dir = $request->input( 'order.0.dir' );

        if ( empty( $request->input( 'search.value' ) ) ) {

            $received_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'complete' );
                } )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        } else {

            $search = $request->input( 'search.value' );

            $received_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'complete' );
                } )
                ->whereLike( [
                    'todo.name', 'completed_by.first_name', 'completed_by.last_name',
                ], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $totalReceived = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'complete' );
                } )
                ->whereLike( [
                    'todo.name', 'completed_by.first_name', 'completed_by.last_name',
                ], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        }

        $totalFiltered = $totalReceived;

        if ( !empty( $received_data ) ) {
            foreach ( $received_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo->todo, [
                    'can_update' => false,
                    'can_chat' => true,
                ] );
            }
        }
        /**
         * Task received data completed
         */

        $json_data = [
            "draw" => intval( $request->input( 'draw' ) ),
            "recordsTotal" => $totalData,
            "recordsFiltered" => intval( $totalFiltered ),
            "data" => $data,
        ];

        echo json_encode( $json_data );
        exit();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews() {

        $this->authorize( 'view_todos' );

        $breadcrumbs = [
            ['link' => url( 'dashboard' ), 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => __( 'locale.menu.Todos' )],
        ];

        return view( 'customer.tasks.__reviews', compact( 'breadcrumbs' ) );
    }

    /**
     * @param  Request  $request
     *
     * @return void
     * @throws AuthorizationException
     */
    public function reviewsSearch( Request $request ) {

        $this->authorize( 'view_todos' );

        $columns = [
            0 => 'responsive_id',
            1 => 'uid',
            2 => 'uid',
            3 => 'name',
            4 => 'created_by',
            5 => 'status',
            6 => 'actions',
        ];

        $totalData = TodosReceived::where( 'user_id', auth()->user()->id )
            ->whereHas( 'todo', function ( $query ) {
                $query->where( 'status', 'review' );
            } )
            ->count();

        $totalData += Todos::where( 'user_id', auth()->user()->id )
            ->where( 'status', 'review' )
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input( 'length' );
        $start = $request->input( 'start' );
        $order = $columns[$request->input( 'order.0.column' )];
        $dir = $request->input( 'order.0.dir' );

        if ( empty( $request->input( 'search.value' ) ) ) {

            $received_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'review' );
                } )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'review' )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();
        } else {

            $search = $request->input( 'search.value' );

            $received_data = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'review' ); // Use '=' for exact match
                } )
                ->whereLike( ['todo.name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $todos_data = Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'review' )
                ->whereLike( ['name'], $search )
                ->offset( $start )
                ->limit( $limit )
                ->orderBy( $order, $dir )
                ->get();

            $totalFiltered = TodosReceived::where( 'user_id', auth()->user()->id )
                ->whereHas( 'todo', function ( $query ) {
                    $query->where( 'status', 'review' );
                } )
                ->whereLike( ['todo.name'], $search )->count();
            $totalFiltered += Todos::where( 'user_id', auth()->user()->id )
                ->where( 'status', 'review' )
                ->whereLike( ['todo.name'], $search )->count();
        }

        $data = [];
        if ( !empty( $received_data ) ) {
            foreach ( $received_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo->todo, [
                    'can_update' => false,
                    'can_chat' => true,
                ] );
            }
        }
        if ( !empty( $todos_data ) ) {
            foreach ( $todos_data as $todo ) {

                $data[] = $this->todos->nestedData( $todo, [
                    'can_update' => true,
                    'can_chat' => true,
                ] );
            }
        }

        $json_data = [
            "draw" => intval( $request->input( 'draw' ) ),
            "recordsTotal" => $totalData,
            "recordsFiltered" => intval( $totalFiltered ),
            "data" => $data,
        ];

        echo json_encode( $json_data );
        exit();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * mark as complete.
     * @param  \App\Models\Todos $todo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markAsComplete( Todos $task, Request $request ) {
        $this->authorize( 'update_todos' );

        $request->validate( ['completed_by' => 'required|exists:users,id'] );

        if ( $this->todos->markAsComplete( $task, $request->completed_by ) ) {
            return redirect()->back()->with( [
                'status' => 'success',
                'message' => "Task marked as complete",
            ] );
        }

        return redirect()->back()->with( [
            'status' => 'error',
            'message' => Message::wentWrong(),
        ] );
    }

    /**
     * pase tha task and send notification
     * @param \App\Models\Todos $task
     */
    public function pauseTask( Todos $task ) {

        if ( $task->getOption( 'task_paused_by_' . auth()->user()->id ) ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'You are already requested to pause the task. please wait until creator accept!',
            ] );
        }

        $username = User::fullname();
        $show_link = route( 'customer.tasks.show', $task->uid );
        $edit_link = route( 'customer.tasks.edit', $task->uid );
        $view_link = "<a href='$show_link'> click here</a>";
        $subject = "$username has requested to pause the task #" . $task->uid;
        $message = "<b>$username</b> has requested to resume the task " . $task->name;
        $message .= '<br>To accept the the request you have update the task: ' . $edit_link;
        $message .= '<br>For more information: ' . $view_link;

        Notifications::create( [
            'user_id' => $task->user_id,
            'type' => 'task',
            'name' => $subject,
            'message' => $message,
            'created_by' => Auth::id(),
        ] );

        if ( config( 'task.task_send_email' ) ) {
            Mail::to( User::find( $task->user_id ) )->send( new TodoMail( [
                'subject' => $subject,
                'message' => $message,
                'taskurl' => $show_link,
            ] ) );
        }

        if ( $task->setOption( 'task_paused_by_' . Auth::id(), true ) ) {
            return response()->json( [
                'status' => 'success',
                'message' => 'Your task pause request is made successfully',
            ] );
        }

        return response()->json( [
            'status' => 'error',
            'message' => 'unable to pause the task',
        ] );
    }
    /**
     * pase tha task and send notification
     * @param \App\Models\Todos $task
     */
    public function continueTask( Todos $task ) {

        if ( !$task->getOption( 'task_paused_by_' . auth()->user()->id ) ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'probably the task is made paused you!',
            ] );
        }

        $username = User::fullname();
        $show_link = route( 'customer.tasks.show', $task->uid );
        $edit_link = route( 'customer.tasks.edit', $task->uid );
        $view_link = "<a href='$show_link'> click here</a>";
        $subject = "$username has requested to continue the task #" . $task->uid;
        $message = "<b>$username</b> has requested to continue the task " . $task->name;
        $message .= '<br>continue start the task you have update the task: ' . $edit_link;
        $message .= '<br>For more information: ' . $view_link;

        Notifications::create( [
            'user_id' => $task->user_id,
            'type' => 'task',
            'name' => $subject,
            'message' => $message,
            'created_by' => Auth::id(),
        ] );

        if ( config( 'task.task_send_email' ) ) {
            Mail::to( User::find( $task->user_id ) )->send( new TodoMail( [
                'subject' => $subject,
                'message' => $message,
                'taskurl' => $show_link,
            ] ) );
        }

        if ( $task->setOption( 'task_paused_by_' . Auth::id(), false ) ) {
            return response()->json( [
                'status' => 'success',
                'message' => 'Your request has been processed',
            ] );
        }

        return response()->json( [
            'status' => 'error',
            'message' => 'unable to pause the task',
        ] );
    }
}
