@extends('admin.layouts.app')

@section('title')
    <title>{{__('Create Card')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:maintenance-cards.index')}}"> {{__('Maintenance Cards')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Maintenance Card')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-car"></i>
                    {{__('Create Card')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                    <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>

						</span>
                </h1>
                <div class="box-content">

                    <form method="post" action="{{route('admin:maintenance-cards.store')}}" class="form">
                        @csrf
                        @method('post')

                        @include('admin.maintenance_card.form')

                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->


@endsection

@section('modals')

@endsection

@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\WorkCards\CreateWorkCardRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection

@section('js')

    <script type="application/javascript" src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
    <script type="application/javascript" src="{{asset('assets/ckeditor/samples/js/sample.js')}}"></script>

    <script type="application/javascript">

        CKEDITOR.replace('editor1', {});

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:maintenance-cards.create')}}" + "?branch_id=" + branch_id;
        }

    </script>

@endsection


