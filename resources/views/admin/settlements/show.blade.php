@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Show Settlement') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:settlements.index')}}"> {{__('Settlements')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Show Settlement')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-cube"></i>
                    {{__('Show Settlement')}}
                    <span class="controls hidden-sm hidden-xs pull-left">

							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">
                    @include('admin.settlements.show_form')

                    <a href="{{route('admin:settlements.index')}}"
                               class="btn btn-danger waves-effect waves-light">
                                <i class=" fa fa-reply"></i> {{__('Back')}}
                            </a>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

    <!-- /.row small-spacing -->
@endsection

@section('modals')
    @include('admin.partial.part_image')
@endsection

@section('js-validation')

    <script type="application/javascript">

        function getPartImage(index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

    </script>

@endsection
