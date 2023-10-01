@extends('layouts/contentLayoutMaster')

@section('title', 'Campaings')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')

    <style>
        .customized_select2 .select2-selection--single {
            border-left: 1;
            border-radius: 1 4px 4px 1;
            min-height: 2.75rem !important;
            padding-left: 20px;
        }
    </style>

@endsection

@section('content')

    <!-- Basic Vertical form layout section start -->
    <section id="basic-vertical-layouts campaign_builder">
        <div class="row match-height">
            <div class="col-md-8 col-8 col-ms-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">

                            <form class="form form-vertical" action="{{ route('customer.crm.edit') }}" method="post" >
                                @csrf
                                <div class="row">
                                    

                                    

                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label for="agent" class="form-label fs-4">Call Disposition:</label>
                                            <input type="text" id="agent" class="form-control" value="{{$crmdata->disposition}}" name="disposition" placeholder="Set Appointment">
                                            @error('address')
                                                <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>                                                
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label for="description" class="form-label fs-4">Notes</label>
                                            <textarea class="form-control" name="notes" rows="4" id="message">{{$crmdata->notes}}</textarea>
                                            <small class="text-primary" id="remaining">160 {{ __('locale.labels.characters_remaining') }}</small>
                                            <small class="text-primary pull-right" id="messages">1 {{ __('locale.labels.message') }} (s)</small>
                                            @error('description')
                                            <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    </div>                                    

                                    <div class="col-12">
                                        <input type="hidden" name="uid" value="{{$crmdata->uid}}">                                   
                                        <button type="submit" class="btn btn-primary mr-1 mb-1 pr-1 pl-1" style="float: right"><i data-feather="send"></i> Save
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
    <script src="{{ asset(mix('js/scripts/sms-counter.js')) }}"></script>

    <script>
        $(document).ready(function () {


            // Basic Select2 select
            $(".select2").each(function () {
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

            let $remaining = $('#remaining'),
                $get_msg = $("#message"),
                $messages = $remaining.next(),
                firstInvalid = $('form').find('.is-invalid').eq(0);

            if (firstInvalid.length) {
                $('body, html').stop(true, true).animate({
                    'scrollTop': firstInvalid.offset().top - 200 + 'px'
                }, 200);
            }

            $get_msg.on('change keyup paste', function () {
                let data = SmsCounter.count($(this).val(), true);

                $remaining.text(data.remaining + " {!! __('locale.labels.characters_remaining') !!}");
                $messages.text(data.messages + " {!! __('locale.labels.message') !!}" + '(s)');

            });

        });
    </script>
@endsection
