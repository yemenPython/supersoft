<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{app()->getLocale() == "ar" ? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{url('')}}"/>
{{--    <link href="{{asset('css/app.css')}}" rel="stylesheet">--}}

<!-- Main Styles -->
    <link rel="stylesheet" href="{{asset('assets/styles/style.min.css')}}">
    <link rel="stylesheet" media="print" href="{{asset('assets/styles/style.min.css')}}">

    <!-- mCustomScrollbar -->
    <link rel="stylesheet" href="{{asset('assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.min.css')}}">

    <!-- Waves Effect -->
    <link rel="stylesheet" href="{{asset('assets/plugin/waves/waves.min.css')}}">

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="{{asset('assets/plugin/sweet-alert/sweetalert.css')}}">

    <!-- Percent Circle -->
    <link rel="stylesheet" href="{{asset('assets/plugin/percircle/css/percircle.css')}}">

    <!-- Chartist Chart -->
    <link rel="stylesheet" href="{{asset('assets/plugin/chart/chartist/chartist.min.css')}}">

    <!-- FullCalendar -->
    <link rel="stylesheet" href="{{asset('assets/plugin/fullcalendar/fullcalendar.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/fullcalendar/fullcalendar.print.css')}}" media='print'>

    <!-- Color Picker -->
    <link rel="stylesheet" href="{{asset('assets/color-switcher/color-switcher.min.css')}}">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.3.1/css/hover.css"/>

    <!-- Data Tables -->
    <link rel="stylesheet" href="{{asset('assets/plugin/datatables/media/css/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/plugin/datatables/extensions/Responsive/css/responsive.bootstrap.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/plugin/datatables/extensions/Buttons/css/buttons.bootstrap.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/plugin/datatables/extensions/Buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('assets/plugin/datatables/extensions/Buttons/css/buttons.dataTables.min.css')}}">
    @if(isset($theme))
        <link rel="stylesheet" href="{{ asset('assets/styles/color/'.$theme.'.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/styles/color/dark-blue.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ribbon.css') }}">

    <link rel="stylesheet" href="{{ asset('css/chartist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stock-market-dashboard/style.css') }}">


    <link href="{{ asset('assets/plugin/chart/chartist/chartist.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/stock-market-dashboard/style.css') }}" rel="stylesheet" type="text/css"/>


    <link rel="stylesheet" href="{{ asset('css/overlay.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}">

    <!-- Datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugin/datepicker/css/bootstrap-datepicker.min.css')}}">


    {{-- Flatpicker Buttons CSS --}}
<!-- <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/themes/light.min.css"> -->

    @if (app()->getLocale() == "ar")
        @include('admin.partial.style-ar')
    @endif
    <style>
        div.dt-buttons {
            position: relative;
            float: {{app()->getLocale() == "en" ? ' ' :  'right'}};
            margin-bottom: 18px;
        }

        .dt-print-view {
            height: 100%;
            text-align: center !important;
            background-color: #F8F8F8;
            direction: {{app()->isLocale('ar') ? 'rtl' : 'ltr'}};
            /* font-family: 'Almarai', sans-serif !important */
        }
        .dataTables_length {
            /* position: absolute;
            top: 31.6%;
            right: 40%; */
            margin:0 20px
        }

    </style>


    <!-- Toastr -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Remodal -->
    <link rel="stylesheet" href="{{asset('assets/plugin/modal/remodal/remodal.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugin/modal/remodal/remodal-default-theme.css')}}">

    <!-- Timepicker --><!-- Datepicker -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script type="application/javascript"
        src="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/shortcut-buttons-flatpickr.min.js"></script> -->


    {{--custome css--}}
    <link rel="stylesheet" href="{{asset('custom/css/style.css')}}">

    <link rel="stylesheet" href="{{ asset('accounting-module/tree-view.css') }}"/>
    <link rel="stylesheet" href="{{ asset('accounting-module/arabic-grid.css') }}"/>
    <link rel="stylesheet" href="{{ asset('loader/style.css') }}"/>

    <style>

        .notification {
            background-color: #555;
            color: white;
            text-decoration: none;
            padding: 15px 26px;
            position: relative;
            display: inline-block;
            border-radius: 2px;
        }

        .notification:hover {
            background: red;
        }

        .notification .badge {
            display: inline-block;
            /* min-width: 10px; */
            padding: 5px 7px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            background-color: #ea4335;
            border-radius: 10px;
        }
        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

            background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 150ms infinite linear;
            -moz-animation: spinner 150ms infinite linear;
            -ms-animation: spinner 150ms infinite linear;
            -o-animation: spinner 150ms infinite linear;
            animation: spinner 150ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>


    @yield('style')
