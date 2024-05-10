@extends('layouts/contentLayoutMaster')

@section('title', __('locale.menu.Chat Box'))

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-chat.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-chat-list.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">

@endsection

@section('content-sidebar')
    @include('customer.chatbox._sidebar')
@endsection


@section('content')
    <div class="body-content-overlay"></div>
    <!-- Main chat area -->
    <section class="chat-app-window">
        <!-- To load Conversation -->
        <div class="start-chat-area @if ($box && $box->id) d-none @endif">
            <div class="mb-1 start-chat-icon">
                <i data-feather="message-square"></i>
            </div>

            <h4 class="sidebar-toggle start-chat-text d-block d-md-none">
                {{ __('locale.labels.new_conversion') }}
            </h4>
            <h4 class="sidebar-toggle start-chat-text d-none d-md-block">
                <a href="{{ route('customer.chat.new', $task->uid) }}"
                    class="text-dark">{{ __('locale.labels.new_conversion') }}</a>
            </h4>


            <div class="mb-1 start-chat-icon sidebar-toggle d-block d-lg-none">
                <i data-feather="menu"></i>
            </div>

        </div>
        <!--/ To load Conversation -->

        <!-- Active Chat -->
        <div class="active-chat @if (!$box && !$box->id) d-none @endif">
            <!-- Chat Header -->
            <div class="chat-navbar">
                <header class="chat-header">


                    <div class="d-flex align-items-center">
                        <div class="sidebar-toggle d-block d-lg-none me-1 cursor-pointer">
                            <i data-feather="menu" class="font-medium-5"></i>
                        </div>
                        <div class="avatar avatar-border user-profile-toggle m-0 me-1"
                            style="background-color: transparent"></div>
                    </div>

                    <div class="d-flex align-items-center max:sm:d-none">
                        Task: <span class="text-primary mx-1"> {{ $task->name }}</span>
                    </div>


                    <div class="d-flex align-items-center">

                        {{-- <span class="refresh" data-bs-toggle="tooltip" data-bs-placement="top" title="refresh"> <i
                                data-feather="refresh-ccw" class="cursor-pointer font-medium-2 mx-1 text-primary"></i>
                        </span> --}}

                        <span class="remove-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ __('locale.buttons.delete') }}"><i data-feather="trash"
                                class="cursor-pointer font-medium-2 text-danger"></i></span>

                    </div>
                </header>
            </div>
            <!--/ Chat Header -->

            <!-- User Chat messages -->
            <div class="user-chats">
                <div class="chats">
                    <div class="chat">
                        <div class="chat_history"></div>
                    </div>
                </div>
            </div>
            <!-- User Chat messages -->

            <!-- Submit Chat form -->
            <form class="chat-app-form" action="javascript:void(0);" onsubmit="enter_chat();">
                <div class="input-group input-group-merge me-1 form-send-message">
                    <input id="get_message" type="text" class="form-control message" placeholder="Your message" />
                </div>
                <button type="button" class="btn btn-primary send" onclick="enter_chat();">
                    <i data-feather="send" class="d-lg-none"></i>
                    <span class="d-none d-lg-block">{{ __('locale.buttons.send') }}</span>
                </button>
            </form>
            <!--/ Submit Chat form -->
        </div>
        <!--/ Active Chat -->
    </section>
    <!--/ Main chat area -->
@endsection

