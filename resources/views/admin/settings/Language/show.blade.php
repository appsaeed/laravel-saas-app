@extends('layouts/contentLayoutMaster')

@section('title', $language->name)

@section('content')

    {{-- Vertical Tabs start --}}
    <section id="vertical-tabs">

        <div class="row match-height">
            <div class="col-12">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('locale.labels.translate') }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form enctype="multipart/form-data" class="form form-vertical" action="{{ route('admin.languages.update', $language->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-1">
                                                <textarea name="{{ $language->code }}" class="my-code-messages" rows="20" style="width: 100%">{!! $content !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary mb-1"><i data-feather="save"></i> {{ __('locale.buttons.save') }}</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Vertical Tabs end --}}
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script type="text/javascript" src="{{  asset(mix('vendors/js/ace/ace/ace.js')) }}"></script>
    <script type="text/javascript" src="{{  asset(mix('vendors/js/ace/ace/theme-twilight.js')) }}"></script>
    <script type="text/javascript" src="{{  asset(mix('vendors/js/ace/ace/mode-php.js')) }}"></script>
    <script type="text/javascript" src="{{  asset(mix('vendors/js/ace/ace/mode-yaml.js')) }}"></script>
    <script type="text/javascript" src="{{  asset(mix('vendors/js/ace/jquery-ace.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        $('.my-code-messages').ace({theme: 'twilight', lang: 'yaml'});
    </script>
@endsection


