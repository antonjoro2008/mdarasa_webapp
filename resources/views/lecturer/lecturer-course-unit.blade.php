@extends('layouts.app')

@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts mb-12">
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.lecturer-course-unit-details', ['courseUnit' => $courseUnit])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.course-unit-topics', ['courseUnitTopics' => $courseUnitTopics])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.lecturer-course-unit-highlights', ['courseUnit' => $courseUnit])
            @include('lecturer.lecturer-course-unit-description', ['courseUnit' => $courseUnit])
        </div>
    </div>
</div>
@endsection
