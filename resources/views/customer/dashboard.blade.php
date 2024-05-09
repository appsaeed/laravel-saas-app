@extends('layouts/contentLayoutMaster')

@section('title', __('locale.menu.Dashboard'))

@section('page-style')
    {{-- Page css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/dashboard-ecommerce.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
@endsection
@php
    function statusToColor($status = 0)
    {
        $colors = [
            'in_progress' => '#00cfe8',
            'review' => '#ff9f43',
            'complete' => '#28c76f',
            'pause' => '#7367f0',
            'all' => '#7367f0',
        ];
        $color = isset($colors[$status]) ? $colors[$status] : $colors['all'];
        return $color;
    }
@endphp
<style>
    .task-boxable {
        max-height: 400px;
        overflow-y: scroll;
    }

    .card-bg-gray {
        background-color: #3b4253 !important;
    }

    .task-card {
        background: #3b4253;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 16px;
    }

    .task-content {
        padding: 10px;
        position: relative;
    }

    .task-link {
        position: absolute;
        right: 0;
    }

    .task-link a {
        color: unset !important;
    }
</style>
@section('content')
    {{-- Dashboard Analytics Start --}}
    <section>

        <div class="row">

            <div class="mb-1">
                <h2 class="fw-bolder">
                    {{ __('Your created task status') }}
                </h2>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ $created->in_progress }}</h2>
                            <p class="card-text">{{ __('locale.menu.In progress') }}</p>
                        </div>
                        <a href="{{ route('customer.tasks.in_progress') }}" class="avatar bg-light-info p-50 m-0">
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
                            <h2 class="fw-bolder mb-0">{{ $created->reviews }}</h2>
                            <p class="card-text">{{ __('locale.menu.Reviews') }}</p>
                        </div>
                        <a href="{{ route('customer.tasks.reviews') }}" class="avatar bg-light-warning p-50 m-0">
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
                            <h2 class="fw-bolder mb-0">{{ $created->complete }}</h2>
                            <p class="card-text">{{ __('locale.menu.Completed') }}</p>
                        </div>
                        <a href="{{ route('customer.tasks.complete') }}" class="avatar bg-light-success p-50 m-0">
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
                            <h2 class="fw-bolder mb-0">{{ $created->all }}</h2>
                            <p class="card-text">{{ __('locale.menu.All ads') }}</p>
                        </div>
                        <a href="{{ route('customer.tasks.index') }}" class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="credit-card" class="text-primary font-medium-5"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="mb-1">
                <h2 class="fw-bolder">
                    {{ __('Your received task status') }}
                </h2>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="fw-bolder mb-0">{{ $received->in_progress }}</h2>
                            <p class="card-text">{{ __('locale.menu.In progress') }}</p>
                        </div>
                        <a href="{{ route('customer.tasks.in_progress') }}" class="avatar bg-light-info p-50 m-0">
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
                            <h2 class="fw-bolder mb-0">{{ $received->reviews }}</h2>
                            <p class="card-text">{{ __('locale.menu.Reviews') }}</p>
                        </div>
                        <a href="{{ route('customer.tasks.reviews') }}" class="avatar bg-light-warning p-50 m-0">
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
                            <h2 class="fw-bolder mb-0">{{ $received->complete }}</h2>
                            <p class="card-text">{{ __('locale.menu.Completed') }}</p>
                        </div>
                        <a href="{{ route('customer.tasks.complete') }}" class="avatar bg-light-success p-50 m-0">
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
                            <h2 class="fw-bolder mb-0">{{ $received->all }}</h2>
                            <p class="card-text">{{ __('All received') }}</p>
                        </div>
                        <a href="{{ route('customer.tasks.receives') }}" class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="credit-card" class="text-primary font-medium-5"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="mb-1">
                <h1>Create task list</h1>

            </div>

            {{-- in progress --}}
            @include('customer.taskcard', [
                'task' => [
                    'name' => __('locale.menu.In progress'),
                    'items' => $created_list->in_progress,
                ],
            ])
            {{-- in progress --}}
            @include('customer.taskcard', [
                'task' => [
                    'name' => __('locale.menu.Reviews'),
                    'items' => $created_list->reviews,
                ],
            ])
            {{-- reviews --}}
            @include('customer.taskcard', [
                'task' => [
                    'name' => __('locale.menu.Completed'),
                    'items' => $created_list->complete,
                ],
            ])
            {{-- reviews --}}
            @include('customer.taskcard', [
                'task' => [
                    'name' => __('Paused Task'),
                    'items' => $created_list->paused,
                ],
            ])

        </div>
    </section>
    <!-- Dashboard Analytics end -->
@endsection