@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/pages/chat.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    @if (config('broadcasting.connections.pusher.app_id'))
        <script src="{{ asset(mix('js/scripts/echo.js')) }}"></script>
    @endif



    <script>
        'use strict';
        // autoscroll to bottom of Chat area
        let chatContainer = $(".user-chats"),
            details,
            chatHistory = $(".chat_history");

        @if ($box && $box->id)

            chatHistory.empty();
            chatContainer.animate({
                scrollTop: chatContainer[0].scrollHeight
            }, 0)

            var chat_id = "{{ $box->uid }}";
            var avatar = "{{ route('customer.getAvatar', $box->to) }}";
            var email = "{{ \App\Models\User::getEmail($box->to) }}";
            var name = "{{ \App\Models\User::fullname($box->to) }}";


            let profile =
                '<img alt=Avatar height=32 src="' + avatar + '" width=32>' +
                '<span class="mx-1 text-truncate emp_name fw-bold"> ' + name + ' </span>';

            $('.user-profile-toggle').html(profile)


            $.ajax({
                url: "{{ url('/chat') }}" + '/' + chat_id + '/messages',
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    details = '<input type="hidden" value="' + chat_id +
                        '" name="chat_id" class="chat_id">';

                    let cwData = JSON.parse(response.data);

                    $.each(cwData, function(i, sms) {
                        let media_url = '';
                        if (sms.media_url !== null) {
                            media_url = '<p><img src="' + sms.media_url + '"/></p>';
                        }

                        let message = '';
                        if (sms.message !== null) {
                            message = '<p>' + sms.message + '</p>';
                        }

                        if (sms.send_by === 'to') {
                            details += '<div class="chat chat-left">' +
                                '<div class="chat-avatar">' +
                                '<span class="avatar box-shadow-1 cursor-pointer">' +
                                '<img src="' + avatar +
                                '" alt="avatar" height="36" width="36"/>' +
                                '</span>' +
                                '</div>' +
                                '<div class="chat-body">' +
                                '<div class="chat-content">' +
                                media_url +
                                message +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        } else {
                            details +=
                                '<div class="chat"><div class="chat-body me-0"><div class="chat-content"><p>' +
                                message + '</p></div></div></div>';
                        }
                    });

                    chatHistory.append(details);
                    chatContainer.animate({
                        scrollTop: chatContainer[0].scrollHeight
                    }, 400)
                }
            });
        @endif

        $(".chat-users-list li").on("click", function() {

            chatHistory.empty();
            chatContainer.animate({
                scrollTop: chatContainer[0].scrollHeight
            }, 0)

            var chat_id = $(this).data('id');
            var avatar = $(this).data('avatar');
            var email = $(this).data('email');
            var name = $(this).data('name');
            var url = $(this).data('url');

            window.history.pushState({
                chat_id,
                avatar,
                email,
                name
            }, null, url)




            let profile =
                '<img alt=Avatar height=32 src="' + avatar + '" width=32>' +
                '<span class="mx-1 text-truncate emp_name fw-bold"> ' + name + '</span>';

            $('.user-profile-toggle').html(profile)



            $.ajax({
                url: "{{ url('/chat') }}" + '/' + chat_id + '/messages',
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    details = '<input type="hidden" value="' + chat_id +
                        '" name="chat_id" class="chat_id">';

                    let cwData = JSON.parse(response.data);

                    $.each(cwData, function(i, sms) {
                        let media_url = '';
                        if (sms.media_url !== null) {
                            media_url = '<p><img src="' + sms.media_url + '"/></p>';
                        }

                        let message = '';
                        if (sms.message !== null) {
                            message = '<p>' + sms.message + '</p>';
                        }

                        if (sms.send_by === 'to') {
                            details += '<div class="chat chat-left">' +
                                '<div class="chat-avatar">' +
                                '<span class="avatar box-shadow-1 cursor-pointer">' +
                                '<img src="' + avatar +
                                '" alt="avatar" height="36" width="36"/>' +
                                '</span>' +
                                '</div>' +
                                '<div class="chat-body">' +
                                '<div class="chat-content">' +
                                media_url +
                                message +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        } else {
                            details +=
                                '<div class="chat"><div class="chat-body me-0"><div class="chat-content"><p>' +
                                message + '</p></div></div></div>';
                        }
                    });

                    chatHistory.append(details);
                    chatContainer.animate({
                        scrollTop: chatContainer[0].scrollHeight
                    }, 400)
                }
            });
        });


        window.addEventListener('popstate', function(data) {

            if (!data.state) {
                $('.start-chat-area').removeClass('d-none');
                $('.start-chat').addClass('d-none');
                return false;
            }

            chatHistory.empty();
            chatContainer.animate({
                scrollTop: chatContainer[0].scrollHeight
            }, 0)

            var chat_id = data.state.chat_id;
            var avatar = data.state.avatar;
            var email = data.state.email;
            var name = data.state.name;

            let profile =
                '<img alt=Avatar height=32 src="' + avatar + '" width=32>' +
                '<span class="mx-1 text-truncate emp_name fw-bold"> ' + name + '</span>';

            $('.user-profile-toggle').html(profile)

            $('ul.chat-users-list li').removeClass('active');
            $('li[data-id=' + chat_id + ']').addClass('active');

            $.ajax({
                url: "{{ url('/chat') }}" + '/' + chat_id + '/messages',
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    details = '<input type="hidden" value="' + chat_id +
                        '" name="chat_id" class="chat_id">';

                    let cwData = JSON.parse(response.data);

                    $.each(cwData, function(i, sms) {
                        let media_url = '';
                        if (sms.media_url !== null) {
                            media_url = '<p><img src="' + sms.media_url + '"/></p>';
                        }

                        let message = '';
                        if (sms.message !== null) {
                            message = '<p>' + sms.message + '</p>';
                        }

                        if (sms.send_by === 'to') {
                            details += '<div class="chat chat-left">' +
                                '<div class="chat-avatar">' +
                                '<span class="avatar box-shadow-1 cursor-pointer">' +
                                '<img src="' + avatar +
                                '" alt="avatar" height="36" width="36"/>' +
                                '</span>' +
                                '</div>' +
                                '<div class="chat-body">' +
                                '<div class="chat-content">' +
                                media_url +
                                message +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        } else {
                            details +=
                                '<div class="chat"><div class="chat-body me-0"><div class="chat-content"><p>' +
                                message + '</p></div></div></div>';
                        }
                    });

                    chatHistory.append(details);
                    chatContainer.animate({
                        scrollTop: chatContainer[0].scrollHeight
                    }, 400)
                }
            });
        });

        // Add message to chat
        function enter_chat(source) {
            let message = $(".message"),
                chatBoxId = $(".chat_id").val(),
                messageValue = message.val();

            let html =
                '<div class="chat"><div class="chat-body me-0"><div class="chat-content"><p>' +
                messageValue + '</p></div></div></div>';
            chatHistory.append(html);
            message.val("");
            $(".user-chats").scrollTop($(".user-chats > .chats").height());

            $.ajax({
                url: "{{ url('/chat') }}" + '/' + chatBoxId + '/reply',
                type: "POST",
                data: {
                    message: messageValue,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {

                    if (response != 'sent') {

                        toastr['warning'](response.message, "{{ __('locale.labels.attention') }}", {
                            closeButton: true,
                            positionClass: 'toast-top-right',
                            progressBar: true,
                            newestOnTop: true,
                            rtl: isRtl
                        });
                    }
                },
                error: function(reject) {
                    showResponseError(reject);
                }
            });


        }


        $(".remove-btn").on('click', function(event) {
            event.preventDefault();
            let sms_id = $(".chat_id").val();

            Swal.fire({
                title: "{{ __('locale.labels.are_you_sure') }}",
                text: "{{ __('locale.labels.able_to_revert') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('locale.labels.delete_it') }}",
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ms-1'
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ url('/chat') }}" + '/' + sms_id + '/delete',
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {

                            var url = $(this).data('url');

                            window.history.pushState(null, null,
                                "{{ route('customer.chat.open', $task->uid) }}")

                            showResponseMessage(response)
                        },
                        error: function(reject) {
                            showResponseError(reject)
                        }
                    });
                }
            })

        })

        @if (config('broadcasting.connections.pusher.app_id'))

            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: "{{ config('broadcasting.connections.pusher.key') }}",
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                encrypted: true,
                authEndpoint: "{{ url('/') }}/broadcasting/auth"
            });

            //  Pusher.logToConsole = true;
            Echo.private('chat').listen('MessageReceived', (e) => {

                Number(e.data.recipient_id) == Number('{{ auth()->user()->id }}')

                if (Number(e.data.recipient_id) == Number('{{ auth()->user()->id }}')) {
                    const image_url = "{{ url('/') }}/get-avatar/" + e.user.uid;
                    let details = '<div class="chat chat-left">' +
                        '<div class="chat-avatar">' +
                        '<span class="avatar box-shadow-1 cursor-pointer">' +
                        '<img src="' + image_url +
                        '" alt="avatar" height="36" width="36"/>' +
                        '</span>' +
                        '</div>' +
                        '<div class="chat-body">' +
                        '<div class="chat-content">' +
                        e.message +
                        '</div>' +
                        '</div>' +
                        '</div>';

                    chatHistory.append(details);
                    chatContainer.animate({
                        scrollTop: chatContainer[0].scrollHeight
                    }, 400)

                }

            })
        @endif
    </script>
@endsection
