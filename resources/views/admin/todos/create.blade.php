@extends('layouts/contentLayoutMaster')

@section('title', __('messages.Add new todo'))

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">

    @if (config('custom.theme_skin') === 'dark')
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.dark.css')) }}">
    @else
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    @endif

@endsection

@section('content')
    <!-- Basic Vertical form layout section start -->
    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="col-md-6 col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ __('messages.Add new todo') }} </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" action="{{ route('customer.todos.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label for="name"
                                                class="required form-label">{{ __('locale.labels.name') }}</label>
                                            <input type="text" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" name="name" placeholder="Todo name" required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label for="title" class="form-label">{{ __('locale.labels.title') }}</label>
                                            <input type="text" id="title"
                                                class="form-control @error('title') is-invalid @enderror"
                                                value="{{ old('title') }}" name="title" placeholder="Todo title">
                                            @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- assign users --}}
                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label for="assign_to" class="form-label required">
                                                {{ __('messages.Assign to') }}
                                            </label>
                                            <select class="select2 select2-icons form-select select2-hidden-accessible"
                                                id="assign_to" name="assign_to[]" multiple="" data-select2-id="assign_to"
                                                required>
                                                <option value="all">
                                                    Available for all
                                                </option>
                                                @foreach ($customers as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->displayName() }} |
                                                        {{ $user->email }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('assign_to')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    {{-- assign users --}}


                                    {{-- set status --}}
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label for="status" class="form-label">
                                                {{ __('locale.labels.status') }}</label>
                                            <select class="select2 w-100" id="timezone" name="status">
                                                @foreach (\App\Models\Todos::$status as $status)
                                                    <option value="{{ $status }}"
                                                        {{ $status == 'available' ? 'selected' : null }}>
                                                        {{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('status')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    {{-- set status --}}

                                    {{-- set deadline --}}
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label for="deadline" class=" form-label">
                                                {{ __('messages.deadline') }}</label>
                                            <input type="datetime" name="deadline" id="deadline" class="form-control ">
                                        </div>
                                        @error('status')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    {{-- set deadline --}}

                                    {{-- description --}}
                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label for="description" class="form-label">
                                                {{ __('locale.labels.description') }}
                                            </label>
                                            <textarea type="text" id="description"
                                                class="form-control @error('description') is-invalid @enderror"name="description" placeholder="Todo description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- description --}}


                                    <div class="col-12">
                                        <div class="mb-1">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="send_email"
                                                    value="checked" name="send_email">
                                                <label class="form-check-label"
                                                    for="send_email">{{ __('Send email') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1">
                                            <i data-feather="save"></i> {{ __('locale.buttons.save') }}
                                        </button>
                                    </div>


                                </div>
                            </form>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
    <!-- // Basic Vertical form layout section end -->

@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection


@section('page-script')

    <script>
        //exmample url: https://flatpickr.js.org/examples/
        $('#deadline').flatpickr({
            // weekNumbers: true,
            defaultDate: new Date(),
            locale: '{{ app()->getLocale() }}',
            enableTime: true,
            // dateFormat: "YYYY-MM-DD HH:MM:SS",
            minDate: "today",
            // maxDate: new Date().fp_incr(14) // 14 days from now
        });

        $('select#assign_to').on('change', function() {
            // alert($(this).val())
            var assign_to = $.trim($(this).val());
            if (assign_to.split(',').includes('all')) {
                $(this).val('all');
                $(this).children().each(function() {
                    $(this).attr('disabled', true)
                });
                $(this).children('[value="all"]').attr('disabled', false);
            } else {
                $(this).children().each(function() {
                    $(this).attr('disabled', false)
                });
            }
        })

        let firstInvalid = $('form').find('.is-invalid').eq(0);

        if (firstInvalid.length) {
            $('body, html').stop(true, true).animate({
                'scrollTop': firstInvalid.offset().top - 200 + 'px'
            }, 200);
        }

        // Basic Select2 select
        $(".select2").each(function() {
            let $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                // the following code is used to disable x-scrollbar when click in select input and
                // take 100% width in responsive also
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $this.parent()
            });
        });
    </script>
@endsection
