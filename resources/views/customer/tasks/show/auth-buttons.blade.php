<div class="btn-group">
    <a href="{{ route('customer.tasks.edit', $task->uid) }}"
        class="btn btn-primary waves-light waves-effect fw-bold mx-1">
        update <i data-feather="upload-cloud"></i></a>
</div>

@if ($task->status === 'review')
    <div class="btn-group">
        <button class="btn btn-success waves-light waves-effect fw-bold mx-1" data-bs-toggle="modal"
            data-bs-target="#mark-as-complete">
            Mark as complete <i data-feather="upload-cloud"></i>
        </button>
    </div>
    @include('customer.tasks.complete.model')
@endif