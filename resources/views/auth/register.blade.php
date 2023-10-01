@extends('layouts/fullLayoutMaster')

@section('title', __('locale.auth.register'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
    <div class="auth-wrapper auth-cover">
        <div class="auth-inner row m-0">
            <!-- Brand logo-->
            <a class="brand-logo" href="{{ route('login') }}">
                <img src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}" />
            </a>
            <!-- /Brand logo-->

            <!-- Register-->
            <div
                class="col-lg-6 col-md-8 d-flex justify-content-center align-items-center mx-auto auth-bg px-2 px-sm-3 px-lg-5 pt-3 mt-4 mb-4 rounded">

                <form method="POST" action="{{ route('register') }}">

                    @csrf

                    <div class="content-header mb-4">
                        <h2 class="fw-bolder mb-4 text-center">{{ __('locale.auth.register') }}</h2>
                        <span class="text-center">{{ __('locale.auth.create_new_account') }}</span>
                    </div>

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                <div class="alert-body">{{ $error }}</div>
                            </div>
                        @endforeach
                    @endif

                    <div class="row">

                        {{-- first_name --}}
                        <div class="mb-1 col-md-6">
                            <label class="form-label required"
                                for="first_name">{{ __('locale.labels.first_name') }}</label>
                            <input id="first_name" type="text"
                                class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                placeholder="{{ __('locale.labels.first_name') }}" value="{{ old('first_name') }}"
                                autocomplete="first_name" />
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        {{-- last_name --}}
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="last_name">
                                {{ __('locale.labels.last_name') }}
                            </label>
                            <input id="last_name" type="text"
                                class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                placeholder="{{ __('locale.labels.last_name') }}" value="{{ old('last_name') }}"
                                autocomplete="last_name" />

                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- email --}}
                        <div class="col-md-6 mb-1">
                            <label class="form-label required" for="email">{{ __('locale.labels.email') }}</label>
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" name="email"
                                placeholder="{{ __('locale.labels.email') }}" />

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- number --}}
                        <div class="col-md-6 mb-1">
                            <label class="form-label" for="phone">{{ __('locale.labels.phone') }}</label>
                            <input type="number" id="phone" class="form-control" name="phone"
                                value="{{ old('phone') }}" placeholder="{{ __('locale.labels.phone') }}">
                        </div>

                        {{-- password --}}
                        <div class="col-md-6 mb-1">
                            <label class="form-label required" for="password">{{ __('locale.labels.password') }}</label>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input type="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    value="{{ old('password') }}" name="password"
                                    placeholder="{{ __('locale.labels.password') }}" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        {{-- passwords configuration --}}
                        <div class="col-md-6 mb-1">
                            <label class="form-label required"
                                for="password_confirmation">{{ __('locale.labels.password_confirmation') }}</label>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input type="password" id="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    value="{{ old('password_confirmation') }}" name="password_confirmation"
                                    placeholder="{{ __('locale.labels.password_confirmation') }}" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                        </div>

                        {{-- counties --}}
                        <div class="mb-1 col-md-6">
                            <label class="form-label required" for="country">{{ __('locale.labels.country') }}</label>
                            <select class="select2 form-control  @error('country') is-invalid @enderror" name="country"
                                id="country">
                                @foreach (\App\Helpers\Helper::countries() as $country)
                                    <option value="{{ $country['name'] }}"
                                        {{ config('app.country') == $country['name'] ? 'selected' : null }}>
                                        {{ $country['name'] }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        {{-- city --}}
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="city">{{ __('locale.labels.city') }}</label>
                            <input type="text" id="city" class="form-control" name="city"
                                placeholder="{{ __('locale.labels.city') }}" value="{{ old('city') }}">
                        </div>

                        {{-- timezones --}}
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="timezone">{{ __('locale.labels.timezone') }}</label>
                            <select class="select2 form-control" name="timezone" id="timezone">
                                @foreach (\App\Library\Tool::allTimeZones() as $timezone)
                                    <option value="{{ $timezone['zone'] }}"
                                        {{ config('app.timezone') == $timezone['zone'] ? 'selected' : null }}>
                                        {{ $timezone['text'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- languages --}}
                        <div class="mb-1 col-md-6">
                            <label class="form-label" for="locale">{{ __('locale.labels.language') }}</label>
                            <select class="form-select select2  @error('locale') is-invalid @enderror" name="locale"
                                id="locale">
                                @foreach ($languages as $language)
                                    <option value="{{ $language->code }}"
                                        {{ old('locale') == $language->code ? 'selected' : null }}>
                                        {{ $language->name }}</option>
                                @endforeach
                            </select>
                            @error('locale')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>


                        {{-- captcha --}}
                        <div class="mb-1">

                            @if (config('no-captcha.registration'))
                                <fieldset class="form-label-group position-relative">
                                    {{ no_captcha()->input('g-recaptcha-response') }}
                                </fieldset>
                            @endif

                            @if (config('no-captcha.registration'))
                                @error('g-recaptcha-response')
                                    <span class="text-danger">{{ __('locale.labels.g-recaptcha-response') }}</span>
                                @enderror
                            @endif
                        </div>

                        <div class="my-2 d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-primary"
                                tabindex="4">{{ __('locale.auth.create_account') }}</button>
                        </div>

                    </div>

                    <div class="row mb-4">
                        {{-- outsid of form --}}
                        <p class="text-center mt-2">
                            <span>{{ __('Alredy have an account?') }}</span>
                            <a href="{{ route('login') }}"><span>&nbsp;{{ __('locale.auth.login') }}</span></a>
                        </p>

                        @if (config('services.facebook.active') ||
                                config('services.twitter.active') ||
                                config('services.google.active') ||
                                config('services.github.active'))
                            <div class="divider my-2">
                                <div class="divider-text">{{ __('locale.auth.or') }}</div>
                            </div>

                            <div class="auth-footer-btn d-flex justify-content-center">

                                @if (config('services.facebook.active'))
                                    <a class="btn btn-facebook" href="{{ route('social.login', 'facebook') }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook">
                                        <i data-feather="facebook"></i>
                                    </a>
                                @endif

                                @if (config('services.twitter.active'))
                                    <a class="btn btn-twitter" href="{{ route('social.login', 'twitter') }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Twitter">
                                        <i data-feather="twitter"></i>
                                    </a>
                                @endif

                                @if (config('services.google.active'))
                                    <a class="btn btn-google" href="{{ route('social.login', 'google') }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Google">
                                        <i data-feather="mail"></i>
                                    </a>
                                @endif

                                @if (config('services.github.active'))
                                    <a class="btn btn-github" href="{{ route('social.login', 'github') }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Github">
                                        <i data-feather="github"></i>
                                    </a>
                                @endif

                            </div>
                        @endif
                        {{-- outsid of form --}}
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
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

        function showMessage(data) {
            if (data.status == 'error') {
                toastr['warning'](data.message,
                    '{{ __('locale.labels.success ') }}!!', {
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        progressBar: true,
                        newestOnTop: true,
                        rtl: isRtl
                    });
                dataListView.draw();
            } else {
                toastr['warning']("{{ __('locale.exceptions.something_went_wrong') }}",
                    '{{ __('locale.labels.warning ') }}!', {
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        progressBar: true,
                        newestOnTop: true,
                        rtl: isRtl
                    });
            }
        }
    </script>
@endsection

@if (config('no-captcha.registration'))
    @push('scripts')
        {{ no_captcha()->script() }}
        {{ no_captcha()->getApiScript() }}

        <script>
            grecaptcha.ready(() => {
                window.noCaptcha.render('register', (token) => {
                    document.querySelector('#g-recaptcha-response').value = token;
                });
            });
        </script>
    @endpush
@endif
