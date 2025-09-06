@extends('layouts.app')

@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container">
    <div class="row">
        <div class="d-sm-none col-12"></div>
        <div class="col-sm-3">
            @include('student.course-sidebar')
        </div>
        <div class="col-sm-9">
            @include('student.units')
        </div>
    </div>
</div>
@endsection
