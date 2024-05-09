<div class="row">

    <table class="table">
        <tbody>

            <tr>
                <td style="word-break: initial">{{ __('locale.labels.name') }}</td>
                <td>
                    <a href="{{ route('customer.tasks.show', $task->uid) }}">{{ $task->name }}</a>
                </td>
            </tr>

            <tr>
                <td style="word-break: initial">{{ __('messages.deadline') }}</td>
                <td>
                    {{ \Carbon\Carbon::create($task->deadline)->longRelativeDiffForHumans(\Carbon\Carbon::now(), 1) }}
                </td>
            </tr>

        </tbody>
    </table>


    {{-- set status --}}
    <div class="col-md-6">
        <div class="mb-1">
            <label for="status" class="form-label">
                {{ __('locale.labels.status') }}</label>
            <select class="select2 w-100" id="timezone" name="status">
                @foreach (['in_progress', 'review', 'pause', 'continue'] as $status)
                    <option value="{{ $status }}" @if ($task->status == $status) selected @endif>
                        {{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
        </div>
        @error('status')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- set status --}}

    {{-- note --}}
    <div class="col-12">
        <div class="mb-1">
            <label for="note" class="form-label">
                Note
            </label>
            <textarea type="text" id="note" class="form-control @error('note') is-invalid @enderror"name="note"
                placeholder="place note">{{ $task->note }}</textarea>
            @error('note')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    {{-- description --}}


    <div class="col-12 mt-2">
        <button type="submit" class="btn btn-primary mr-1 mb-1">
            <i data-feather="save"></i> {{ __('locale.buttons.save') }}
        </button>
    </div>


</div>
