<div class="col-md-3">
    <div class="card">
        <div class="card-header pb-0 pt-1">
            <div>
                <h4 class="fw-bolder mb-0">
                    @if (isset($task['name']))
                        {{ $task['name'] }}
                    @else
                        no name
                    @endif
                </h4>
            </div>
            <div class="avatar-content">
                <i data-feather="more-vertical" class="font-medium-5"></i>
            </div>
        </div>
        <hr>

        <div class="task-boxable p-1 noscroll">
            @if (isset($task['items']))

                @foreach ($task['items'] as $todo)
                    <div class="task-card">

                        <div class="task-content" style="border-left:1px solid {{ statusToColor($todo->status) }}">

                            <div class="task-link">
                                <a href="{{ route('customer.todos.show', $todo->uid) }}">
                                    <i data-feather="link" class="font-medium-5"></i>
                                </a>
                            </div>

                            <h6 class="">
                                {{ $todo->name }}
                            </h6>
                            <p class="m-0 pt-1 font-normal">
                                {{ $todo->description }}
                            </p>
                        </div>

                    </div>
                @endforeach
            @else
                no items
            @endif


        </div>
    </div>
</div>
