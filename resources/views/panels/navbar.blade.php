@if ($configData['mainLayoutType'] == 'horizontal' && isset($configData['mainLayoutType']))
    <nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center {{ $configData['navbarColor'] }}"
        data-nav="brand-center">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav">
                @if (Auth::user()->active_portal == 'customer' && Auth::user()->is_customer == 1)
                    <li class="nav-item"><a class="navbar-brand" href="{{ route('user.home') }}">
                            <span class="brand-logo"><img src="{{ asset(config('app.logo')) }}" alt="app logo" /></span>
                        </a>
                    </li>
                @else
                    <li class="nav-item"><a class="navbar-brand" href="{{ route('admin.home') }}">
                            <span class="brand-logo"><img src="{{ asset(config('app.logo')) }}" alt="app logo" /></span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    @else
        <nav
            class="header-navbar navbar navbar-expand-lg align-items-center {{ $configData['navbarClass'] }} navbar-light navbar-shadow {{ $configData['navbarColor'] }} {{ $configData['layoutWidth'] === 'boxed' && $configData['verticalMenuNavbarType'] === 'navbar-floating' ? 'container-xxl' : '' }}">
@endif


<div class="navbar-container d-flex content">
    <div class="bookmark-wrapper d-flex align-items-center">
        <ul class="nav navbar-nav d-xl-none">
            <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                        data-feather="menu"></i></a></li>
        </ul>
    </div>


    <ul class="nav navbar-nav align-items-center ms-auto">
        {{-- Language Dropdown --}}
        @if (config('custom.menu.language_menu'))
            <li class="nav-item dropdown dropdown-language">
                <a class="nav-link dropdown-toggle" id="dropdown-flag" href="#" data-bs-toggle="dropdown"
                    aria-haspopup="true">
                    <i class="flag-icon flag-icon-us"></i>
                    <span class="selected-language">English</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-flag">
                    @foreach (\App\Helpers\Helper::languages() as $lang)
                        <a class="dropdown-item" href="{{ url('lang/' . $lang['code']) }}"
                            data-language="{{ $lang['code'] }}">
                            <i class="flag-icon flag-icon-{{ $lang['iso_code'] }}"></i> {{ $lang['name'] }}
                        </a>
                    @endforeach

                </div>
            </li>
        @endif

        {{-- Dark and light option. It will be theme manager option --}}
        @if (config('custom.menu.theme_switch'))
            <li class="nav-item d-none d-lg-block d-md-block">
                <a class="nav-link nav-link-style">
                    <i class="ficon" data-feather="{{ $configData['theme'] === 'dark' ? 'sun' : 'moon' }}"></i>
                </a>
            </li>
        @endif

        {{-- Notification dropdown --}}
        @if (config('custom.menu.notifye_menu'))
            <li class="nav-item dropdown dropdown-notification mark-open me-25">
                <a class="nav-link" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ficon" data-feather="bell"></i>
                    @php
                        $count = \App\Models\Notifications::where('user_id', Auth::user()->id)
                            ->where('mark_open', 0)
                            ->count();
                    @endphp
                    @if ($count)
                        <script>
                            window.addEventListener("DOMContentLoaded", function() {
                                $('li.mark-open').on('click', function() {
                                    $('span.notify-count').remove();
                                    $.post('{{ route('user.account.notifications.mark_open') }}', {
                                        _token: '{{ csrf_token() }}'
                                    });
                                })
                            })
                        </script>
                        <span class="notify-count badge rounded-pill bg-danger badge-up">{{ $count }}</span>
                    @endif

                </a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 me-auto">{{ __('locale.labels.notifications') }}</h4>
                            <div class="badge rounded-pill badge-light-primary">{{ $count }}
                                {{ __('locale.labels.new') }}</div>
                        </div>
                    </li>
                    <li class="scrollable-container media-list">

                        @foreach (\App\Models\Notifications::where('user_id', Auth::user()->id)->where('mark_read', 0)->latest()->take('10')->cursor() as $value)
                            <a class="d-flex" href="{{ route('user.account', ['tab' => 'notification']) }}">
                                <div class="list-item d-flex align-items-start">
                                    @switch($value->type)
                                        @case('user')
                                            <div class="me-1">
                                                <div class="avatar bg-light-primary">
                                                    <div class="avatar-content"><i class="avatar-icon" data-feather="user"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span
                                                        class="fw-bolder">{{ __('locale.labels.you_have_new_user') }}</p>
                                                <small class="notification-text"> {{ str_limit($value->message, 30) }}</small>
                                            </div>
                                        @break

                                        @case('task')
                                            <div class="me-1">
                                                <div class="avatar bg-light-success">
                                                    <div class="avatar-content"><i class="avatar-icon"
                                                            data-feather="file-text"></i></div>
                                                </div>
                                            </div>

                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span
                                                        class="fw-bolder">{{ str_limit($value->name, 30) }}</span></p>
                                                </p>
                                                <small class="notification-text"> {{ str_limit($value->message, 30) }}</small>
                                            </div>
                                        @break

                                        @case('todo')
                                            <div class="me-1">
                                                <div class="avatar bg-light-success">
                                                    <div class="avatar-content"><i class="avatar-icon"
                                                            data-feather="file-text"></i></div>
                                                </div>
                                            </div>

                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span
                                                        class="fw-bolder">{{ str_limit($value->name, 30) }}</span></p>
                                                </p>
                                                <small class="notification-text"> {{ str_limit($value->message, 30) }}</small>
                                            </div>
                                        @break

                                        @case('chatbox')
                                            <div class="me-1">
                                                <div class="avatar bg-light-danger">
                                                    <div class="avatar-content"><i class="avatar-icon"
                                                            data-feather="message-square"></i></div>
                                                </div>
                                            </div>

                                            <div class="list-item-body flex-grow-1">
                                                <p class="media-heading"><span class="fw-bolder">New Inbox Message</span></p>
                                                <small class="notification-text"> {{ str_limit($value->message, 30) }}</small>
                                            </div>
                                        @break
                                    @endswitch
                                    <small>
                                        <time
                                            class="media-meta">{{ \App\Library\Tool::formatHumanTime($value->created_at) }}</time>
                                    </small>

                                </div>
                            </a>
                        @endforeach
                    </li>
                    <li class="dropdown-menu-footer">
                        <a class="btn btn-primary w-100"
                            href="{{ route('user.account', ['tab' => 'notification']) }}">{{ __('locale.labels.read_all_notifications') }}</a>
                    </li>
                </ul>
            </li>
        @endif


        @if (config('custom.menu.profile_menu'))
            <li class="dropdown dropdown-user nav-item">
                <a class="dropdown-toggle nav-link dropdown-user-link" id="dropdown-user" href="javascript:void(0);"
                    data-bs-toggle="dropdown" aria-haspopup="true">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name fw-bolder">
                            @if (Auth::check())
                                {{ Auth::user()->displayName() }}
                            @else
                                {{ config('app.name') }}
                            @endif
                        </span>
                        <span class="user-status">{{ __('locale.labels.available') }}</span>
                    </div>
                    <span class="avatar">
                        <img class="round" src="{{ route('user.avatar', Auth::user()->uid) }}"
                            alt="{{ config('app.name') }}" height="40" width="40" />
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                    @if (Auth::user()->active_portal == 'admin' && Auth::user()->is_customer == 1)
                        <a class="dropdown-item" href="{{ route('user.switch_view', ['portal' => 'customer']) }}"><i
                                class="me-50" data-feather="log-in"></i>{{ __('locale.labels.switch_view') }}</a>
                        <div class="dropdown-divider"></div>
                    @endif

                    @if (Auth::user()->active_portal == 'customer' && Auth::user()->is_admin == 1)
                        <a class="dropdown-item" href="{{ route('user.switch_view', ['portal' => 'admin']) }}"><i
                                class="me-50" data-feather="log-in"></i>{{ __('locale.labels.switch_view') }}</a>
                        <div class="dropdown-divider"></div>
                    @endif

                    <h6 class="dropdown-header">{{ __('locale.labels.manage_profile') }}</h6>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="{{ route('user.account') }}"><i class="me-50"
                            data-feather="user"></i>{{ __('locale.labels.profile') }}</a>


                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="me-50" data-feather="power"></i> {{ __('locale.menu.Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        @endif
    </ul>
</div>

</nav>
<!-- END: Header-->
