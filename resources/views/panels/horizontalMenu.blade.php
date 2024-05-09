@php
    $configData = Helper::applClasses();
@endphp
{{-- Horizontal Menu --}}
<div class="horizontal-menu-wrapper">
    <div class="header-navbar navbar-expand-sm navbar navbar-horizontal
  {{ $configData['horizontalMenuClass'] }}
    {{ $configData['theme'] === 'dark' ? 'navbar-dark' : 'navbar-light' }}
            navbar-shadow menu-border
{{ $configData['layoutWidth'] === 'boxed' && $configData['horizontalMenuType'] === 'navbar-floating' ? 'container-xxl' : '' }}"
        role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto">
                    <a class="navbar-brand" href="{{ route('login') }}">
                        <span class="brand-logo">{{ config('app.logo') }}</span>
                        <h2 class="brand-text mb-0">{{ config('app.name') }}</h2>
                    </a>
                </li>
                <li class="nav-item nav-toggle">
                    <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                        <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <!-- Horizontal menu content-->
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                {{-- Foreach menu item starts --}}
                @php
                    if (auth()->user()->active_portal == 'admin') {
                        $sidebarMenu = Menus::data()->admin;
                    } else {
                        $sidebarMenu = Menus::data()->customer;
                    }
                @endphp

                @foreach ($sidebarMenu as $menu)
                    {{-- Add Custom Class with nav-item --}}
                    @php
                        $custom_classes = '';
                        if (isset($menu->classlist)) {
                            $custom_classes = $menu->classlist;
                        }
                        $permission = explode('|', $menu->access);
                    @endphp
                    @canany($permission, auth()->user())
                        <li class="nav-item @if (isset($menu->submenu)) {{ 'dropdown' }} @endif {{ $custom_classes }} {{ request()->url() === $menu->url ? 'active' : '' }}"
                            @if (isset($menu->submenu)) {{ 'data-menu=dropdown' }} @endif>
                            <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0)' }}"
                                class="nav-link d-flex align-items-center @if (isset($menu->submenu)) {{ 'dropdown-toggle' }} @endif"
                                target="{{ isset($menu->newTab) ? '_blank' : '_self' }}"
                                @if (isset($menu->submenu)) {{ 'data-bs-toggle=dropdown' }} @endif>
                                <i data-feather="{{ $menu->icon }}"></i>
                                <span>{{ $menu->name }}</span>
                            </a>
                            @if (isset($menu->submenu))
                                @include('panels/horizontalSubmenu', [
                                    'menu' => $menu->submenu,
                                ])
                            @endif
                        </li>
                    @endcanany
                @endforeach
                {{-- Foreach menu item ends --}}
            </ul>
        </div>
    </div>
</div>