</head>

<body>
<div class="loading" id="loaderSearch" style="display: none">Loading&#8230;</div>


@include('admin.partial.sidebar')
<!-- /.main-menu -->

@include('admin.partial.header')
<div id="app">
    <div id="wrapper">

        <input type="hidden" id="auth_id" value="{{auth()->id()}}">
        <input type="hidden" id="realtime_url" value="{{route('admin:notifications.get.real')}}">

        <div class="main-content">

        @yield('content')
        <!-- /.row -->

            <!--  BEGIN FOOTER  -->
        @include('admin.partial.footer')
        <!--  END FOOTER  -->
        </div>
        <!-- /.main-content -->
    </div><!--/#wrapper -->
</div>
@yield('modals')
@yield('accounting-module-modal-area')
@include('admin.global_modals.show_asset')

<script type="application/javascript" src="{{asset('js/app.js')}}"></script>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script type="application/javascript" src="{{asset('assets/script/html5shiv.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/script/respond.min.js')}}"></script>
<![endif]-->
<!--
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script type="application/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/scripts/modernizr.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/plugin/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="application/javascript"
        src="{{asset('assets/plugin/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/plugin/nprogress/nprogress.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/plugin/sweet-alert/sweetalert.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/scripts/sweetalert.init.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/plugin/waves/waves.min.js')}}"></script>
<!-- Full Screen Plugin -->
<script type="application/javascript" src="{{asset('assets/plugin/fullscreen/jquery.fullscreen-min.js')}}"></script>

<!-- Percent Circle -->
<script type="application/javascript" src="{{asset('assets/plugin/percircle/js/percircle.js')}}"></script>

<!-- Google Chart -->
<script type="application/javascript" src="{{asset('https://www.gstatic.com/charts/loader.js')}}"></script>

<!-- Chartist Chart -->
<script type="application/javascript" src="{{asset('assets/plugin/chart/chartist/chartist.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/scripts/chart.chartist.init.min.js')}}"></script>

<!-- Chart.js -->
<script type="application/javascript" src="{{asset('assets/plugin/chart/chartjs/Chart.bundle.min.js')}}"></script>

  <!-- Datepicker -->
  <script src="{{asset('assets/plugin/datepicker/js/bootstrap-datepicker.min.js')}}"></script>


<!-- FullCalendar -->
<script type="application/javascript" src="{{asset('assets/plugin/moment/moment.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/plugin/fullcalendar/fullcalendar.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/scripts/fullcalendar.init.js')}}"></script>


<script type="application/javascript" src="{{asset('assets/scripts/main.min.js')}}"></script>
<script type="application/javascript" src="{{url('assets/color-switcher/color-switcher.min.js')}}"></script>

<!-- Toastr -->
<script type="application/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script type="application/javascript">

    var options = {"closeButton": true, "positionClass": "toast-top-center", "progressBar": true,};
    @if(count($errors))

    toastr.error("{{ $errors->first()}}", '', options);

    @endif

    @if(Session::has('message'))

    var type = "{{Session::get('alert-type','info')}}";

    switch (type) {
        case 'info':
            toastr.info("{{ Session::get('message') }}", '', options);
            break;
        case 'success':
            toastr.success("{{ Session::get('message') }}", '', options);
            break;
        case 'warning':
            toastr.warning("{{ Session::get('message') }}", '', options);
            break;
        case 'error':
            toastr.error("{{ Session::get('message') }}", '', options);
            break;
    }

    @endif

