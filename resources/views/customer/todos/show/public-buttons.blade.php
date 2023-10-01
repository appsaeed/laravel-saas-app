@if (!$todo->hasEmployee())
    <div class="btn-group">
        <button id="will_do" class="btn btn-success waves-light waves-effect fw-bold mx-1">
            I will do <i data-feather="plus-circle"></i></button>
    </div>
@else
    @if (!$todo->hasReview() && !$todo->getOption('task_paused_by_' . auth()->user()->id))
        <div class="btn-group">
            <button id="pause_task" class="btn btn-primary waves-light waves-effect fw-bold mx-1">
                pause task <i data-feather="pause"></i>
            </button>
        </div>
    @endif

    @if (!$todo->hasReview() && $todo->status == 'pause')
        <div class="btn-group">
            <button id="continue_task" class="btn btn-primary waves-light waves-effect fw-bold mx-1">
                continue task <i data-feather="pause"></i></button>
        </div>
    @endif

    <div class="btn-group">
        <button @disabled($todo->hasReview()) id="send_review"
            class="btn btn-success waves-light waves-effect fw-bold mx-1">
            Send for review <i data-feather="send"></i></button>
    </div>
@endif
