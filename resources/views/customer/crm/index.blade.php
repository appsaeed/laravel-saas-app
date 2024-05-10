@extends('layouts/contentLayoutMaster')

@section('title', 'CRM Dashboard')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">

@endsection

@section('content')

    <!-- Basic table -->
    <section id="datatables-basic">
        <div class="mb-3 mt-2">

            <div class="col-md-12 col-sm-12 mb-1">
                <div class="btn-group">
                    <button class="btn btn-primary fw-bold dropdown-toggle" type="button" id="bulk_actions"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('locale.labels.actions') }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="bulk_actions">
                        <a class="dropdown-item bulk-enable" href="#"><i data-feather="check"></i>
                            {{ __('locale.datatables.bulk_enable') }}</a>
                        <a class="dropdown-item bulk-disable" href="#"><i data-feather="stop-circle"></i>
                            {{ __('locale.datatables.bulk_disable') }}</a>
                        <a class="dropdown-item bulk-delete" href="#"><i data-feather="trash"></i>
                            {{ __('locale.datatables.bulk_delete') }}</a>
                    </div>
                </div>

                <div class="btn-group">
                    <a class="btn btn-success waves-light waves-effect fw-bold mx-1"> {{ __('locale.buttons.add_new') }} <i
                            data-feather="plus-circle"></i></a>
                </div>

                @if (Auth::user()->customer->getOption('list_export') == 'yes')
                    <div class="btn-group">
                        <a href="#" class="btn btn-info waves-light waves-effect fw-bold mx-1" data-bs-toggle="modal"
                            data-bs-target="#exportData"> {{ __('locale.buttons.export') }} <i
                                data-feather="file-text"></i></a>
                    </div>

                    <div class="modal fade" id="exportData" tabindex="-1" role="dialog" aria-labelledby="exportData"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel33">{{ __('locale.buttons.export') }}</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <form action="{{ route('customer.crm.export') }}" method="post">
                                    @csrf
                                    <div class="modal-body">

                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="mb-1">
                                                    <label for="start-date-picker" class="form-label">Called:</label>
                                                    <input type="text" name="called" class="form-control date_picker"
                                                        placeholder="" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-1">
                                                    <label for="start-date-picker" class="form-label">From:</label>
                                                    <input type="text" name="from" class="form-control date_picker"
                                                        placeholder="" />
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-1">
                                                    <label for="end-date-picker"
                                                        class="form-label">{{ __('locale.labels.number') }}:</label>
                                                    <input type="text" name="number" class="form-control"
                                                        placeholder="" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-1">
                                                    <label for="end-date-picker" class="form-label">Disposition:</label>
                                                    <select class="form-select" name="disposition">
                                                        <option selected value=""></option>
                                                        <option value="Set Appointment">Set Appointment</option>
                                                        <option value="Set Appointment">Set Appointment</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>



                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i data-feather="file-text"></i>
                                            {{ __('locale.labels.generate') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-12 col-sm-12 mb-1">
                <div class="crm-example-t mt-2 mb-2">
                    When contacting your prospects, we have found the best approach for starting the conversation is
                    something like:
                </div>
                <div class="crm-body-t">
                    Hello, this is --- from ----Realty and I saw you called our information hotline earlier and I wanted to
                    reach out to you to see if you got all of your questions answered.
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class="table datatables-basic">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('locale.labels.id') }}</th>
                                <th>date</th>
                                <th>called</th>
                                <th>from</th>
                                <th>number</th>
                                <th>property</th>
                                <th>agent</th>
                                <th>Disposition</th>
                                <th>Notes</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
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

    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>

