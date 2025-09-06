@extends('layouts.app')

@section('content')
<div>
    @include('admin.main-nav')
</div>
<div class="container accounts mb-12">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.admin-course-unit-details', ['courseUnit' => $courseUnit])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('admin.course-unit-topics', ['courseUnitTopics' => $courseUnitTopics])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('admin.course-unit-highlights', ['courseUnit' => $courseUnit])
            @include('admin.course-unit-description', ['courseUnit' => $courseUnit])
        </div>
    </div>
</div>
@endsection
