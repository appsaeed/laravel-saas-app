{{-- Modal --}}
<div class="modal fade text-left" id="mark-as-complete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Mark as complete</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('customer.todos.mark_as_complete', $todo->uid) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="col-12">
                        <div class="mb-1">
                            <label for="image" class="form-label">Completed by:</label>
                            <select class="select2 select2-icons form-select select2-hidden-accessible" id="assign_to"
                                name="completed_by" required>
                                @foreach ($reviewers as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->displayName() }} |
                                        {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">complete</button>
                </div>
            </form>
        </div>
    </div>
</div>
