@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('Library')}} </title>
@endsection

@section('style')

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
{{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">--}}

    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">

@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('Lockers Transfer')}}</li>
            </ol>
        </nav>

        <div style="height: 600px;">
            <div id="fm"></div>
        </div>

    </div>
@endsection



@section('js')
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
{{--    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>--}}

@endsection
