@extends('layouts/contentLayoutMaster')

@section('title', __('update the task'))

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
    <div class="mb-3 mt-2">
        <div class="btn-group">
            <a href="{{ route('customer.todos.all') }}" class="btn btn-primary fw-bold me-1" type="button">
                {{ __('locale.buttons.back') }} </a>
        </div>
        <div class="btn-group">
            <a href="{{ route('customer.todos.show', $todo->uid) }}" class="btn btn-success fw-bold me-1" type="button">
                open </a>
        </div>
    </div>
    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="col-md-6 col-12">

                <div class="card" style="margin-bottom: 300px">
                    <div class="card-header">
                        <h4 class="card-title"> {{ __('Update todo') }} </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" action="{{ route('customer.todos.update', $todo->uid) }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                @if (auth()->user()->id === $todo->user_id)
                                    @include('customer.todos.edit.creator')
                                @else
                                    {{-- update for recevier --}}
                                    @include('customer.todos.edit.receiver')
                                @endif
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
        $('input#deadline').flatpickr({
            weekNumbers: true,
            defaultDate: new Date('{{ $todo->deadline }}'),
            // defaultDate: Date.now(),
            enableTime: true,
            // dateFormat: "Y-m-d h:m",
            minDate: 'today',
            minTime: Date.now(),
            // maxDate: new Date().fp_incr(366 * 3) // 14 days from now
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
