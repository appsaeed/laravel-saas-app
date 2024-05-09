@extends('layouts/contentLayoutMaster')

@section('title', __('locale.developers.api_documents'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/ui/prism.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-knowledge-base.css')) }}">
@endsection

@section('content')
    <!-- Knowledge base question Content  -->
    <section id="api-documentation">
        <div class="row">

            <div class="col-md-12 d-none d-sm-block">
                <p class="row justify-content-center welcome-messages">
                    {{ __('locale.labels.welcome_to_docs', ['brandname' => config('app.name')]) }}</p>
                <p class="row justify-content-center mb-3 welcome-description">
                    {{ __('locale.description.api_docs', ['brandname' => config('app.name')]) }}
                </p>
            </div>

            <div class="col-lg-3 col-md-5 col-12">
                <div class="card">
                    <div class="card-body" id="features">
                        <h5 class="text-success text-uppercase">{{ config('app.name') }} {{ __('locale.labels.api') }}</h5>
                        <a href="#" class="knowledge-base-question">
                            <ul class="list-group list-group-flush mt-1">
                                @foreach (Apidocs::data() as $api)
                                    <li class="list-group-item cursor-pointer" id="{{ Str::slug( $api->title ) }}">
                                        {{ $api->title }}
                                    </li>
                                @endforeach
                            </ul>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-7 col-12">
                <div class="card">
                    <div class="card-body features_description">

                        @foreach (Apidocs::data() as $api)
                            <div class="title mb-2" id="{{ Str::slug($api->title) }}-div">

                                <div class="text-uppercase text-primary font-medium-2 mb-3">
                                    {{ $api->title }}
                                </div>

                                {{ $api->description }}

                                <p class="font-medium-2 mt-2 text-primary">
                                    {{ __('locale.developers.api_endpoint') }}
                                </p>

                                <pre>
                                    <code class="language-markup text-primary">
                                        {{ $api->endpoint }}
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    {{ __('locale.developers.parameters') }}
                                </div>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>{{ __('locale.developers.parameter') }}</th>
                                                <th>{{ __('locale.labels.required') }}</th>
                                                <th style="width:50%;">{{ __('locale.labels.description') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>Authorization</td>
                                                <td>
                                                    <div class="badge badge-primary text-uppercase mr-1 mb-1">
                                                        <span>{{ __('locale.labels.yes') }}</span>
                                                    </div>
                                                </td>
                                                <td>When calling our API, send your api token with the authentication type
                                                    set as <code>Bearer</code>
                                                    (Example: <code>Authorization: Bearer {api_token}</code>)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Accept</td>
                                                <td>
                                                    <div class="badge badge-primary text-uppercase mr-1 mb-1">
                                                        <span>{{ __('locale.labels.yes') }}</span>
                                                    </div>
                                                </td>
                                                <td>Set to <code>application/json</code></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-2 font-medium-2 text-primary">Example request</div>
                                <pre>
                                                            <code class="language-php">
                            curl -X {{ $api->method ?? 'GET' }} {{ $api->endpoint }} \
                                 -H 'Authorization: Bearer {{ Auth::user()->api_token }}'
                                                            </code>
                                                        </pre>

                                <div class="mt-2 font-medium-2 text-primary">Returns</div>
                                <p>Returns a contact object if the request was successful. </p>
                                <pre>
                                                            <code class="language-json">
                            {
                                "status": "success",
                                "data": "profile data with all details",
                            }
                                                            </code>
                                                        </pre>
                                <p>If the request failed, an error object will be returned.</p>
                                <pre>
                                                            <code class="language-json">
                            {
                                "status": "error",
                                "message" : "A human-readable description of the error."
                            }
                                                            </code>
                                                        </pre>

                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Knowledge base question Content ends -->
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
@endsection


@section('page-script')
    {{-- page js files --}}
    <script>
        $(document).ready(function() {
            let featureDescription = $(".features_description .title");
            featureDescription.hide();

            $("#{{ Str::slug(Apidocs::data()[0]->title) }}-div").show();

            function setFeature(feature) {
                featureDescription.each(function() {
                    if (this !== feature) $(this).hide();
                });
                $("#" + feature).toggle();
            }

            $("#features li").click(function(e) {
                e.preventDefault();
                setFeature(this.id + "-div");
            });
        });
    </script>
@endsection
