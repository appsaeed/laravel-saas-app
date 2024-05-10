@extends('layouts/contentLayoutMaster')
@section('title', 'Scan directories')
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
                        <h2>Scan files and directories</h2>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive table datatables-basic">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td style="word-break: initial">
                                                <b>type</b>
                                            </td>
                                            <td> <b>value</b> </td>
                                        </tr>
                                        @php
                                            $param = request()->input('path');
                                            $fullpath = base_path($param ?? '/');
                                            function path(): string
                                            {
                                                $str = func_get_args();
                                                $str = join('/', $str);
                                                $str = trim($str, '/');
                                                $str = ltrim($str, '/');
                                                return $str;
                                            }
                                        @endphp
                                        @foreach (scandir($fullpath) as $path)
                                            @if ($path != '.')
                                                <tr>
                                                    <td style="word-break: initial">
                                                        <i data-feather="{{ is_dir(base_path($path)) ? 'folder' : 'file' }}"
                                                            class="{{ is_dir(base_path($path)) ? 'text-success' : 'text-info' }} font-medium-4"></i>
                                                    </td>
                                                    <td>
                                                        @if (is_dir($fullpath . '/' . $path))
                                                            <a
                                                                href="{{ route('admin.systems.filemanager') }}?path={{ $param . '/' . $path }}">
                                                                {{ $path }}
                                                            </a>
                                                        @else
                                                            {{ $path }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.delete-file') }}?name={{ $fullpath . '/' . $path }}"
                                                            class="text-danger cursor-pointer" title="delete">
                                                            <i data-feather="trash" class="font-medium-4"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
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
