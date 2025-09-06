@extends('layouts.app')

@section('content')
<div>
    <div class="row">
        <div class="col-sm-12">
            <a class="navbar-brand" href="/">
                <img src="{{ url('/images/logo.png')}}" class="logo mt-12" alt="MDARASA">
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('admin.login')
        </div>
    </div>
</div>
@endsection
