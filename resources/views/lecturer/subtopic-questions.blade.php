@extends('layouts.app')
@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-12">
            <a href="{{ url('/lecturer/subtopics')}}">
                <button class="btn red-theme mt-12">Back to Subtopics</button>
            </a>
        </div>
        <div class="separator mt-8 mb-8"></div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.subtopic-questions-list', ['subtopicQuestions' => $subtopicQuestions])
        </div>
    </div>
</div>
@endsection
