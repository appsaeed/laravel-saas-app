<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PhoneNumbers;
use App\Models\Reports;
use App\Models\Todos;
use App\Models\TodosReceived;
use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
        /**
         * @var UserRepository
         */
        protected $users;

        /**
         * UserController constructor.
         *
         * @param  UserRepository  $users
         */
        public function __construct(UserRepository $users)
        {
                $this->users = $users;
        }

        /**
         * Show user homepage.
         *
         * @return Application|Factory|\Illuminate\Contracts\View\View|View
         */
        public function index()
        {

                $breadcrumbs = [
                        ['link' => url('dashboard'), 'name' => __('locale.menu.Dashboard')],
                        ['name' => User::fullname()],
                ];

                $created = (object) [
                        'in_progress' => Todos::where('user_id', Auth::id())->where('status', 'in_progress')->count(),
                        'complete' => Todos::where('user_id', Auth::id())->where('status', 'complete')->count(),
                        'reviews' => Todos::where('user_id', Auth::id())->where('status', 'review')->count(),
                        'all' => Todos::where('user_id', Auth::id())->count(),
                ];

                $received = (object)[
                        'in_progress' => TodosReceived::where('user_id', Auth::id())
                                ->whereHas('todo', function ($query) {
                                        $query->where('status', 'in_progress');
                                })->count(),
                        'complete' => TodosReceived::where('user_id', Auth::id())
                                ->whereHas('todo', function ($query) {
                                        $query->where('status', 'complete');
                                })->count(),

                        'reviews' => TodosReceived::where('user_id', Auth::id())
                                ->whereHas('todo', function ($query) {
                                        $query->where('status', 'review');
                                })->count(),
                        'all' => TodosReceived::where('user_id', Auth::id())->count(),
                ];


                $created = (object) [
                        'in_progress' => Todos::where('user_id', Auth::id())->where('status', 'in_progress')->count(),
                        'complete' => Todos::where('user_id', Auth::id())->where('status', 'complete')->count(),
                        'reviews' => Todos::where('user_id', Auth::id())->where('status', 'review')->count(),
                        'all' => Todos::where('user_id', Auth::id())->count(),
                ];

                $created_list = (object) [
                        'in_progress' => Todos::where('user_id', Auth::id())->where('status', 'in_progress')->get(),
                        'complete' => Todos::where('user_id', Auth::id())->where('status', 'complete')->get(),
                        'reviews' => Todos::where('user_id', Auth::id())->where('status', 'review')->get(),
                        'paused' => Todos::where('user_id', Auth::id())->where('status', 'pause')->get(),
                        'all' => Todos::where('user_id', Auth::id())->get(),
                ];

                $received_list = (object)[
                        'in_progress' => TodosReceived::where('user_id', Auth::id())
                                ->whereHas('todo', function ($query) {
                                        $query->where('status', 'in_progress');
                                })->get(),
                        'complete' => TodosReceived::where('user_id', Auth::id())
                                ->whereHas('todo', function ($query) {
                                        $query->where('status', 'complete');
                                })->get(),

                        'reviews' => TodosReceived::where('user_id', Auth::id())
                                ->whereHas('todo', function ($query) {
                                        $query->where('status', 'review');
                                })->get(),
                        'all' => TodosReceived::where('user_id', Auth::id())->get(),
                ];

                return view(
                        'customer.dashboard',
                        compact(
                                'breadcrumbs',
                                'created',
                                'received',
                                'created_list',
                                'received_list'
                        )
                );
        }
}
