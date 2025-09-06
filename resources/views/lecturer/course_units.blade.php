@extends('layouts.app')
@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-success pull-right mt-12" onclick="toggleForm('addCourseUnitForm')">
                New
            </button>
        </div>
        <div class="separator mt-8 mb-8"></div>
    </div>
    <div class="row" id="addCourseUnitForm">
        <div class="col-sm-12">
            @include('lecturer.course-unit-form', ['courseId' => $courseId])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.course-units-list', ['courseUnits' => $courseUnits])
        </div>
    </div>
</div>
<?php $segments = explode('/', $_SERVER['REQUEST_URI']); ?>
@if((!isset($courseUnit) || is_null($courseUnit))
&& !is_numeric(end($segments)))
<script>
$('#addCourseUnitForm').hide();
</script>
@endif
@endsection