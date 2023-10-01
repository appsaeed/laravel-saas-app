@extends('layouts/contentLayoutMaster')

@section('title', __('locale.labels.new_conversion'))


@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection


@section('content')
    <!-- Basic Vertical form layout section start -->
    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="col-md-6 col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('locale.labels.new_conversion') }}</h4>
                        <a href="{{ route('customer.chat.open', $todo->uid) }}"
                            class="text-primary d-block d-md-none">{{ __('locale.menu.Chat Box') }}</a>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                            <form class="form form-vertical" action="{{ route('customer.chat.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12">

                                        <div class="mb-1">
                                            <label for="user_id" class="form-label required">
                                                select a user
                                            </label>
                                            <select class="form-select select2" id="user_id" name="user_id">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->fullname($user->id) }} | {{ $user->email }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('user_id')
                                                <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <input type="hidden" name="todo_id" value="{{ $todo->id }}">
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 float-end">
                                            <i data-feather="send"></i> {{ __('locale.buttons.send') }}
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
@endsection


@section('page-script')

    <script>
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

        let firstInvalid = $('form').find('.is-invalid').eq(0);

        if (firstInvalid.length) {
            $('body, html').stop(true, true).animate({
                'scrollTop': firstInvalid.offset().top - 200 + 'px'
            }, 200);
        }
    </script>
@endsection
