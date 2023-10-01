<div class="card">
    <div class="card-body py-2 my-25">
        <form class="form form-vertical" action="{{ route('user.account.update_information') }}" method="post">
            @csrf
            <div class="row mt-1">
                <div class="col-12 col-sm-6">
                    <h5 class="mb-1"><i data-feather="user"></i>{{ __('locale.customer.personal_information') }}</h5>

                    <div class="mb-1">
                        <label for="phone" class="form-label required">{{ __('locale.labels.phone') }}</label>
                        <input type="number" id="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ $user->customer->phone }}" name="phone" required>
                        @error('phone')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="company" class="form-label">{{ __('locale.labels.company') }}</label>
                        <input type="text" id="company" class="form-control @error('company') is-invalid @enderror"
                            value="{{ $user->customer->company }}" name="company">
                        @error('company')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="website" class="form-label">{{ __('locale.labels.website') }}</label>
                        <input type="url" id="website" class="form-control @error('website') is-invalid @enderror"
                            value="{{ $user->customer->website }}" name="website">
                        @error('website')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>

                    <h5 class="mb-1 mt-2 mt-sm-0"><i data-feather="map-pin"></i> {{ __('locale.labels.address') }}</h5>

                    <div class="mb-1">
                        <label for="address" class="form-label required">{{ __('locale.labels.address') }}</label>
                        <input type="text" id="address" class="form-control @error('address') is-invalid @enderror"
                            value="{{ $user->customer->address }}" name="address" required>
                        @error('address')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="city" class="form-label required">{{ __('locale.labels.city') }}</label>
                        <input type="text" id="city" class="form-control @error('city') is-invalid @enderror"
                            value="{{ $user->customer->city }}" name="city" required>
                        @error('city')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="state" class="form-label">{{ __('locale.labels.state') }}</label>
                        <input type="text" id="state" class="form-control @error('state') is-invalid @enderror"
                            value="{{ $user->customer->state }}" name="state">
                        @error('state')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="postcode" class="form-label">{{ __('locale.labels.postcode') }}</label>
                        <input type="text" id="postcode"
                            class="form-control @error('postcode') is-invalid @enderror"
                            value="{{ $user->customer->postcode }}" name="postcode">
                        @error('postcode')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="country" class="form-label required">{{ __('locale.labels.country') }}</label>
                        <select class="form-select select2" id="country" name="country">
                            @foreach (\App\Helpers\Helper::countries() as $country)
                                <option value="{{ $country['name'] }}"
                                    {{ $user->customer->country == $country['name'] ? 'selected' : null }}>
                                    {{ $country['name'] }}</option>
                            @endforeach
                        </select>
                        @error('country')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>


                </div>
                <div class="col-12 d-flex flex-sm-row flex-column mt-1">
                    <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0"><i data-feather="save"></i>
                        {{ __('locale.buttons.save_changes') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
