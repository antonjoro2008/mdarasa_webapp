@extends('layouts.app')

@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container">
    <div class="row">
        <div class="d-none-sm col-xs-12">

        </div>
        <div class="col-sm-3">
            @include('student.course-sidebar')
        </div>
        <div class="col-sm-9">
            @include('student.lecturer-unit')
        </div>
    </div>
</div>
@endsection
