@extends('layouts/contentLayoutMaster')
@section('title', $task->name))
@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection


@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
<style>
    .table-responsive table td {
        padding: 20px !important;
    }

    table {}

    table tbody {
        border: none !important;
    }
</style>
@section('content')

    <!-- Basic table -->
    <section id="datatables-basic">

        <div class="mb-3 mt-2">
            <div class="btn-group">
                <a href="{{ route('customer.tasks.index') }}" class="btn btn-primary fw-bold me-1" type="button">
                    {{ __('locale.buttons.back') }} </a>
            </div>
            @if (auth()->user()->id != $task->user_id)
                @include('customer.tasks.show.public-buttons')
            @else
                @include('customer.tasks.show.auth-buttons')
            @endif
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title"> {{ $task->name }} </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive table datatables-basic">
                                <table class="table">
                                    <tbody>

                                        <tr>
                                            <td style="word-break: initial">{{ __('messages.deadline') }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::create($task->deadline)->longRelativeDiffForHumans(\Carbon\Carbon::now(), 2) }}
                                                <br>
                                                <small class="emp_post text-truncate text-muted">
                                                    {{ \Carbon\Carbon::parse($task->deadline)->format('Y m M d,  D m:h') }}
                                                </small>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="word-break: initial">{{ __('locale.labels.state') }}</td>
                                            <td>
                                                <span
                                                    class="text-{{ \App\Helpers\Message::todoStatusclass($task->status) }}">
                                                    {{ str_replace('_', ' ', $task->status) }}
                                                </span>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td style="word-break: initial">{{ __('locale.labels.created_at') }}</td>
                                            <td>
                                                {{ \App\Library\Tool::formatHumanTime($task->created_at) }}
                                            </td>
                                        </tr>
                                        @if ($task->status === 'complete')
                                            <tr>
                                                <td style="word-break: initial">Completed by</td>
                                                <td>
                                                    {!! App\Helpers\Worker::todoCompletedByid($task->completed_by) !!}
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="word-break: initial">{{ __('messages.created_by') }}</td>
                                            <td>
                                                @if (auth()->user()->id == $task->user_id)
                                                    You
                                                @else
                                                    <div class="d-flex justify-content-left align-items-center">
                                                        <div class="avatar  me-1"><img
                                                                src="{{ route('user.avatar', $task->user->uid) }}"
                                                                alt="Avatar" width="32" height="32"></div>
                                                        <div class="d-flex flex-column">
                                                            <span class="emp_name text-truncate fw-bold">
                                                                {{ $task->user->displayName() }}
                                                            </span><small class="emp_post text-truncate text-muted">
                                                                {{ $task->user->email }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="word-break: initial">{{ __('locale.labels.assign_to') }}</td>
                                            <td>
                                                @if (in_array('all', json_decode($task->assign_to)))
                                                    <span class='emp_name text-truncate'>Avalble for
                                                        all</span>
                                                @else
                                                    @foreach (json_decode($task->assign_to) as $id)
                                                        <div class='emp_name text-truncate'>
                                                            {{ \App\Models\User::find($id)->displayName() }}
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="word-break: initial">{{ __('locale.labels.title') }}</td>
                                            <td>{{ $task->title }}</td>
                                        </tr>

                                        <tr style="border: none">
                                            <td style="word-break: initial">
                                                {{ __('locale.labels.description') }}
                                            </td>
                                            <td>{{ $task->description }}</td>
                                        </tr>
                                        <tr style="border: none">
                                            <td style="word-break: initial">
                                                note </td>
                                            <td>{{ $task->note }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!--/ Basic table -->


@endsection


@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>

@endsection
@section('page-script')
    {{-- Page js files --}}

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr['warning']("{{ $error }}",
                    'Warning!', {
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        progressBar: true,
                        newestOnTop: true,
                        rtl: isRtl
                    });
            </script>
        @endforeach
    @endif

    @if (auth()->user()->id === $task->user_id)
        <script>
            'use strict';
        </script>
    @else
        <script>
            $(document).ready(function() {
                "use strict"

                $('button#will_do').on('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __('locale.labels.are_you_sure') }}",
                        text: "Would be able to complete the task?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: "Yes I will",
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-outline-danger ms-1'
                        },
                        buttonsStyling: false,
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('customer.tasks.will_do', $task->uid) }}",
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    showResponseMessage(data);
                                },
                                error: function(reject) {
                                    showResponseError(reject);
                                }
                            })
                        }
                    })

                });

                $('button#send_review').on('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __('locale.labels.are_you_sure') }}",
                        text: "Would be send for review?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: "Yes send",
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-outline-danger ms-1'
                        },
                        buttonsStyling: false,
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('customer.tasks.send_review', $task->uid) }}",
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    showResponseMessage(data);
                                },
                                error: function(reject) {
                                    showResponseError(reject);
                                }
                            })
                        }
                    })

                });

                $('button#pause_task').on('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __('locale.labels.are_you_sure') }}",
                        text: "Would be pause this task?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: "Yes pause",
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-outline-danger ms-1'
                        },
                        buttonsStyling: false,
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('customer.tasks.pause', $task->uid) }}",
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    showResponseMessage(data);
                                },
                                error: function(reject) {
                                    showResponseError(reject);
                                }
                            })
                        }
                    })

                });
                $('button#continue_task').on('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "{{ __('locale.labels.are_you_sure') }}",
                        text: "Would be pause this task?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: "Yes pause",
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-outline-danger ms-1'
                        },
                        buttonsStyling: false,
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('customer.tasks.continueTask', $task->uid) }}",
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    showResponseMessage(data);
                                },
                                error: function(reject) {
                                    showResponseError(reject);
                                }
                            })
                        }
                    })

                });

            });
        </script>
    @endif

@endsection
