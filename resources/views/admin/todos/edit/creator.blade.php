<div class="row">

    <div class="col-md-6">
        <div class="mb-1">
            <label for="name" class="required form-label">{{ __('locale.labels.name') }}</label>
            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ $todo->name }}" name="name" placeholder="Todo name">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>


    <div class="col-md-6">
        <div class="mb-1">
            <label for="title" class="form-label">{{ __('locale.labels.title') }}</label>
            <input type="text" id="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ $todo->title }}" name="title" placeholder="Todo title">
            @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    {{-- assign users --}}
    <div class="col-12">
        <div class="mb-1">
            <label for="assign_to" class="form-label required">
                {{ __('messages.Assign to') }}
            </label>
            <select class="select2 select2-icons form-select select2-hidden-accessible" id="assign_to"
                name="assign_to[]" multiple="" data-select2-id="assign_to">
                <option @if (in_array('all', $todo->assigned())) selected @endif value="all">
                    Available for all
                </option>
                @foreach ($customers as $user)
                    <option @if (in_array($user->id, $todo->assigned())) selected @endif value="{{ $user->id }}">
                        {{ $user->displayName() }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('assign_to')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- assign users --}}


    {{-- set status --}}
    <div class="col-md-6">
        <div class="mb-1">
            <label for="status" class="form-label">
                {{ __('locale.labels.status') }}</label>
            <select class="select2 w-100" id="timezone" name="status">
                @foreach (\App\Models\Todos::$status as $status)
                    <option value="{{ $status }}" @if ($todo->status == $status) selected @endif>
                        {{ str_replace('_', ' ', $status) }}
                    </option>
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

    {{-- set deadline --}}
    <div class="col-md-6">
        <div class="mb-1">
            <label for="deadline" class=" form-label">
                {{ __('messages.deadline') }}</label>
            <input type="datetime" name="deadline" id="deadline" class="form-control">
        </div>
        @error('status')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    {{-- set deadline --}}

    {{-- description --}}
    <div class="col-12">
        <div class="mb-1">
            <label for="description" class="form-label">
                {{ __('locale.labels.description') }}
            </label>
            <textarea type="text" id="description"
                class="form-control @error('description') is-invalid @enderror"name="description" placeholder="Todo description">{{ $todo->description }}</textarea>
            @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    {{-- description --}}


    <div class="col-12">
        <div class="mb-1">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="send_email" value="checked" name="send_email">
                <label class="form-check-label" for="send_email">{{ __('Send email') }}</label>
            </div>
        </div>
    </div>

    <div class="col-12 mt-2">
        <button type="submit" class="btn btn-primary mr-1 mb-1">
            <i data-feather="save"></i> {{ __('locale.buttons.save') }}
        </button>
    </div>


</div>
