<?php

namespace App\Http\Controllers;

use App\Mail\TodoMail;
use App\Models\Notifications;
use App\Models\Todos;
use App\Models\User;
use App\Repositories\TodosRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use PO;

class Testcontroller extends Controller
{

    public function index()
    {
        $minutes = 1;
        // return $this->lastCron();
        $tasks = Todos::where('status', '!=', 'complete')
            ->where('deadline', '>', now())
            ->where('deadline', '>=', now()->addMinutes($minutes))
            ->where('last_cron', '<=', now()->subMinutes($minutes))
            ->get();

        foreach ($tasks as $task) {
            $task->last_cron = now();
            $task->save();
        }

        return $tasks;
    }
    public function lastCron()
    {

        $tasks =  Todos::where('status', '!=', 'complete')
            ->where('deadline', '>', now())
            ->first();

        return Carbon::create($tasks->last_cron)->longRelativeDiffForHumans(now(), 3);
    }
}
