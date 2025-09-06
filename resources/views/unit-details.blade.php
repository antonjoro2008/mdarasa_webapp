@extends('layouts.app')

@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container accounts mb-12">
    <div class="row">
        <div class="col-sm-9">
            @include('course-unit-details', ['courseUnit' => $courseUnit])
            @include('course-unit-highlights', ['courseUnit' => $courseUnit])
            @include('course-unit-description', ['courseUnit' => $courseUnit])
        </div>
        <div class="col-sm-3">
            @include('lecturer-profile', ['courseUnit' => $courseUnit])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('student.popular')
        </div>
    </div>
</div>
@endsection