if ($(".datepicker").length)
            $('.datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: "yyyy-mm-dd",
            });

</script>


<!-- Data Tables -->
<script type="application/javascript"
        src="{{asset('assets/plugin/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script type="application/javascript"
        src="{{asset('assets/plugin/datatables/media/js/dataTables.bootstrap.min.js')}}"></script>
<script type="application/javascript"
        src="{{asset('assets/plugin/datatables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
<script type="application/javascript"
        src="{{asset('assets/plugin/datatables/extensions/Responsive/js/responsive.bootstrap.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/scripts/datatables.demo.min.js')}}"></script>
{{-- <script type="application/javascript" src="{{asset('assets/plugin/datatables/extensions/Buttons/js/buttons.bootstrap.min.js')}}"></script>
 <script type="application/javascript" src="{{asset('assets/plugin/datatables/extensions/Buttons/js/buttons.bootstrap4.min.js')}}"></script> --}}
<script type="application/javascript"
        src="{{asset('assets/plugin/datatables/extensions/Buttons/js/buttons.html5.min.js')}}"></script>
<script type="application/javascript"
        src="{{asset('assets/plugin/datatables/extensions/Buttons/js/buttons.print.min.js')}}"></script>
<script type="application/javascript"
        src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.min.js"></script>
<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>
<script type="application/javascript"
        src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
<script type="application/javascript" src="{{asset('js/concat.min.js')}}"></script>
<script type="application/javascript" src="{{asset('js/new-app.js')}}"></script>
<script type="application/javascript" src="{{asset('js/lightbox.min.js')}}"></script>
<script type="application/javascript">
    console.log('you most change this script source by this : src="{{asset('js/lightbox-plus-jquery.min.js')}}"')
</script>

<!-- Validator -->
<script type="application/javascript" type="text/javascript"
        src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

<!-- Remodal -->
<script type="application/javascript" src="{{asset('assets/plugin/modal/remodal/remodal.min.js')}}"></script>

<!-- Select2 -->
<link href="{{asset('select2/style.css')}}" rel="stylesheet"/>
<script type="application/javascript" src="{{asset('select2/script.js')}}"></script>

<!-- Timepicker --><!-- Datepicker -->
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{--cudstom js--}}
<script type="application/javascript" src="{{asset('custom/js/js.js')}}"></script>
<script type="application/javascript" src="{{asset('js/tafqeet.js')}}"></script>

<script type="application/javascript">
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
</script>

<script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

<script type="application/javascript">

    $(document).on('click', '#log_out', function () {
        swal({
            title: "{{__('Logout')}}",
            text: "{{__('Are you sure want to logout?')}}",
            type: "question",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                $("#logout-form").submit();
            }
        });
    });

</script>
{{--for print--}}
<script type="application/javascript" src="{{asset('assets/plugin/print/jQuery.print.min.js')}}"></script>
<script type="application/javascript" src="{{asset('assets/shortcuts.js')}}"></script>
<script type="application/javascript">
    function invoke_datatable(selector, load_at_end_selector, last_child_allowed, withSorting = true) {
        var selector_id = selector.attr("id")
        var page_title = $("title").text()
        $("#" + selector_id).DataTable({
            "bSort": withSorting,
            "language": {
                "url": "{{app()->isLocale('ar')  ? "//cdn.datatables.net/plug-ins/1.10.20/i18n/Arabic.json" :
                                             "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"}}",
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> {{__('Print')}}',
                    autoPrint: false,
                    exportOptions: {
                        columns: last_child_allowed ? ':visible' : ':visible:not(:nth-last-child(-n+2))'
                    },
                    messageTop: `

                        @include("admin.layouts.datatable-print")
                    <h4 class="text-center" style="margin-bottom: 10px">${page_title}</h4>
                    `

                    ,
                    customize: function (win) {
                        $(win.document.head).append("<link href='{{ asset('css/datatable-print-styles.css') }}' rel='stylesheet'/>")
                        $(win.document.body).find("h1:first").remove()
                        if (load_at_end_selector) $(win.document.body).append($(load_at_end_selector).html())
                        win.document.title = page_title
                        setTimeout(function () {
                            win.print()
                        }, 5000)
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-table"></i> {{__('Excel')}}',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa fa-list"></i> {{__('Columns visibility')}}',
                },
            ],
        });
    }
