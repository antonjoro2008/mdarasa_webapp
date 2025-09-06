@extends('layouts.app')
@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-6">
            <a href="{{ url('/lecturer/topics') }}">
                <button class="btn red-theme mt-12">Back to Subtopics</button>
            </a>
        </div>
        <div class="separator mt-8 mb-8"></div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    @include('lecturer.add-video-form')
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @include('lecturer.videos-list', ['videos' => $videos])
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-12">
                    @include('lecturer.add-notes-form')
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @include('lecturer.notes-list', ['notes' => $notes])
                </div>
            </div>
        </div>
    </div>
</div>

<div class="please-wait" id="please-wait">
    <img src="{{ url('/images/waiting.gif') }}" width="300" alt="" />
</div>
<script>
$('#please-wait').hide()

$('#videoSubmitForm').submit(function() {
    $('#please-wait').show();
    $(this).children('button[type=submit]').prop('disabled', true);
});

$('#notesSubmitForm').submit(function() {
    $('#please-wait').show();
    $(this).children('button[type=submit]').prop('disabled', true);
});
</script>
@endsection
