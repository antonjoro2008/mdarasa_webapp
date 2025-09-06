@extends('layouts.app')

@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container accounts">
    @include('student.mobi-sidebar')
    <div class="row">
        <div class="col-sm-12">
            @include('student.myunits-list', ['myUnits' => $myUnits])
        </div>
    </div>
</div>
@endsection