</script>
<script type="application/javascript">
    function server_side_datatable(selector, load_at_end_selector) {
        var page_title = $("title").text()
        $datatable = $('#datatable-with-btns').DataTable({
            serverSide: false,
            responsive: false,
            "iDisplayLength": 25,
            dom: 'Bfrtipl',
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function (data) {
                    data.isDataTable = "true";
                }
            },
            "language": {
                "url": "{{app()->isLocale("ar")  ? url("trans/ar.json") :  url("trans/en.json")}}",
            },
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> {{__('Print')}}',
                    autoPrint: false,
                    exportOptions: {
                        columns: ':visible:not(:nth-last-child(-n+2))',
                        modifier: { page: 'all'},
                    },
                    messageTop: `@include("admin.layouts.datatable-print")<h4 class="text-center" style="margin-bottom: 10px">${page_title}</h4>`
                    ,
                    customize: function (win) {
                        $(win.document.head).append("<link href='{{ asset('css/datatable-print-styles.css') }}' rel='stylesheet'/>")
                        $(win.document.body).find("h1:first").remove()
                        if (load_at_end_selector) $(win.document.body).append($(load_at_end_selector).html())
                        win.document.title = page_title
                        setTimeout(function () {
                            win.print()
                        }, 500)
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-table"></i> {{__('Excel')}}',
                    exportOptions: {
                        columns: ':visible:not(:last-child)'
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa fa-list"></i> {{__('Columns visibility')}}',
                },
            ],
            aoColumns: [
                    @if(isset($js_columns))
                    @foreach($js_columns as $key=> $row)
                    @if($key == 'action' || $key=='DT_RowIndex' || $key=='options')
                {
                    mData: "{{$key}}", name: "{{$row}}", orderable: false, searchable: false
                },
                    @else
                {
                    mData: "{{$key}}", name: "{{$row}}"
                },
                @endif
                @endforeach
                @endif
            ],
            pageLength: 25,
            lengthMenu: [ [25 , 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "{{__('All')}}"] ],
            "lengthChange": true,
        });
        //please any body see this comment "don't delete it"
        {{--$datatable.on( 'length', function (e,settings,len ) {--}}
        {{--    --}}{{--var url = '{{url()->full()}}?&filter=';--}}
        {{--    --}}{{--$datatable.ajax.url(url).load();--}}
        {{--} );--}}
    }
</script>

