<?php

/**
 * @title todo respository
 * @author appsaeed <appsaeed7@gmail.com>
 * @link https://appsaeed.github.io
 * @datetime 2023-8-23
 */

namespace App\Repositories;

use App\Helpers\Worker;
use App\Library\Tool;
use App\Mail\TodoMail;
use App\Models\ChatBox;
use App\Models\ChatBoxMessage;
use App\Models\Notifications;
use App\Models\Todos;
use App\Models\TodosReceived;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TodosRepository {
    /**
     * store todo
     * @param \Illuminate\Http\Request
     * @return bool
     */
    public function store( Request $request ) {
        try {
            $send_email = config( 'task.task_send_email' );

            DB::beginTransaction();

            $todos = new Todos();
            $notifocations = new Notifications();
            $todoReceived = new TodosReceived();

            $createTodo = $todos->create( [
                 ...$request->only( $todos->getFillable() ),
                'user_id' => auth()->user()->id,
                'assign_to' => json_encode( $request->assign_to ),
            ] );

            $show_link = route( 'customer.tasks.show', $createTodo->uid );
            $view_link = "<a href='$show_link'> click here</a>";
            $subject = "You have received new task from " . User::fullname();
            $message = 'You have received new task from <b>' . User::fullname() . '</b> and for more information: ' . $view_link;

            if ( $createTodo->save() ) {

                $notifies = [];
                $receives = [];

                if ( in_array( 'all', $createTodo->assigned() ) ) {

                    $users = User::allcustomers();

                    foreach ( $users as $user ) {

                        if ( $send_email ) {
                            Mail::to( $user )->send( new TodoMail( [
                                'subject' => $subject,
                                'message' => $message,
                                'taskurl' => $show_link,
                            ] ) );
                        }

                        $notifies[] = [
                            'user_id' => $user->id,
                            'type' => 'task',
                            'name' => $subject,
                            'message' => $message,
                            'created_by' => auth()->user()->id,
                        ];

                        $receives[] = [
                            'user_id' => $user->id,
                            'todo_id' => $createTodo->id,
                        ];

                        $chatbox = ChatBox::create( [
                            'user_id' => Auth::user()->id,
                            'from' => auth()->user()->id,
                            'to' => $user->id,
                            'todo_id' => $createTodo->id,
                            'notification' => true,
                        ] );

                        ChatBoxMessage::create( [
                            'box_id' => $chatbox->id,
                            'message' => 'New task: ' . $createTodo->name,
                            'send_by' => 'from',
                        ] );

                        $chatbox->touch();
                    }

                    //complete for all users
                } else {
                    foreach ( $createTodo->assigned() as $id ) {

                        if ( $send_email ) {
                            Mail::to( User::find( $id ) )->send( new TodoMail( [
                                'subject' => $subject,
                                'message' => $message,
                                'taskurl' => $show_link,
                            ] ) );
                        }

                        $notifies[] = [
                            'user_id' => $id,
                            'type' => 'task',
                            'name' => $subject,
                            'message' => $message,
                            'created_by' => auth()->user()->id,
                        ];

                        $receives[] = [
                            'user_id' => $id,
                            'todo_id' => $createTodo->id,
                        ];

                        $chatbox = ChatBox::create( [
                            'user_id' => Auth::user()->id,
                            'from' => auth()->user()->id,
                            'to' => $id,
                            'todo_id' => $createTodo->id,
                            'notification' => 0,
                        ] );

                        ChatBoxMessage::create( [
                            'box_id' => $chatbox->id,
                            'message' => 'New task: ' . $createTodo->name,
                            'send_by' => 'from',
                        ] );

                        $chatbox->touch();
                    }
                    //complete to ganerate for selected user
                }

                $todoReceived->insert( $receives ) && $notifocations->insert( $notifies );

                DB::commit();
            }

            return true;
        } catch ( \Throwable $th ) {
            return false;
        }
    }

    /**
     * update todo
     * @param \Illuminate\Http\Request
     * @param \App\Models\Todos
     * @return bool
     */
    public function update( Request $request, Todos $task ) {

        try {

            $send_email = config( 'task.task_send_email' );

            $notifocation = new Notifications();

            $show_link = route( 'customer.tasks.show', $task->uid );
            $view_link = "<a href='$show_link'> click here</a>";

            $subject = User::fullname() . ' has updated a task.';
            $message = '<b>' . User::fullname() . '</b> has updated status';
            $message .= '<br><b>task name</b>: ' . $task->name;
            $message .= '<br><b>status</b>:' . $task->status;
            $message .= "<br><br>for more information: " . $view_link;

            if ( $request->status != $task->status ) {

                $subject = User::fullname() . ' has updated to ' . $request->status;
            }

            if ( $request->status == 'pause' ) {
                $task->setOption( 'auth_paused_at', now() );
            }

            $task->update( $request->only( $task->getFillable() ) );

            if ( $request->status == 'continue' ) {

                $deadline = Carbon::parse( $task->deadline );
                $pause_date = Carbon::parse( $task->getOption( 'auth_paused_at' ) );

                // Add the number of seconds represented by $pause_date to $deadline
                $deadline->addSeconds( $pause_date->diffInSeconds() );

                // Update the deadline property in $task object
                $task->deadline = $deadline;

                $task->save();

                $task->removeOption( 'auth_paused_at' );
            }

            $this->notifications( $task, [
                'subject' => $subject,
                'message' => $message,
                'send_email' => $request->send_email,
            ] );

            return true;
        } catch ( \Throwable $th ) {
            return false;
        }
    }

    /**
     * create notifications
     * @param \App\Models\User
     * @param array $data
     * @return bool
     */
    public function notifications( Todos $task, array $data = [] ) {
        try {
            $send_email = false;

            $show_link = route( 'customer.tasks.show', $task->uid );
            $view_link = "<a href='$show_link'> click here</a>";
            $subject = "You have received new task from " . User::fullname();
            $message = 'You have received new task from <b>' . User::fullname() . '</b> and for more information: ' . $view_link;

            if ( isset( $data['subject'] ) ) {
                $subject = $data['subject'];
            }
            if ( isset( $data['message'] ) ) {
                $message = $data['message'];
            }

            if ( isset( $data['send_email'] ) && $data['send_email'] ) {
                $send_email = true;
            }

            $notifies = [];

            if ( in_array( 'all', $task->assigned() ) ) {

                $users = User::allcustomers();

                if ( $send_email ) {
                    Mail::to( $users )->send( new TodoMail( [
                        'subject' => $subject,
                        'message' => $message,
                        'taskurl' => $show_link,
                    ] ) );
                }

                foreach ( $users as $user ) {

                    $notifies[] = [
                        'user_id' => $user->id,
                        'type' => 'task',
                        'name' => $subject,
                        'message' => $message,
                        'created_by' => auth()->user()->id,
                    ];
                }

                //complete for all users
            } else {
                foreach ( $task->assigned() as $id ) {

                    if ( $send_email ) {
                        Mail::to( User::find( $id ) )->send( new TodoMail( [
                            'subject' => $subject,
                            'message' => $message,
                            'taskurl' => $show_link,
                        ] ) );
                    }

                    $notifies[] = [
                        'user_id' => $id,
                        'type' => 'task',
                        'name' => $subject,
                        'message' => $message,
                        'created_by' => auth()->user()->id,
                    ];
                }
                //complete to ganerate for selected user
            }

            return Notifications::insert( $notifies );
        } catch ( \Throwable $th ) {
            return false;
        }
    }

    /**
     * create markasComplete
     * @param \App\Models\Todos $task
     * @param mixed $user_id
     * @param bool $send_email
     * @return bool
     */
    public function markasComplete( Todos $task, $user_id, $send_email = false ) {
        try {

            if ( !$task->update( ['status' => 'complete', 'completed_by' => $user_id] ) ) {
                return false;
            }

            $show_link = route( 'customer.tasks.show', $task->uid );
            $view_link = "<a href='$show_link'> click here</a>";
            $subject = '#' . $task->uid . ' task completed by ' . User::fullname( $user_id );
            $message = "<b>" . $task->name . "</b> is completed by " . User::fullname( $user_id );
            $message = "<br> for more information open the task: $view_link";

            $notifies = [];

            // TodosReceived::where('todo_id', $task->id)->delete();

            if ( in_array( 'all', $task->assigned() ) ) {

                $users = User::allcustomers();

                if ( $send_email ) {
                    Mail::to( $users )->send( new TodoMail( [
                        'subject' => $subject,
                        'message' => $message,
                        'taskurl' => $show_link,
                    ] ) );
                }

                foreach ( $users as $user ) {

                    $notifies[] = [
                        'user_id' => $user->id,
                        'type' => 'task',
                        'name' => $subject,
                        'message' => $message,
                        'created_by' => auth()->user()->id,
                    ];
                }

                //complete for all users
            } else {
                foreach ( $task->assigned() as $id ) {

                    if ( $send_email ) {
                        Mail::to( User::find( $id ) )->send( new TodoMail( [
                            'subject' => $subject,
                            'message' => $message,
                            'taskurl' => $show_link,
                        ] ) );
                    }

                    $notifies[] = [
                        'user_id' => $id,
                        'type' => 'task',
                        'name' => $subject,
                        'message' => $message,
                        'created_by' => auth()->user()->id,
                    ];
                }
                //complete to ganerate for selected user
            }

            return Notifications::insert( $notifies );
        } catch ( \Throwable $th ) {
            return false;
        }
    }

    /**
     * create deadline notifications by cron job
     * @param \App\Models\Todos $task
     * @return void
     */
    public static function cronNotiffy( Todos $task ) {
        $send_email = config( 'task.task_send_email' );

        $dedlining = Carbon::create( $task->deadline )->longRelativeDiffForHumans( now(), 2 );
        $deadlineAt = Carbon::parse( $task->deadline )->format( 'Y M D h:i' );

        DB::beginTransaction();

        $show_link = route( 'customer.tasks.show', $task->uid );
        $view_link = "<a href='$show_link'> click here</a>";
        $subject = "Task deadline  " . $dedlining;
        $message = 'Task deadline <code>' . $dedlining . '</code><br>';
        $message .= 'You must complete this task at  <code>' . $deadlineAt . '</code><br>';
        $message .= 'Task name:  <b>' . $task->name . '</b><br>';
        $message .= 'Task created by:  <b>' . User::fullname( $task->user_id ) . '</b><br>';
        $message .= 'For more information: ' . $view_link;

        $notifies = [];

        if ( in_array( 'all', $task->assigned() ) ) {

            $users = User::allcustomers( true );

            if ( $send_email ) {
                Mail::to( $users )->send( new TodoMail( [
                    'subject' => $subject,
                    'message' => $message,
                    'taskurl' => $show_link,
                ] ) );
            }

            foreach ( $users as $user ) {

                $notifies[] = [
                    'user_id' => $user->id,
                    'type' => 'task',
                    'name' => $subject,
                    'message' => $message,
                    'created_by' => $task->user_id,
                ];
            }

            //complete for all users
        } else {

            $notifies[] = [
                'user_id' => $task->user_id,
                'type' => 'task',
                'name' => $subject,
                'message' => $message,
                'created_by' => $task->user_id,
            ];

            foreach ( $task->assigned() as $id ) {

                if ( $send_email ) {
                    Mail::to( User::find( $id ) )->send( new TodoMail( [
                        'subject' => $subject,
                        'message' => $message,
                        'taskurl' => $show_link,
                    ] ) );
                }

                $notifies[] = [
                    'user_id' => $id,
                    'type' => 'task',
                    'name' => $subject,
                    'message' => $message,
                    'created_by' => $task->user_id,
                ];
            }
            //complete to ganerate for selected user
        }

        Notifications::insert( $notifies );

        DB::commit();
    }

    /**
     * Table nestedData
     * @param \App\Models\Todos
     * @param array $options
     */
    public function nestedData( Todos $task, $abilities = [] ) {
        $options = $abilities;
        if ( auth()->id() === 1 ) {
            $options = [
                'can_update' => true,
                'can_delete' => true,
                'can_create' => true,
                'can_chat' => true,
            ];
        }
        $chat_own_url = route( 'customer.chat.open', $task->uid );
        $message__url = route( 'customer.chat.receiver', $task->uid );

        $nestedData = [];
        $nestedData['responsive_id'] = '';
        $nestedData['id'] = $task->id;
        $nestedData['uid'] = $task->uid;
        $nestedData['avatar'] = route( 'customer.getAvatar', $task->user->uid );
        $nestedData['email'] = $task->user->email;
        $nestedData['user_name'] = $task->user->displayName();
        $nestedData['created_at'] = Worker::todoCreated_at( $task->created_at );
        $nestedData['name'] = Worker::todoNameHtml(
            $task->name,
            $task->deadline,
            route( 'customer.tasks.show', $task->uid )
        );
        if ( $task->user_id === auth()->user()->id ) {
            $nestedData['created_by'] = 'You';
        } else {
            $nestedData['created_by'] = Worker::todoCreatedBy(
                $task->user->displayName(),
                $task->user->email,
                route( 'customer.getAvatar', $task->user->uid )
            );
        }

        $nestedData['completed_by'] = Worker::todoCompletedByid( $task->completed_by );
        $nestedData['completed_at'] = Tool::formatDate( $task->updated_at );

        $nestedData['assign_to'] = Worker::todoAissignedUsers( $task );
        $nestedData['status'] = Worker::todoStatus( $task->status );

        //delete options
        $nestedData['can_delete'] = $task->isCreator();
        $nestedData['delete'] = $task->uid;

        //chat options
        $nestedData['can_chat'] = isset( $options['can_chat'] ) && $options['can_chat'];
        $nestedData['chat_url'] = $task->isCreator() ? $chat_own_url : $message__url;

        //update options
        $nestedData['can_update'] = isset( $options['can_update'] ) && $options['can_update'];
        $nestedData['update'] = route( 'customer.tasks.edit', $task->uid );

        //update options
        $nestedData['can_edit'] = isset( $options['can_update'] ) && $options['can_update'];
        $nestedData['edit'] = route( 'customer.tasks.edit', $task->uid );

        //update options
        $nestedData['can_view'] = true;
        $nestedData['view'] = route( 'customer.tasks.show', $task->uid );

        return $nestedData;
    }
}
