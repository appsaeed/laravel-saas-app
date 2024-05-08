<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoices;
use App\Models\Todos;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBaseController extends Controller {
    /**
     * Show admin home.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function index() {

        $breadcrumbs = [
            ['link' => "/dashboard", 'name' => __( 'locale.menu.Dashboard' )],
            ['name' => User::fullname()],
        ];

        $revenue = Invoices::CurrentMonth()
            ->selectRaw( 'EXTRACT(DAY FROM created_at) as day, count(uid) as revenue' )
            ->groupBy( 'day' )
            ->pluck( 'revenue', 'day' );

        $revenue_chart = ( new LarapexChart )->lineChart()
            ->addData( __( 'locale.labels.revenue' ), $revenue->values()->toArray() )
            ->setXAxis( $revenue->keys()->toArray() );

        $customers = Customer::thisYear()
            ->selectRaw( 'EXTRACT(MONTH FROM created_at) as month, count(uid) as customer' )
            ->groupBy( 'month' )
            ->orderBy( 'month' )
            ->pluck( 'customer', 'month' );

        $customer_growth = ( new LarapexChart )->barChart()
            ->addData( __( 'locale.labels.customers_growth' ), $customers->values()->toArray() )
            ->setXAxis( $customers->keys()->toArray() );

        $task = (object) [
            'in_progress' => Todos::where( 'status', 'in_progress' )->count(),
            'complete' => Todos::where( 'status', 'complete' )->count(),
            'reviews' => Todos::where( 'status', 'review' )->count(),
            'all' => Todos::count(),
        ];

        return view( 'admin.dashboard', compact( 'breadcrumbs', 'revenue_chart', 'customer_growth', 'task' ) );
    }

    protected function redirectResponse( Request $request, $message, $type = 'success' ) {
        if ( $request->wantsJson() ) {
            return response()->json( [
                'status' => $type,
                'message' => $message,
            ] );
        }

        return redirect()->back()->with( "flash_{$type}", $message );
    }
}