<script type="application/javascript">
    function print_element(element_id, page_title) {
        var custom_styles = [
            '{{ asset('assets-ar/Design/css/bootstrap.min.css') }}',
            'https://fonts.googleapis.com/css2?family=Almarai&display=swap',
            '{{asset('assets/styles/style.min.css')}}',
            '{{ asset("custom/css/style.css") }}',
            '{{ asset('css/modal-print-styles.css') }}'
        ]
        @if(app()->getLocale() == "ar")
        custom_styles.push("{{asset('assets-ar/styles/style-rtl.css')}}")
        @endif
        var custom_style_codes = ""
        for (i = 0; i < custom_styles.length; i++) {
            custom_style_codes += `<link href="${custom_styles[i]}" rel="stylesheet"/>`
        }
        var element_html = document.getElementById(element_id).innerHTML
        var html_code = `
            <html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                <head>
                    <title>${page_title}</title>
                    ${custom_style_codes}
                    <style>
                        html{direction:{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}}
                        table{ direction:{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};float:{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} }
                        .col-xs-12, .col-xs-4, .col-xs-6, .col-md-12, .col-md-4 ,.col-md-6, .col-sm-12, .col-sm-4, .col-sm-6 {
                            float:{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}
        }
    </style>
</head>
<body>



${element_html}
                </body>
            </html>
        `

        var printer_win = window.open('', 'Print-Window')
        printer_win.document.write(html_code)
        printer_win.document.close()
        printer_win.focus()
        setTimeout(function () {
            printer_win.print()
            printer_win.close()
        }, 3000)
    }

    function change_user_accounts() {
        var branch = $('select[name="branch_id"] option:selected').val()
        var choosed_acc = $('select[name="user_account_id"] option:selected').val()
        var option = $("select[name='user_account_type'] option:selected")
        if (!option.val()) {
            $('select[name="user_account_id"]').html("<option value=''> {{ __('Select One') }} </option>")
            $(".select2").select2()
        } else {
            $.ajax({
                dataType: 'json',
                type: 'GET',
                data: {
                    branch_id: branch,
                    choosed_acc: choosed_acc
                },
                url: option.data("url"),
                success: function (response) {
                    $('select[name="user_account_id"]').html(response.options)
                },
                error: function (err) {
                    swal("{{ __('Error') }}", err.response.data.message, "error")
                }
            })
        }
    }
</script>

