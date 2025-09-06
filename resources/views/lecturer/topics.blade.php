@extends('layouts.app')
@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-6">
            <a href="{{ url('/lecturer') }}">
                <button class="btn red-theme mt-12">Back to Units</button>
            </a>
        </div>
        <div class="col-sm-6">
            <button type="button" class="btn btn-success pull-right mt-12" onclick="toggleForm('addUnitTopicForm')">
                New</button>
        </div>
        <div class="separator mt-8 mb-8"></div>
    </div>
    <div class="row" id="addUnitTopicForm">
        <div class="col-sm-12">
            @include('lecturer.unit-topic-form', ['courseUnitId' => $selectedUnitId])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.unit-topics-list', ['unitTopics' => $unitTopics])
        </div>
    </div>
</div>
<?php $segments = explode('/', $_SERVER['REQUEST_URI']); ?>
@if((!isset($unitTopic) || is_null($unitTopic))
&& !is_numeric(end($segments)))
<script>
$('#addUnitTopicForm').hide();
</script>
@endif
@endsection