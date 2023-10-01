<div class="row">

    {{-- in progress --}}
    @include('customer.taskcard', [
        'task' => [
            'name' => __('locale.menu.In progress'),
            'items' => 1,
        ],
    ])
    {{-- in reviews --}}

    {{-- in reviews --}}
    @include('customer.taskcard', [
        'task' => [
            'name' => __('locale.menu.Reviews'),
            'items' => 2,
        ],
    ])

    {{-- in done --}}
    @include('customer.taskcard', [
        'task' => [
            'name' => __('locale.menu.Done'),
            'items' => 1,
        ],
    ])

    {{-- in done --}}
    @include('customer.taskcard', [
        'task' => [
            'name' => __('locale.menu.All ads'),
            'items' => 3,
        ],
    ])


    {{-- <div class="col-lg-8 col-sm-6 col-12">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <h3 class="text-primary">{{ \App\Helpers\Helper::greetingMessage() }}</h3>
                <p class="font-medium-2 mt-2">
                    {{ __('locale.description.dashboard', ['brandname' => config('app.name')]) }}</p>
            </div>
        </div>
    </div> --}}

</div>
