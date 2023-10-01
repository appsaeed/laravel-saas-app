@extends('layouts/contentLayoutMaster')

@section('title', __('locale.menu.Chat Box'))

@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-chat.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-chat-list.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">

@endsection
<style>
    @media (min-width: 992px) {
        .content-area-wrapper {
            width: calc(100% - 260px);
        }

        body .content-right {
            float: right;
            width: 100% !important;
        }
    }
</style>
@section('content')
    <!-- Main chat area -->
    <section class="chat-app-window">
        <!-- Active Chat -->
        <div class="active-chat">
            <!-- Chat Header -->
            <div class="chat-navbar">
                <header class="chat-header">

                    {!! \App\Helpers\Worker::profileHtmlByid($todo->user_id) !!}
                    <div class="d-flex align-items-center max:sm:d-none">
                        Task: <span class="text-primary mx-1"> {{ $todo->name }}</span>
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
        let chat_id = '{{ $chat_box->uid }}';

        chatHistory.empty();
        chatContainer.animate({
            scrollTop: chatContainer[0].scrollHeight
        }, 0)


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

                    if (sms.send_by === 'from') {
                        details += '<div class="chat chat-left">' +
                            '<div class="chat-avatar">' +
                            '<span class="avatar box-shadow-1 cursor-pointer">' +
                            '<img src="{{ route('customer.getAvatar', $todo->user_id) }}" alt="avatar" height="36" width="36"/>' +
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
                url: "{{ url('/chat') }}" + '/' + chatBoxId + '/reply-to',
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

                console.log(Number(e.data.recipient_id), Number('{{ auth()->user()->id }}'))

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
