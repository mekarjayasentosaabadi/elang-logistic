    <script>
        var listRoutes = JSON.parse('{{ json_decode(listRoutes()) }}')
    </script>
    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('assets') }}/app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('assets') }}/app-assets/js/core/app-menu.js"></script>
    <script src="{{ asset('assets') }}/app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->


    <script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{{ asset('assets') }}/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js"></script>

    <script src="{{ asset('assets') }}/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('assets') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @include('sweetalert::alert')
    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
            // ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        })
    </script>
