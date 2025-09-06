@extends('layouts.app')
@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-6">
            <a href="{{ url('/lecturer/topics') }}">
                <button class="btn red-theme mt-12">Back to Topics</button>
            </a>
        </div>
        <div class="col-sm-6">
            <button type="button" class="btn btn-success pull-right mt-12" onclick="toggleForm('addUnitSubtopicForm')">
                New</button>
        </div>
        <div class="separator mt-8 mb-8"></div>
    </div>
    <div class="row" id="addUnitSubtopicForm">
        <div class="col-sm-12">
            @include('lecturer.unit-subtopic-form', ['unitTopicId' => $selectedUnitTopicId,
            'courseUnitId' => $selectedUnitId])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.unit-subtopics-list', ['unitSubtopics' => $unitSubtopics])
        </div>
    </div>
</div>
<?php $segments = explode('/', $_SERVER['REQUEST_URI']); ?>
@if((!isset($unitSubtopic) || is_null($unitSubtopic))
&& !is_numeric(end($segments)))
<script>
$('#addUnitSubtopicForm').hide();
</script>
@endif
@endsection