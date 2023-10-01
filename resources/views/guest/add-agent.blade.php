@php
    $configData = Helper::applClasses();
@endphp

@extends('layouts/fullLayoutMaster')

@section('title', 'Add invite Agent')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')

    <div class="auth-wrapper auth-cover">
        <div class="auth-inner row m-0">
            <!-- Brand logo-->
            <a class="brand-logo" href="{{route('login')}}">
                <img src="{{asset(config('app.logo'))}}" alt="{{config('app.name')}}"/>
            </a>
            <!-- /Brand logo-->

            <!-- Left Text-->
            <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
                <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                    @if($configData['theme'] === 'dark')
                        <img src="{{asset('images/pages/reset-password-v2-dark.svg')}}" class="img-fluid" alt="Register V2"/>
                    @else
                        <img src="{{asset('images/pages/reset-password-v2.svg')}}" class="img-fluid" alt="Register V2"/>
                    @endif
                </div>
            </div>
            <!-- /Left Text-->

            <!-- add  agent-->
            <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
                <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">

                    @if ($errors->any())

                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                <span class="alert-body">{{ $error }}</span>
                            </div>
                        @endforeach

                    @endif
                    <h2 class="card-title fw-bold mb-1">Add number</h2>
                    <p class="card-text mb-2">Add your number to recived text message</p>
                    <form class="auth-reset-password-form mt-2" method="POST" action="{{route('agent.addNumber')}}">
                        @csrf
                        <div class="mb-1">
                            <label class="form-label" for="number">Enter your phone number</label>
                            <input id="number" type="text" class="form-control @error('number') is-invalid @enderror" name="number" placeholder="phone number" value="{{ $email ?? old('email') }}" required autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-1">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="name">Enter your name</label>
                            </div>
                            <div class="input-group input-group-merge form-password-toggle">
                                <input id="name" type="text" class="form-control form-control-merge @error('password') is-invalid @enderror" name="name" placeholder="Your name" autocomplete="new-password" autofocus tabindex="1">
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>
                        </div>

                        @if(config('no-captcha.registration'))
                            <fieldset class="form-label-group position-relative">
                                {{ no_captcha()->input('g-recaptcha-response') }}
                            </fieldset>
                        @endif

                        @if(config('no-captcha.registration'))
                            @error('g-recaptcha-response')
                            <span class="text-danger">{{ __('locale.labels.g-recaptcha-response') }}</span>
                            @enderror
                        @endif
                        
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <input type="hidden" name="exten_id" value="{{$exten_id}}">
                        <button type="submit" class="btn btn-primary w-100" tabindex="3">Save</button>
                    </form>
                    <p class="text-center mt-2">
                        <a href="{{url('login')}}">
                            <i data-feather="chevron-left"></i> {{ __('locale.auth.back_to_login') }}
                        </a>
                    </p>
                </div>
            </div>
            <!-- /Reset password-->
        </div>
    </div>
@endsection


@if(config('no-captcha.registration'))
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