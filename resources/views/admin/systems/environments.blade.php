@extends('layouts/contentLayoutMaster')
@section('title', 'System Informations')
@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection


@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endsection
<style>
    .table-responsive table td {
        padding: 20px !important;
    }

    table {}

    table tbody {
        border: none !important;
    }
</style>
@section('content')

    <!-- Basic table -->
    <section id="datatables-basic">

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>Environment variables</h2>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive table datatables-basic">
                                <table class="table">
                                    <tbody>                                        
                                        <tr>
                                            <td style="word-break: initial">key</td>
                                            <td>value </td>
                                        </tr>
                                        @foreach ($environments as $key => $value)
                                        <tr>
                                            <td style="word-break: initial">
                                                {{ $key }}
                                            </td>
                                            <td>
                                                {{ $value}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!--/ Basic table -->


@endsection


@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>

@endsection
@section('page-script')
    {{-- Page js files --}}

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr['warning']("{{ $error }}",
                    'Warning!', {
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        progressBar: true,
                        newestOnTop: true,
                        rtl: isRtl
                    });
            </script>
        @endforeach
    @endif

@endsection
