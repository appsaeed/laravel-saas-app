@extends('layouts/contentLayoutMaster')

@section('title', 'Campaings')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')

    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">

    <style>
        .customized_select2 .select2-selection--multiple {
            border-left: 0;
            border-radius: 0 4px 4px 0;
            min-height: 2.75rem !important;
        }

        .customized_select2 .select2-selection--single {
            border-left: 0;
            border-radius: 0 4px 4px 0;
            min-height: 2.75rem !important;
        }
    </style>

@endsection

@section('content')

    <!-- Basic Vertical form layout section start -->
    <section id="basic-vertical-layouts campaign_builder">
        <div class="row match-height">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">

                            <form class="form form-vertical" action="{{route('customer.campaigns._message')}}" method="post">
                                @csrf

                                <div class="row">

                                    <div class="col-12 mt-1 mb-1">
                                        <h3 class="title">
                                            Setup message for Voicebox: {{$messagebox->voicebox}}
                                        </h3>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">{{__('locale.permission.sms_template')}}</label>
                                            <select class="form-select select2" id="sms_template">
                                                <option>{{ __('locale.labels.select_one') }}</option>
                                                @foreach($templates as $template)
                                                    <option value="{{$template->id}}">{{ $template->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    {{-- select shortcode --}}

                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">{{__('locale.labels.available_tag')}}</label>
                                            <select class="form-select select2" id="available_tag">
                                                <option value="phone">{{ __('locale.labels.phone') }}</option>
                                                <option value="first_name">{{ __('locale.labels.first_name') }}</option>
                                                <option value="last_name">{{ __('locale.labels.last_name') }}</option>
                                                <option value="email">{{ __('locale.labels.email') }}</option>
                                                <option value="username">{{ __('locale.labels.username') }}</option>
                                                <option value="company">{{ __('locale.labels.company') }}</option>
                                                <option value="address">{{ __('locale.labels.address') }}</option>
                                                <option value="birth_date">{{ __('locale.labels.birth_date') }}</option>
                                                <option value="anniversary_date">{{ __('locale.labels.anniversary_date') }}</option>

                                                @if($template_tags)
                                                    @foreach($template_tags as $field)
                                                        <option value="{{$field->tag}}">{{ $field->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label for="message" class="form-label">{{__('locale.labels.message')}}</label>
                                            <textarea class="form-control message_1" name="message1" rows="5" id="message" data-index="1">{{$messagebox->message1}}</textarea>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-primary text-uppercase text-start remaining_1" id="remaining">160 {{ __('locale.labels.characters_remaining') }}</small>
                                                <small class="text-primary text-uppercase text-end" id="messages">1 {{ __('locale.labels.message') }} (s)</small>
                                            </div>
                                            @error('message1')
                                            <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                        <div class="mb-1">
                                            <div class="btn-group btn-group-sm message_box" role="group">
                                                <input type="radio" class="btn-check"  value="," id="comma" autocomplete="off" checked/>
                                                <label class="btn btn-outline-primary" for="comma">, ({{ __('locale.labels.comma') }})</label>
    
                                                <input type="radio" class="btn-check"  value=";" id="semicolon" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="semicolon">; ({{ __('locale.labels.semicolon') }})</label>
    
                                                <input type="radio" class="btn-check"  value="|" id="bar" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="bar">| ({{ __('locale.labels.bar') }})</label>
    
                                                <input type="radio" class="btn-check"  value="tab" id="tab" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="tab">{{ __('locale.labels.tab') }}</label>
    
                                                <input type="radio" class="btn-check"  value="new_line" id="new_line" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="new_line">{{ __('locale.labels.new_line') }}</label>
    
                                            </div>
    
                                            @error('delimiter')
                                            <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                {{-- input field end --}}
                                

                                {{-- <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mt-1 mb-1 float-left" style="float: right"><i data-feather="send"></i> {{ __('locale.buttons.send') }}
                                        </button>
                                    </div>
                                </div> --}}

                                {{-- end message 1 --}}

                                {{-- start message 2 --}}
                                <div class="row">

                                    <div class="col-12 mt-1 mb-1">
                                        <h3 class="title">Message 2 sent 24hrs after Call</h3>
                                    </div>

                                    {{-- select tempage --}}

                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">{{__('locale.permission.sms_template')}}</label>
                                            <select class="form-select select2" id="sms_template">
                                                <option>{{ __('locale.labels.select_one') }}</option>
                                                @foreach($templates as $template)
                                                    <option value="{{$template->id}}">{{ $template->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    {{-- select shortcode --}}

                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">{{__('locale.labels.available_tag')}}</label>
                                            <select class="form-select select2" id="available_tag">
                                                <option value="phone">{{ __('locale.labels.phone') }}</option>
                                                <option value="first_name">{{ __('locale.labels.first_name') }}</option>
                                                <option value="last_name">{{ __('locale.labels.last_name') }}</option>
                                                <option value="email">{{ __('locale.labels.email') }}</option>
                                                <option value="username">{{ __('locale.labels.username') }}</option>
                                                <option value="company">{{ __('locale.labels.company') }}</option>
                                                <option value="address">{{ __('locale.labels.address') }}</option>
                                                <option value="birth_date">{{ __('locale.labels.birth_date') }}</option>
                                                <option value="anniversary_date">{{ __('locale.labels.anniversary_date') }}</option>

                                                @if($template_tags)
                                                    @foreach($template_tags as $field)
                                                        <option value="{{$field->tag}}">{{ $field->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label for="message2" class="form-label">{{__('locale.labels.message')}}</label>
                                            <textarea class="form-control message_2" name="message2" rows="5" id="message" data-index="2">{{$messagebox->message2}}</textarea>
                                            <div class="d-flex justify-content-between ">
                                                <small class="text-primary text-uppercase text-start remaining_2" id="remaining">160 {{ __('locale.labels.characters_remaining') }}</small>
                                                <small class="text-primary text-uppercase text-end " id="messages">1 {{ __('locale.labels.message') }} (s)</small>
                                            </div>
                                            @error('message2')
                                            <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    </div>

                                    

                                    <div class="col-12">
                                        <div class="mb-1">
                                            <div class="btn-group btn-group-sm message_box" role="group">
                                                <input type="radio" class="btn-check"  value="," id="comma" autocomplete="off" checked/>
                                                <label class="btn btn-outline-primary" for="comma">, ({{ __('locale.labels.comma') }})</label>

                                                <input type="radio" class="btn-check"  value=";" id="semicolon" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="semicolon">; ({{ __('locale.labels.semicolon') }})</label>

                                                <input type="radio" class="btn-check"  value="|" id="bar" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="bar">| ({{ __('locale.labels.bar') }})</label>

                                                <input type="radio" class="btn-check"  value="tab" id="tab" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="tab">{{ __('locale.labels.tab') }}</label>

                                                <input type="radio" class="btn-check"  value="new_line" id="new_line" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="new_line">{{ __('locale.labels.new_line') }}</label>

                                            </div>

                                            @error('delimiter')
                                            <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>

                                    </div>

                                </div>

                                {{-- <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mt-1 mb-1 float-left" style="float: right"><i data-feather="send"></i> {{ __('locale.buttons.send') }}
                                        </button>
                                    </div>
                                </div> --}}
                                {{-- end message 2 --}}

                                {{-- start message 3 --}}
                                <div class="row">

                                    <div class="col-12 mt-1 mb-1">
                                        <h3 class="title">Message 3 sent 48hrs after Call</h3>
                                    </div>

                                    {{-- select tempage --}}

                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">{{__('locale.permission.sms_template')}}</label>
                                            <select class="form-select select2" id="sms_template">
                                                <option>{{ __('locale.labels.select_one') }}</option>
                                                @foreach($templates as $template)
                                                    <option value="{{$template->id}}">{{ $template->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    {{-- select shortcode --}}

                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label">{{__('locale.labels.available_tag')}}</label>
                                            <select class="form-select select2" id="available_tag">
                                                <option value="phone">{{ __('locale.labels.phone') }}</option>
                                                <option value="first_name">{{ __('locale.labels.first_name') }}</option>
                                                <option value="last_name">{{ __('locale.labels.last_name') }}</option>
                                                <option value="email">{{ __('locale.labels.email') }}</option>
                                                <option value="username">{{ __('locale.labels.username') }}</option>
                                                <option value="company">{{ __('locale.labels.company') }}</option>
                                                <option value="address">{{ __('locale.labels.address') }}</option>
                                                <option value="birth_date">{{ __('locale.labels.birth_date') }}</option>
                                                <option value="anniversary_date">{{ __('locale.labels.anniversary_date') }}</option>

                                                @if($template_tags)
                                                    @foreach($template_tags as $field)
                                                        <option value="{{$field->tag}}">{{ $field->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label for="message" class="form-label">{{__('locale.labels.message')}}</label>
                                            <textarea class="form-control message_3" name="message3" rows="5" id="message" data-index="3">{{$messagebox->message3}}</textarea>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-primary text-uppercase text-start remaining_3" id="remaining">160 {{ __('locale.labels.characters_remaining') }}</small>
                                                <small class="text-primary text-uppercase text-end" id="messages">1 {{ __('locale.labels.message') }} (s)</small>
                                            </div>
                                            @error('message3')
                                            <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-1">
                                            <div class="btn-group btn-group-sm message_box" role="group">
                                                <input type="radio" class="btn-check"  value="," id="comma" autocomplete="off" checked/>
                                                <label class="btn btn-outline-primary" for="comma">, ({{ __('locale.labels.comma') }})</label>

                                                <input type="radio" class="btn-check"  value=";" id="semicolon" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="semicolon">; ({{ __('locale.labels.semicolon') }})</label>

                                                <input type="radio" class="btn-check"  value="|" id="bar" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="bar">| ({{ __('locale.labels.bar') }})</label>

                                                <input type="radio" class="btn-check"  value="tab" id="tab" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="tab">{{ __('locale.labels.tab') }}</label>

                                                <input type="radio" class="btn-check"  value="new_line" id="new_line" autocomplete="off"/>
                                                <label class="btn btn-outline-primary" for="new_line">{{ __('locale.labels.new_line') }}</label>

                                            </div>

                                            @error('delimiter')
                                            <p><small class="text-danger">{{ $message }}</small></p>
                                            @enderror
                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <input type="hidden" name="uid" value="{{$messagebox->uid}}">
                                        <button type="submit" class="btn btn-primary mt-1 mb-1 float-left" style="float: right"><i data-feather="send"></i> {{ __('locale.buttons.send') }}
                                        </button>
                                    </div>
                                </div>
                                {{-- end message 3 --}}
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
    <script src="{{ asset(mix('js/scripts/dom-rules.js')) }}"></script>
@endsection

@section('page-script')

    <script src="{{ asset(mix('js/scripts/sms-counter.js')) }}"></script>

    <script>
        window.index = 1;
        $(document).ready(function () {

            $('textarea').on('focus', function(){
                window.index = $(this).data('index');
            });

            $('.schedule_date').flatpickr({
                minDate: "today",
                dateFormat: "Y-m-d",
                defaultDate: "{{ date('Y-m-d') }}",
            });

            $('.flatpickr-time').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                defaultDate: "{{ date('H:i') }}",
            });

            $(".sender_id").on("click", function () {
                $("#sender_id").prop("disabled", !this.checked);
                $("#phone_number").prop("disabled", this.checked);
            });

            $(".phone_number").on("click", function () {
                $("#phone_number").prop("disabled", !this.checked);
                $("#sender_id").prop("disabled", this.checked);
            });


            let schedule = $('.schedule'),
                scheduleTime = $(".schedule_time");

            if (schedule.prop('checked') === true) {
                scheduleTime.show();
            } else {
                scheduleTime.hide();
            }

            $('.advanced_div').hide();

            schedule.change(function () {
                scheduleTime.fadeToggle();
            });

            $('.advanced').change(function () {
                $('.advanced_div').fadeToggle();
            });

            $.createDomRules({

                parentSelector: 'body',
                scopeSelector: 'form',
                showTargets: function (rule, $controller, condition, $targets, $scope) {
                    $targets.fadeIn();
                },
                hideTargets: function (rule, $controller, condition, $targets, $scope) {
                    $targets.fadeOut();
                },

                rules: [
                    {
                        controller: '#frequency_cycle',
                        value: 'custom',
                        condition: '==',
                        targets: '.show-custom',
                    },
                    {
                        controller: '#frequency_cycle',
                        value: 'onetime',
                        condition: '!=',
                        targets: '.show-recurring',
                    },
                    {
                        controller: '.message_type',
                        value: 'mms',
                        condition: '==',
                        targets: '.send-mms',
                    }
                ]
            });


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

            let number_of_message_box_ajax = 0,
                number_of_message_box_manual = 0,
                $get_message_box = $('#message_box'),
                $remaining = $('.remaining_'+window.index),
                $messages = $remaining.next(),
                $get_msg = $("#message"),
                firstInvalid = $('form').find('.is-invalid').eq(0);

            if (firstInvalid.length) {
                $('body, html').stop(true, true).animate({
                    'scrollTop': firstInvalid.offset().top - 200 + 'px'
                }, 200);
            }

            function get_delimiter() {
                return $('input[name=delimiter]:checked').val();
            }

            function get_message_box_count() {

                let message_box_value = $get_message_box[0].value.trim();

                if (message_box_value) {
                    let delimiter = get_delimiter();

                    if (delimiter === ';') {
                        number_of_message_box_manual = message_box_value.split(';').length;
                    } else if (delimiter === ',') {
                        number_of_message_box_manual = message_box_value.split(',').length;
                    } else if (delimiter === '|') {
                        number_of_message_box_manual = message_box_value.split('|').length;
                    } else if (delimiter === 'tab') {
                        number_of_message_box_manual = message_box_value.split(' ').length;
                    } else if (delimiter === 'new_line') {
                        number_of_message_box_manual = message_box_value.split('\n').length;
                    } else {
                        number_of_message_box_manual = 0;
                    }
                } else {
                    number_of_message_box_manual = 0;
                }
                let total = number_of_message_box_manual + Number(number_of_message_box_ajax);

                $('.number_of_message_box').text(total);
            }

            $get_message_box.keyup(get_message_box_count);


            $("input[name='delimiter']").change(function () {
                get_message_box_count();
            });


            function get_character() {
                
                if ($(this).val() !== null) {

                    let data = SmsCounter.count($(this).val(), true);

                    if (data.encoding === 'UTF16') {
                        $('#sms_type').val('unicode').trigger('change');
                    } else {
                        $('#sms_type').val('plain').trigger('change');
                    }

                    $('.remaining_'+window.index).text(data.remaining + " {!! __('locale.labels.characters_remaining') !!}");
                    $('.remaining_'+window.index).next().text(data.messages + " {!! __('locale.labels.message') !!}" + '(s)');

                }

            }


            $('select#available_tag').on('change', function () {
                
                const caretPos = $('.message_'+window.index).selectionStart;
                const textAreaTxt = $('.message_'+window.index).val();
                let txtToAdd = $(this).val();
                if (txtToAdd) {
                    txtToAdd = '{' + txtToAdd + '}';
                }

                $('.message_'+window.index).val(textAreaTxt.substring(0, caretPos) + txtToAdd );
            });            


            $("select#sms_template").on('change', function () {

                let template_id = $(this).val();

                $.ajax({
                    url: "{{ url('templates/show-data')}}" + '/' + template_id,
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}"
                    },
                    cache: false,
                    success: function (data) {
                        if (data.status === 'success') {
                            
                            const caretPos = $('.message_'+window.index).selectionStart;
                            const textAreaTxt = $('.message_'+window.index).val();
                            let txtToAdd = data.message;

                            $('.message_'+window.index).val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos)).val().length;

                            get_character();

                        } else {
                            toastr['warning'](data.message, "{{ __('locale.labels.attention') }}", {
                                closeButton: true,
                                positionClass: 'toast-top-right',
                                progressBar: true,
                                newestOnTop: true,
                                rtl: isRtl
                            });
                        }
                    },
                    error: function (reject) {
                        if (reject.status === 422) {
                            let errors = reject.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                toastr['warning'](value[0], "{{__('locale.labels.attention')}}", {
                                    closeButton: true,
                                    positionClass: 'toast-top-right',
                                    progressBar: true,
                                    newestOnTop: true,
                                    rtl: isRtl
                                });
                            });
                        } else {
                            toastr['warning'](reject.responseJSON.message, "{{__('locale.labels.attention')}}", {
                                closeButton: true,
                                positionClass: 'toast-top-right',
                                progressBar: true,
                                newestOnTop: true,
                                rtl: isRtl
                            });
                        }
                    }
                });
            });

            $("textarea#message").keyup(get_character);

        });
    </script>
@endsection

