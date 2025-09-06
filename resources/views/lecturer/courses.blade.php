@extends('layouts.app')
@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-success pull-right mt-12" onclick="toggleForm('addCourseForm')">
                New
            </button>
        </div>
        <div class="separator mt-8 mb-8"></div>
    </div>
    <div class="row" id="addCourseForm">
        <div class="col-sm-12">
            @include('lecturer.course-form')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.courses-list', ['courses' => $courses])
        </div>
    </div>
</div>
@if(!isset($course) || is_null($course))
<script>
$('#addCourseForm').hide();
</script>
@endif
@endsection