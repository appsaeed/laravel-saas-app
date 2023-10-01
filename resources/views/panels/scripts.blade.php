<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset(mix('vendors/js/ui/jquery.sticky.js')) }}"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>

<!-- custom scripts file for user -->
<script src="{{ asset(mix('js/core/scripts.js')) }}"></script>

<!-- END: Theme JS-->


<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script>
    $(document).ready(function() {
        "use strict";
        //show response message
        window.showResponseMessage = function(data, dataListView = null) {

            if (data.status === 'success') {
                toastr['success'](data.message, '{{ __('locale.labels.success') }}!!', {
                    closeButton: true,
                    positionClass: 'toast-top-right',
                    progressBar: true,
                    newestOnTop: true,
                    rtl: isRtl
                });
                if (dataListView) dataListView.draw();
            } else if (data.status === 'error') {
                toastr['warning'](data.message, '{{ __('locale.labels.attention') }}', {
                    closeButton: true,
                    positionClass: 'toast-top-right',
                    progressBar: true,
                    newestOnTop: true,
                    rtl: isRtl
                });
            } else if (data.status) {
                toastr['warning'](data.message, '{{ __('locale.labels.attention') }}', {
                    closeButton: true,
                    positionClass: 'toast-top-right',
                    progressBar: true,
                    newestOnTop: true,
                    rtl: isRtl
                });
                if (dataListView) dataListView.draw();
            } else {
                toastr['warning']("{{ __('locale.exceptions.something_went_wrong') }}",
                    '{{ __('locale.labels.warning') }}!', {
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        progressBar: true,
                        newestOnTop: true,
                        rtl: isRtl
                    });
            }
        }

        //show error message
        window.showResponseError = function(reject) {
            try {
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
                            progressBar: true,
                            newestOnTop: true,
                            rtl: isRtl
                        });
                }
            } catch (error) {
                var message = String(error);
                toastr['error'](message,
                    "{{ __('locale.labels.attention') }}", {
                        positionClass: 'toast-top-right',
                        progressBar: true,
                        newestOnTop: true,
                        rtl: isRtl
                    });
            }
        }
    });
    let isRtl = $('html').attr('data-textdirection') === 'rtl';
</script>

{{-- page script --}}
@yield('page-script')
<script>
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });
</script>

@if (Session::has('message'))
    <script>
        let type = "{{ Session::get('status', 'success') }}";
        switch (type) {
            case 'info':
                toastr['info']("{!! Session::get('message') !!}", '{{ __('locale.labels.information') }}!', {
                    closeButton: true,
                    positionClass: 'toast-top-right',
                    progressBar: true,
                    newestOnTop: true,
                    rtl: isRtl
                });

                break;

            case 'warning':
                toastr['warning']("{!! Session::get('message') !!}", '{{ __('locale.labels.warning') }}!', {
                    closeButton: true,
                    positionClass: 'toast-top-right',
                    progressBar: true,
                    newestOnTop: true,
                    rtl: isRtl
                });
                break;

            case 'success':
                toastr['success']("{!! Session::get('message') !!}", '{{ __('locale.labels.success') }}!!', {
                    closeButton: true,
                    positionClass: 'toast-top-right',
                    progressBar: true,
                    newestOnTop: true,
                    rtl: isRtl
                });
                break;

            case 'error':
                toastr['error']("{!! Session::get('message') !!}", '{{ __('locale.labels.ops') }}..!!', {
                    closeButton: true,
                    positionClass: 'toast-top-right',
                    progressBar: true,
                    newestOnTop: true,
                    rtl: isRtl
                });
                break;
        }
    </script>
@endif
