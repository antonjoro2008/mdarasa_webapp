@extends('layouts.app')

@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container accounts mb-12">
    <div class="row">
        <div class="col-sm-12">
            @include('student.student-course-unit-details', ['courseUnit' => $courseUnit])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('student.course-unit-topics', ['courseUnitTopics' => $courseUnitTopics])
        </div>
    </div>
</div>
@endsection