@yield('js')
@yield('js-validation')
@yield('js-print')
@yield('js-ajax')
@yield('js-car')
<script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>
{{-- i build this function to solve delete selected issue --}}
<script type="application/javascript">

    function force_delete_selected(route, for_reusable, archived = false, restore = false, forcDelete = false) {
        var ids_inputs = ""
        $("form[id='deleteSelected']").each(function () {
            if ($(this).find("input[type='checkbox']").prop("checked")) {
                var id_value = $(this).find("input[type='checkbox']").val()
                ids_inputs += "<input type='hidden' name='ids[]' value='" + id_value + "'>"
                if (archived) {
                    ids_inputs += "<input type='hidden' name='archive' value='" + 'archive' + "'>"
                }
                if (restore) {
                    ids_inputs += "<input type='hidden' name='restore' value='" + 'restore' + "'>"
                }
                if (forcDelete) {
                    ids_inputs += "<input type='hidden' name='forcDelete' value='" + 'forcDelete' + "'>"
                }
            }
        })
        if (for_reusable) return ids_inputs
        var form_html = "<form style='display:none' action='" + route + "' method='post' id='generated-form'>"
        form_html += '{{ csrf_field() }}'
        form_html += ids_inputs
        form_html += "</form>"
        $("body").append(form_html)
        $("#generated-form").submit()
    }

    function confirmDelete(id) {
        event.preventDefault()
        swal({
            title: "{{__('Delete')}}",
            text: "{{__('Are you sure want to Delete?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById("confirmDelete" + id).submit();

                {{--swal("{{__('successfully')}}", "{{__('Deleted Successfully')}}", "success", {--}}

                {{--    buttons: {--}}
                {{--        confirm: {--}}
                {{--            text: "{{__('Ok')}}",--}}
                {{--        },--}}
                {{--    }--}}
                {{--});--}}
            }
        });
    }

    function confirmDeleteSelected(route) {
        event.preventDefault()
        swal({
            title: "{{__('Delete Selected')}}",
            text: "{{__('Are you sure want to Delete Data Selected?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                var ids_inputs = force_delete_selected(route, true)
                $("#deleteSelected").append(ids_inputs)
                $("#deleteSelected").submit()
            }
        });
    }

    function archiveConfirmation(route, type) {
        console.log(route)
        event.preventDefault()
        switch (type) {
            case 'deleteSelected':
                swal({
                    title: "{{__('Delete Selected')}}",
                    text: "{{__('Are you sure want to Delete Data Selected?')}}",
                    type: "success",
                    buttons: {
                        confirm: {
                            text: "{{__('Ok')}}",
                        },
                        cancel: {
                            text: "{{__('Cancel')}}",
                            visible: true,
                        }
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        var ids_inputs = force_delete_selected(route, true, false, false, true)
                        $("#deleteSelected").append(ids_inputs)
                        $("#deleteSelected").submit()
                    }
                });
                break;
            case 'archiveSelected':
                swal({
                    title: "{{__('Archive Selected')}}",
                    text: "{{__('Are you sure want to Archive Data Selected?')}}",
                    type: "success",
                    buttons: {
                        confirm: {
                            text: "{{__('Ok')}}",
                        },
                        cancel: {
                            text: "{{__('Cancel')}}",
                            visible: true,
                        }
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        var ids_inputs = force_delete_selected(route, true, true)
                        $("#deleteSelected").append(ids_inputs)
                        $("#deleteSelected").submit()
                    }
                });
                break;
            case 'restoreSelected':
                swal({
                    title: "{{__('Restore Selected')}}",
                    text: "{{__('Are you sure want to Restore Data Selected?')}}",
                    type: "success",
                    buttons: {
                        confirm: {
                            text: "{{__('Ok')}}",
                        },
                        cancel: {
                            text: "{{__('Cancel')}}",
                            visible: true,
                        }
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        var ids_inputs = force_delete_selected(route, true, false, true)
                        $("#deleteSelected").append(ids_inputs)
                        $("#deleteSelected").submit()
                    }
                });
                break;
            default:
        }
    }

    function confirmRestoreData(id) {
        event.preventDefault()
        swal({
            title: "{{__('Restore')}}",
            text: "{{__('Are you sure want to Restore Data?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById("confirmRestoreData" + id).submit();
            }
        });
    }

    $(document).ready(function () {
        var flatpickr_plugins = [
            ShortcutButtonsPlugin({
                button: [
                    {
                        label: "Today"
                    }
                ],
                label: "or",
                onClick: (index, fp) => {
                    let date = new Date();
                    fp.setDate(date);
                    fp.close()
                }
            })
        ]
        $('input[type="date"]').flatpickr({plugins: flatpickr_plugins})
        @if(isset($_GET['date_from']) && $_GET['date_from'] != '')
        $('input[name="date_from"]').flatpickr({defaultDate: "{{ $_GET['date_from'] }}", plugins: flatpickr_plugins})
        @endif
        @if(isset($_GET['date_to']) && $_GET['date_to'] != '')
        $('input[name="date_to"]').flatpickr({defaultDate: "{{ $_GET['date_to'] }}", plugins: flatpickr_plugins})
        @endif

        @if(isset($_GET['dateFrom']) && $_GET['dateFrom'] != '')
        $('input[name="dateFrom"]').flatpickr({defaultDate: "{{ $_GET['dateFrom'] }}", plugins: flatpickr_plugins})
        @endif
        @if(isset($_GET['dateTo']) && $_GET['dateTo'] != '')
        $('input[name="dateTo"]').flatpickr({defaultDate: "{{ $_GET['dateTo'] }}", plugins: flatpickr_plugins})
        @endif
    })
    shortcut.add("F1", function () {
        $("#btnsave").click()
    });

    shortcut.add("F2", function () {
        $("#reset").click()
    });

    shortcut.add("F3", function () {
        $("#back").click()
    });

    $(document).ready(function () {
        var root_ul_classes = "current active open", li_class = "current"
        var href_value = "{{ url('/') . '/' . request()->segment(2) .'/'. request()->segment(3) }}"
        var _href_value = "{{ url('/') . '/' . request()->segment(1) . '/' . request()->segment(2) .'/'. request()->segment(3) }}"

        var selector = $('ul.menu a[href="' + href_value + '"]'),
            __selector = $('ul.menu  a[href="' + _href_value + '"]')

        if (selector.length) {
            selector.parent().addClass(li_class)
            selector.parent().parent().parent().addClass(root_ul_classes)
        }

        if (__selector.length) {
            __selector.parent().addClass(li_class)
            __selector.parent().parent().parent().addClass(root_ul_classes)
        }
        $('select').select2()
    })

    function confirmAddToArchive(id) {
        event.preventDefault()
        swal({
            title: "{{__('Add To Archive')}}",
            text: "{{__('Are you sure want to Archive?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById("archiveData" + id).submit();
            }
        });
    }

    function confirmForceDelete(id) {
        event.preventDefault()
        swal({
            title: "{{__('Force Delete')}}",
            text: "{{__('Are you sure want to Force Delete?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById("confirmForceDelete" + id).submit();
            }
        });
    }

    function confirmForceDeleteUser(id) {
        event.preventDefault()
        swal({
            title: "{{__('Force Delete')}}",
            text: "{{__('Are you sure want to Force Delete?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                let authId = '{{\Illuminate\Support\Facades\Auth::id()}}';
                if (id === 1 || authId === id) {
                    swal("{{__('error')}}", "{{__('words.cant-delete-admin')}}", "error", {

                        buttons: {
                            confirm: {
                                text: "{{__('Ok')}}",
                            },
                        }
                    });
                    return false;
                }
                document.getElementById("confirmForceDelete" + id).submit();
            }
        });
    }

    function restoreAllData() {
        event.preventDefault()
        swal({
            title: "{{__('Restore All Data')}}",
            text: "{{__('Are you sure want to Restore All Data?')}}",
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById("restoreAllData").submit();
            }
        });
    }
