@if (session()->has('admin_user_id') && session()->has('temp_user_id'))
   <div class="alert alert-success" role="alert">
      <h4 class="alert-heading">{{ __('locale.labels.attention') }}</h4>
      <div class="alert-body">
         {!! __('locale.labels.login_as', ['name' => auth()->user()->displayName(), 'route' => route('logout'), 'admin' => Session::get('admin_user_name') ]) !!}
      </div>
   </div>
@endif
