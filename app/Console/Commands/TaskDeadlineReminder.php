<?php

namespace App\Console\Commands;

use App\Models\Todos;
use App\Repositories\TodosRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TaskDeadlineReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:deadline-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Task deadline reminder notification for task connected users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {

            $minutes = [
                10, // 10 minutes
                30, //  30 minutes
                60,   // 1 hour
                360,  // 6 hours
                720,  // 12 hours
                1440, // 24 hours
            ];


            foreach ($minutes as $minute) {
                $tasks = Todos::where('status', '!=', 'complete')
                    ->where('deadline', '>', now())
                    ->where('deadline', '>=', now()->addMinutes($minute))
                    ->where('last_cron', '<=', now()->subMinutes($minute))
                    ->get();

                foreach ($tasks as $task) {
                    Log::info("task deadline remainder for 24 hours");
                    TodosRepository::cronNotiffy($task);
                    $task->last_cron = now();
                    $task->save();
                }
            }
            //completed tasks
        } catch (\Throwable $th) {
            Log::error('command error: ' . $th->getMessage());
        }

        return Command::SUCCESS;
    }
}
