@extends('layouts.app')

@section('content')
<div>
    @include('admin.main-nav')
</div>
<div class="mt-12 card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-10 center-flex">
                <span class="title-color">#{{ $subtopicNumber }}</span>.&nbsp; {{ $subtopicName }}
            </div>
            <div class="col-sm-2">
                <a class="nav-link inline-view pull-right" href="{{ url('/admin/course/unit') }}/{{ $courseUnitId}}">
                    <button type="button" class="publish-btn inline-view button-pd">Back to Topics</button>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-9 unit-details mt-12">
                @foreach($notes as $notesRecord)
                <div class="row mb-8">
                    <div class="col-sm-12"><span class="title-color"></span> {{ $notesRecord->notesNumber }}.
                        {{ $notesRecord->notesTitle }}</div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        @if($notesRecord->fileDomiciledAt == "Local")
                        <iframe src="/topics/{{ $notesRecord->subtopicNotes }}#toolbar=0"
                            style="width:95%;height:500px;background:#202020 !important;">
                        </iframe>
                        @else
                        <iframe src="https://{{ $notesRecord->subtopicNotes }}#toolbar=0"
                            style="width:95%;height:500px;background:#202020 !important;">
                        </iframe>
                        @endif
                    </div>
                </div>
                <div class="separator"></div>
                @endforeach
            </div>
            <div class="col-md-3 unit-details mt-12">
                @if(!empty($subtopics))
                <div class="row mt-12">
                    <div class="title-color txt-align-center">All Subtopics</div>
                    <div class="separator mb-12"></div>
                    @foreach($subtopics as $subtopic)
                    <div class="col-sm-12 flex">
                        <div>
                            <form method="get" action="{{ url('/admin/subtopic/notes') }}" class="inline-view">
                                @csrf
                                <input type="hidden" name="courseUnitId" value="{{ $subtopic->courseUnitId }}" />
                                <input type="hidden" name="topicId" value="{{ $subtopic->unitTopicId }}" />
                                <input type="hidden" name="subTopicId" value="{{ $subtopic->courseUnitSubtopicId }}" />
                                <input type="hidden" name="subtopicNumber" value="{{ $subtopic->subtopicNumber }}" />
                                <input type="hidden" name="topicName" value="{{ $subtopic->topicName }}" />
                                <button type="submit" class="flex pt-8 full-width subtopic-btn mb-12">
                                    {{ $subtopic->subtopicNumber }}. {{ $subtopic->subtopicName }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection