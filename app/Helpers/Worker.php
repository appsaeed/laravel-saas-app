<?php

namespace App\Helpers;

use App\Library\Tool;
use App\Models\Todos;
use App\Models\User;
use App\Scopes\StatusScope;
use Carbon\Carbon;

/**
 * Helper Worker 
 * @author appsaeed7@gmail.com
 * @copyright 2023
 * @author_url link - https://appsaeed.github.io
 */
class Worker
{
    /**
     * @method todoStatus
     * @param string $status
     * @return string
     */
    public static function todoStatus($status)
    {
        $className = '';
        $data =  [
            'available'         => 'info',
            'in_progress'       => 'primary',
            'review'            => 'warning',
            'complete'          => 'success',
            'pending'           => 'info',
            'pause'             => 'info',
            'continue'          => 'primary',
        ];

        if (isset($data[$status])) {
            $className = $data[$status];
        };

        return "<div><h5 class='text-bold-600 text-" . $className . "'>" .
            str_replace('_', ' ', $status) . "</h5></div>";
    }

    /**
     * @method static todoCreatedBy
     * @param string $name
     * @param string $email
     * @param string $image
     * @return string
     */
    public static function todoCreatedBy($name, $email, $image)
    {
        return '<div class="d-flex justify-content-left align-items-center"><div class="avatar  me-1"><img src="' . $image . '" alt="Avatar" width="32" height="32"></div><div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">' . $name . '</span><small class="emp_post text-truncate text-muted"> ' . $email . '</small></div></div>';
    }
    /**
     * @method static todoCreatedBy
     * @param string $name
     * @param string $email
     * @param string $image
     * @return string
     */
    public static function todoAissignedUsers(Todos $todo)
    {
        try {
            $names = [];
            if (in_array('all', $todo->assigned())) {
                return 'available for all';
            } else {
                foreach ($todo->assigned() as $user_id) {
                    $names[] = User::find($user_id)::fullname();
                }
            }
            return join(' <span class="text-primary">|</span> ', $names);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * @method static showUserbyid
     * @param string $user_id
     * @return string
     */
    public static function todoCompletedByid($user_id)
    {
        $name = '';
        $email = '';
        $image = '';
        $user = User::find($user_id);
        if ($user) {
            $name = $user->fullname($user_id);
            $email = $user->email;
            $image = route('user.avatar', $user->uid);
        }
        return '<div class="d-flex justify-content-left align-items-center"><div class="avatar  me-1"><img src="' . $image . '" alt="Avatar" width="32" height="32"></div><div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">' . $name . '</span><small class="emp_post text-truncate text-muted"> ' . $email . '</small></div></div>';
    }
    /**
     * @method static showUserbyid
     * @param string $user_id
     * @return string
     */
    public static function profileHtmlByid($user_id)
    {
        $name = '';
        $email = '';
        $image = '';
        $user = User::find($user_id);
        if ($user) {
            $name = $user->first_name . ' ' . $user->last_name;
            $email = $user->email;
            $image = route('customer.getAvatar', $user->uid);
        }
        return '<div class="d-flex justify-content-left align-items-center"><div class="avatar  me-1"><img src="' . $image . '" alt="Avatar" width="32" height="32"></div><div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">' . $name . '</span><small class="emp_post text-truncate text-muted"> ' . $email . '</small></div></div>';
    }

    /**
     * @method static todoCreatedBy
     * @param string $name
     * @param string $email
     * @param string $image
     * @return string
     */
    public static function usernameWithAvatar(string $name, string $email,  string $image = null)
    {
        if (!$image) {
            $image = "https://ui-avatars.com/api/?name=$name";
        }

        return '<div class="d-flex justify-content-left align-items-center"><div class="avatar  me-1"><img src="' . $image . '" alt="Avatar" width="32" height="32"></div><div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">' . $name . '</span><small class="emp_post text-truncate text-muted"> ' . $email . '</small></div></div>';
    }
    public static function getUserByid($user_id)
    {
        $image = null;
        $name  = '';
        $email  = '';


        return '<div class="d-flex justify-content-left align-items-center"><div class="avatar  me-1"><img src="' . $image . '" alt="Avatar" width="32" height="32"></div><div class="d-flex flex-column"><span class="emp_name text-truncate fw-bold">' . $name . '</span><small class="emp_post text-truncate text-muted"> ' . $email . '</small></div></div>';
    }


    /**
     * Todod created and received count for the user
     * @param string $created
     * @param string $received
     * @return string
     */
    public static function todoGotCount($created, $received)
    {
        return "Created: $created <br> Received: $received";
    }


    /**
     * @param string $name
     * @param string $deadline
     * @param string $link
     */
    public static function todoNameHtml(string $name, $deadline, $link)
    {
        $time = Carbon::create($deadline)->longRelativeDiffForHumans(\Carbon\Carbon::now(), 1);

        return "<div><h5 class='text-bold-600'><a href='$link' >" . ucfirst($name) . "</a>  </h5><span class='text-muted'>deadline: $time </span></div>";
    }

    /**
     * make created at 
     * @param string $created_at
     * @return string
     */
    public static function todoCreated_at($created_at)
    {
        return __('locale.labels.created_at') . ': ' . Tool::formatDate($created_at);
    }


    /**
     *  string url to link
     */
    public static function linkify($s)
    {
        return preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.%-=#]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
    }
}