@endsection
@section('page-script')
    {{-- Page js files --}}
    <script>
        $(document).ready(function() {
            "use strict"
            
            // init table dom
            let Table = $("table");

            // init list view datatable
            let dataListView = $('.datatables-basic').DataTable({

                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('customer.crm.search') }}",
                    "dataType": "json",
                    "type": "post",
                    "data": {
                        _token: "{{ csrf_token() }}"
                    }
                },
                "columns": [{
                        "data": "uid"
                    },
                    {
                        "data": "uid"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "called"
                    },
                    {
                        "data": "from"
                    },
                    {
                        "data": "number"
                    },
                    {
                        "data": "property"
                    },
                    {
                        "data": "agent"
                    },
                    {
                        "data": "disposition"
                    },
                    {
                        "data": "notes"
                    },
                    {
                        "data": 'responsive_id'
                    },
                ],

                searchDelay: 1500,
                columnDefs: [{
                        // For Checkboxes
                        targets: 0,
                        orderable: false,
                        responsivePriority: 2,
                        render: function(data) {
                            return (
                                '<div class="form-check"> <input class="form-check-input dt-checkboxes" type="checkbox" value="" id="' +
                                data +
                                '" /><label class="form-check-label" for="' +
                                data +
                                '"></label></div>'
                            );
                        },
                        checkboxes: {
                            selectAllRender: '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                            selectRow: true
                        }
                    },
                    {
                        targets: 1,
                        visible: false
                    },
                    {
                        // Actions
                        targets: -1,
                        title: 'Edit',
                        className: 'expand',
                        orderable: false,
                        tableIndex: 0,
                        responsivePriority: 2,
                        render: function(data, type, full) {
                            const icon = feather.icons['edit'].toSvg({
                                class: 'font-medium-4'
                            });
                            return (
                                `<div id="mode_data" onclick="openModal($(this))" data-rows='${JSON.stringify(full)}' class="text-primary me-1" data-bs-toggle="tooltip" data-bs-placement="top" title= "Setup Messages">${icon}</div>`
                            );
                        }
                    }
                ],
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

                language: {
                    paginate: {
                        // remove previous & next text from pagination
                        previous: '&nbsp;',
                        next: '&nbsp;'
                    },
                    sLengthMenu: "_MENU_",
                    sZeroRecords: "{{ __('locale.datatables.no_results') }}",
                    sSearch: "{{ __('locale.datatables.search') }}",
                    sProcessing: "{{ __('locale.datatables.processing') }}",
                    sInfo: "{{ __('locale.datatables.showing_entries', ['start' => '_START_', 'end' => '_END_', 'total' => '_TOTAL_']) }}"
                },
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                let data = row.data();
                                return 'Details of ' + data['voicebox'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {

                            let data = $.map(columns, function(col) {
                                return col.title !==
                                    '' // ? Do not show row in modal popup if title is blank (for check box)
                                    ?
                                    '<tr data-dt-row="' +
                                    col.rowIdx +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table class="table"/>').append('<tbody>' + data +
                                '</tbody>') : false;
                        }
                    }
                },
                aLengthMenu: [
                    [10, 20, 50, 100],
                    [10, 20, 50, 100]
                ],
                select: {
                    style: "multi"
                },
                order: [
                    [2, "desc"]
                ],
                displayLength: 10,
                initComplete: function() {
                    $('button.btn-close.mode-close').click(function() {
                        $('.modal.show').slideToggle(500);
                        $('.modal-backdrop').remove();
                    });

                    window.openModal = function(cthis) {
                        const editIcon =
                            `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>`;
                        const saveIcon =
                            `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>`;

                        window.submitEdit = function(current) {

                            $.ajax({
                                url: "{{ route('customer.crm.edit') }}",
                                type: "POST",
                                data: {
                                    uid: current.data('uid'),
                                    _method: 'POST',
                                    _token: "{{ csrf_token() }}",
                                    disposition: $('#appointment_val').val(),
                                    notes: $('#notes_val').val().trim()
                                },
                                success: function(data) {
                                    showResponseMessage(data);
                                },
                                error: function(reject) {
                                    if (reject.status === 422) {
                                        let errors = reject.responseJSON.errors;
                                        $.each(errors, function(key, value) {
                                            toastr['warning'](value[0],
                                                "{{ __('locale.labels.attention') }}", {
                                                    closeButton: true,
                                                    positionClass: 'toast-top-right',
                                                    progressBar: true,
                                                    newestOnTop: true,
                                                    rtl: isRtl
                                                });
                                        });
                                    } else {
                                        toastr['warning'](reject.responseJSON
                                            .message,
                                            "{{ __('locale.labels.attention') }}", {
                                                positionClass: 'toast-top-right',
                                                containerId: 'toast-top-right',
                                                progressBar: true,
                                                closeButton: true,
                                                newestOnTop: true
                                            });
                                    }
                                }
                            })
                        }


                        var rows = cthis.data('rows');
                        let row = '';

                        row += `<tr><td>Date</td> <td>${rows.created_at}</td></tr>`;
                        row += `<tr><td>Called</td> <td>${rows.called}</td></tr>`;
                        row += `<tr><td>From</td> <td>${rows.from}</td></tr>`;
                        row += `<tr><td>Number</td> <td>${rows.number}</td></tr>`;
                        row += `<tr><td>Property</td> <td>${rows.property}</td></tr>`;
                        row += `<tr><td>Agent</td> <td>${rows.agent}</td></tr>`;
                        row += `<tr>
                                    <td>Disposition</td>
                                    <td>
                                        <select class="form-select" id="appointment_val">
                                            <option value="${rows.disposition}">${rows.disposition}</option>
                                            <option value="Set Appointment">Set Appointment</option>
                                        </select>                                       
                                    </td>

                                </tr>`;

                        row += `<tr>
                                    <td>Notes</td> 
                                    <td>
                                        <input id="notes_val" class="form-control" type="text" value="${rows.notes}"/>                                        
                                    </td>                                    
                                </tr>`;

                        row += `<tr><td>Action</td> 
                                    <td>
                                        <div data-uid="${rows.uid}"  onclick="submitEdit($(this));" style="float:right;margin-left:10px;" class="btn btn-primary">${saveIcon} save</div>
                                    </td>
                                </tr>`;


                        $('body').append('<div class="modal-backdrop fade show"></div>');
                        $('#expand_table tbody').html(row);
                        $('.modal-title').html('Details of ' + rows.from);
                        $('#expand_table').slideDown(500);

                    };
                }
            });

            $('body').append(
                `<div class="modal fade dtr-bs-modal show" style="display:none;" aria-modal="true" role="dialog" id="expand_table"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"></h4><button type="button" class="btn-close mode-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><table class="table"><tbody></tbody></table></div></div></div></div>`
            );


            Table.delegate(".get_status", "click", function() {
                let group_id = $(this).data('id');
                $.ajax({
                    url: "{{ url('contacts') }}" + '/' + group_id + '/active',
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        showResponseMessage(data);
                    }
                });
            });

            // On copy
            Table.delegate(".action-copy", "click", function(e) {
                e.stopPropagation();
                let id = $(this).data('id');
                let group_name = $(this).data('value')
                Swal.fire({
                    title: "Copy extension",
                    text: "Would you like to copy the extension?",
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    cancelButtonText: "{{ __('locale.buttons.cancel') }}",
                    cancelButtonAriaLabel: "{{ __('locale.buttons.cancel') }}",
                    confirmButtonText: feather.icons['copy'].toSvg({
                        class: 'font-medium-1 me-50'
                    }) + "Yes",
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false,
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ url('campaigns') }}" + '/' + id + '/copy',
                            type: "POST",
                            data: {
                                _method: 'POST',
                                group_name: result.value,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                showResponseMessage(data);
                            },
                            error: function(reject) {
                                if (reject.status === 422) {
                                    let errors = reject.responseJSON.errors;
                                    $.each(errors, function(key, value) {
                                        toastr['warning'](value[0],
                                            "{{ __('locale.labels.attention') }}", {
                                                closeButton: true,
                                                positionClass: 'toast-top-right',
                                                progressBar: true,
                                                newestOnTop: true,
                                                rtl: isRtl
                                            });
                                    });
                                } else {
                                    toastr['warning'](reject.responseJSON.message,
                                        "{{ __('locale.labels.attention') }}", {
                                            closeButton: true,
                                            positionClass: 'toast-top-right',
                                            progressBar: true,
                                            newestOnTop: true,
                                            rtl: isRtl
                                        });
                                }
                            }
                        })
                    }
                })
            });

            // On save
            Table.delegate(".action-save", "click", function(e) {
                e.stopPropagation();
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ url('campaigns') }}" + '/' + id + '/save',
                    type: "POST",
                    data: {
                        _method: 'POST',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        showResponseMessage(data);
                    },
                    error: function(reject) {
                        if (reject.status === 422) {
                            let errors = reject.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr['warning'](value[0],
                                    "{{ __('locale.labels.attention') }}", {
                                        closeButton: true,
                                        positionClass: 'toast-top-right',
                                        progressBar: true,
                                        newestOnTop: true,
                                        rtl: isRtl
                                    });
                            });
                        } else {
                            toastr['warning'](reject.responseJSON.message,
                                "{{ __('locale.labels.attention') }}", {
                                    positionClass: 'toast-top-right',
                                    containerId: 'toast-top-right',
                                    progressBar: true,
                                    closeButton: true,
                                    newestOnTop: true
                                });
                        }
                    }
                })
            });



        });
    </script>
@endsection

{{-- <div onclick="change($(this));" style="float:right;margin-left:10px;" class="btn btn-primary">${editIcon}</div> --}}
