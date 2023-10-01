<div class="col-md-6 col-12">
    <div class="form-body">
        <form class="form form-vertical" action="{{ route('admin.settings.notifications') }}" method="post">
            @csrf

            <div class="col-12">
                <div class="mb-1">
                    <label for="notification_phone"
                        class="form-label required">{{ __('locale.settings.notification_phone') }}</label>
                    <input type="text" id="notification_phone" name="notification_phone" required class="form-control"
                        value="{{ \App\Helpers\Helper::app_config('notification_phone') }}">
                    @error('notification_phone')
                        <p><small class="text-danger">{{ $message }}</small></p>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="mb-1">
                    <label for="notification_from_name"
                        class="form-label required">{{ __('locale.settings.notification_from_name') }}</label>
                    <input type="text" id="notification_from_name" name="notification_from_name" required
                        class="form-control" value="{{ \App\Helpers\Helper::app_config('notification_from_name') }}">
                    @error('notification_from_name')
                        <p><small class="text-danger">{{ $message }}</small></p>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="mb-1">
                    <label for="notification_email"
                        class="form-label required">{{ __('locale.settings.notification_email') }}</label>
                    <input type="text" id="notification_email" name="notification_email" required
                        class="form-control" value="{{ \App\Helpers\Helper::app_config('notification_email') }}">
                    @error('notification_email')
                        <p><small class="text-danger">{{ $message }}</small></p>
                    @enderror
                </div>
            </div>


            {{-- User Registration notificaiton --}}
            <div class="col-12">
                <div class="mb-1">
                    <div class="form-check me-3 me-lg-5 mt-1">
                        <input type="checkbox" value="true" class="form-check-input"
                            name="user_registration_notification_email"
                            {{ \App\Helpers\Helper::app_config('user_registration_notification_email') == true ? 'checked' : null }}>
                        <label
                            class="form-label">{{ __('locale.settings.user_registration_notification_email') }}</label>
                    </div>

                </div>
            </div>

            {{-- Login notification --}}
            <div class="col-12">
                <div class="mb-1">
                    <div class="form-check me-3 me-lg-5 mt-1">
                        <input type="checkbox" class="form-check-input" value="true" name="login_notification_email">

                        <label class="form-label">{{ __('locale.settings.login_notification_email') }}</label>
                    </div>

                </div>
            </div>

            <div class="col-12 mt-2">
                <button type="submit" class="btn btn-primary mr-1 mb-1">
                    <i data-feather="save"></i> {{ __('locale.buttons.save') }}
                </button>
            </div>


        </form>
    </div>
</div>
