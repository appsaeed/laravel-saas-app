@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

{{-- Vendor Css files --}}
@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/tether-theme-arrows.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/tether.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/shepherd.min.css')) }}">
@endsection

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-tour.css')) }}">
@endsection



@section('content')

    <section>

        <div class="row">

            <div class="mb-1">
                <h2 class="fw-bolder">
                    {{ __('Task status') }}
                </h2>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ $task->in_progress }}</h2>
                            <p class="card-text">{{ __('locale.menu.In progress') }}</p>
                        </div>
                        <a href="{{ route('admin.todos.index') }}" class="avatar bg-light-info p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="disc" class="text-info font-medium-5"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ $task->reviews }}</h2>
                            <p class="card-text">{{ __('locale.menu.Reviews') }}</p>
                        </div>
                        <a href="{{ route('admin.todos.index') }}" class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="star" class="text-warning font-medium-5"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ $task->complete }}</h2>
                            <p class="card-text">{{ __('locale.menu.Completed') }}</p>
                        </div>
                        <a href="{{ route('admin.todos.index') }}" class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="check-square" class="text-success font-medium-5"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ $task->all }}</h2>
                            <p class="card-text">{{ __('locale.menu.All ads') }}</p>
                        </div>
                        <a href="{{ route('customer.todos.all') }}" class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="credit-card" class="text-primary font-medium-5"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-end">
                        <h4 class="card-title text-uppercase">{{ __('locale.labels.customers_growth') }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body pb-0">
                            <div id="customer-growth"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection

@section('vendor-script')
    {{--     Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/tether.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/shepherd.min.js')) }}"></script>
@endsection


@section('page-script')
    <!-- Page js files -->


    <script>
        $(window).on("load", function() {

            let $primary = '#7367F0';
            let $strok_color = '#b9c3cd';
            let $label_color = '#e7eef7';
            let $purple = '#df87f2';

        });


        // Client growth Chart
        // ----------------------------------

        let clientGrowthChartoptions = {
            chart: {
                stacked: true,
                type: 'bar',
                toolbar: {
                    show: false
                },
                height: 290,
            },
            plotOptions: {
                bar: {
                    columnWidth: '70%'
                }
            },
            colors: ['#7367F0'],
            series: {!! $customer_growth->dataSet() !!},
            grid: {
                borderColor: '#e7eef7',
                padding: {
                    left: 0,
                    right: 0
                }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'left',
                offsetX: 0,
                fontSize: '14px',
                markers: {
                    radius: 50,
                    width: 10,
                    height: 10,
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                labels: {
                    style: {
                        colors: '#b9c3cd',
                    }
                },
                axisTicks: {
                    show: false,
                },
                categories: {!! $customer_growth->xAxis() !!},
                axisBorder: {
                    show: false,
                },
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        color: '#b9c3cd',
                    },
                    formatter: function(val) {
                        return val.toFixed(1)
                    }
                }
            },
            tooltip: {
                x: {
                    show: false
                }
            },
        }

        let clientGrowthChart = new ApexCharts(
            document.querySelector("#customer-growth"),
            clientGrowthChartoptions
        );

        clientGrowthChart.render();
    </script>
@endsection