</script>

<script type="application/javascript">

    @if(Session::has('authorization'))
    swal({
        title: "Authorization!",
        text: "sorry, Not Allowed!",
        icon: "error",
        button: "Ok",
    });
    @endif

    //to reset the form of the search
    $("#resetWithAllResult").on('click', function (e) {
        e.preventDefault();
        let searchForm = $(this).closest("form")[0];
        let btnSubmit = $(this).siblings("button")[0];
        searchForm.reset();
        resetSelect2(searchForm);
        btnSubmit.click();
    })

    $("#onlyReset").on('click', function (e) {
        e.preventDefault();
        let searchForm = $(this).closest("form")[0];
        searchForm.reset();
       resetSelect2(searchForm);
    })

    function resetSelect2(from_fields) {
        $(from_fields).find("select").each(function (index, item) {
            $(this).selectedIndex = 0;
            $(this).val(null).trigger("change");
        });
    }

    function openModalWithShowAsset(assetId) {
        event.preventDefault();
        $.ajax({
            url: '{{url('admin/assets/assets_details')}}' + '/' + assetId,
            type: 'get',
            success: function (response) {
                $('#showAssetModal').modal('show');
                $('#showAssetResponse').html(response.data);
            }
        });
    }

    function confirmAction(route, message = '') {
        event.preventDefault()
        swal({
            title: "{{__('Confirmation')}}",
            text: message,
            type: "success",
            buttons: {
                confirm: {
                    text: "{{__('Ok')}}",
                },
                cancel: {
                    text: "{{__('Cancel')}}",
                    visible: true,
                }
            }
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.location.href = route;
            }
        });
    }

    function modalDatatable(selector_id) {
        $("#" + selector_id).DataTable({
            "paging": false,
            "language": {
                "url": "{{app()->isLocale('ar')  ? "//cdn.datatables.net/plug-ins/1.10.20/i18n/Arabic.json" :
                                             "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"}}",
            },
        });
    }

    function alertWithMsg(message) {
        swal({
            title: '{{__('warning')}}',
            text: message,
            icon: "warning",
            reverseButtons: false,
            buttons: {
                cancel: {
                    text: "{{ __('words.ok') }}",
                    className: "btn btn-primary",
                    value: null,
                    visible: true
                }
            }
        })
    }
</script>

<script type="application/javascript" src="{{asset('js/dark_mode.js')}}"></script>
@yield('accounting-scripts')

@include('admin.layouts.select_2_scripts')

</body>
</html>


