@extends('layouts.app')
@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container accounts">
    @include('student.mobi-sidebar')
    <div class="row">
        <div class="col-6">
            <a href="{{ url('/course/student/unit')}}/{{ $courseUnitId }}">
                <button class="btn red-theme mt-12">Back to Subtopics</button>
            </a>
        </div>
        <div class="col-6">
            <button type="button" class="btn btn-success pull-right mt-12"
                onclick="toggleForm('addSubtopicQuestionForm')">
                New</button>
        </div>
        <div class="separator mt-8 mb-8"></div>
    </div>
    <div class="row" id="addSubtopicQuestionForm">
        <div class="col-sm-12">
            @include('student.subtopic-question-form')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('student.subtopic-questions-list', ['subtopicQuestions' => $subtopicQuestions])
        </div>
    </div>
</div>
@if(!isset($subtopicQuestion) || is_null($subtopicQuestion))
<script>
$('#addSubtopicQuestionForm').hide();
</script>
@endif
@endsection
