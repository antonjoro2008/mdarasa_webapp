@extends('layouts.app')

@section('content')
<div>
    @include('admin.main-nav')
</div>
<div class="mt-12 card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-10 center-flex">
                Topic Details
            </div>
            <div class="col-sm-2">
                <a class="nav-link inline-view pull-right" href="{{ url('admin/course/unit') }}/{{ $courseUnitId}}">
                    <button type="button" class="publish-btn inline-view button-pd">Back to Topics</button>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                @if(!is_null($previousTopic))
                <form method="get" action="{{ url('/admin/topic/notes') }}" class="inline-view">
                    @csrf
                    <input type="hidden" name="courseUnitId" value="{{ $previousTopic->courseUnitId }}" />
                    <input type="hidden" name="topicId" value="{{ $previousTopic->courseUnitTopicId }}" />
                    <input type="hidden" name="topicNumber" value="{{ $previousTopic->topicNumber }}" />
                    <input type="hidden" name="topicName" value="{{ $previousTopic->topicName }}" />
                    <input type="hidden" name="topicNotes" value="{{ $previousTopic->topicNotes }}" />
                    <button type="submit" class="prev-btn">
                        << Prev. Topic </button>
                </form>
                @endif
            </div>
            <div class="col-md-5 unit-details center-flex">
                <span class="title-color">#{{ $topicNumber }}</span>.&nbsp; {{ $topicName }}
            </div>
            <div class="col-md-2">
                @if(!is_null($nextTopic))
                <form method="get" action="{{ url('/admin/topic/notes') }}" class="inline-view">
                    @csrf
                    <input type="hidden" name="courseUnitId" value="{{ $nextTopic->courseUnitId }}" />
                    <input type="hidden" name="topicId" value="{{ $nextTopic->courseUnitTopicId }}" />
                    <input type="hidden" name="topicNumber" value="{{ $nextTopic->topicNumber }}" />
                    <input type="hidden" name="topicName" value="{{ $nextTopic->topicName }}" />
                    <input type="hidden" name="topicNotes" value="{{ $nextTopic->topicNotes }}" />
                    <button type="submit" class="next-btn">
                        Next Topic >>
                    </button>
                </form>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 mt-12">
                <div class="center-flex">
                    <iframe
                        src="https://docs.google.com/gview?url={{ $serverUrl }}/topic/download/{{ $topicNotes }}&embedded=true"
                        style="width:95%;height:500px;background:#202020 !important;" frameborder="0">
                    </iframe>
                </div>
                @if(!is_null($courseUnitTopics))
                <div class="title-color mt-12">Related Topic Notes</div>
                <div class="separator"></div>
                <div class="row mt-12">
                    @foreach($courseUnitTopics as $topic)
                    <div class="col-sm-3 flex">
                        <div class="mr-8">
                            {{ $topic->topicNumber }}.
                        </div>
                        <div class="full-width">
                            <form method="get" action="{{ url('/admin/topic/notes') }}" class="inline-view">
                                @csrf
                                <input type="hidden" name="courseUnitId" value="{{ $topic->courseUnitId }}" />
                                <input type="hidden" name="topicId" value="{{ $topic->courseUnitTopicId }}" />
                                <input type="hidden" name="topicNumber" value="{{ $topic->topicNumber }}" />
                                <input type="hidden" name="topicName" value="{{ $topic->topicName }}" />
                                <input type="hidden" name="topicNotes" value="{{ $topic->topicNotes }}" />
                                <button type="submit" class="related-topic-btn flex pt-8 full-width">
                                    {{ $topic->topicName }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="col-md-3 mt-12">
                @if(!empty($subtopics))
                <div class="row mt-12">
                    <div class="title-color txt-align-center">Subtopics</div>
                    <div class="separator mb-12"></div>
                    @foreach($subtopics as $subtopic)
                    @if(!is_null($subtopic->subtopicNotes))
                    <div class="col-sm-12 flex">
                        <div class="mr-8">
                            {{ $subtopic->subtopicNumber }}.
                        </div>
                        <div class="full-width">
                            <form method="get" action="{{ url('/admin/subtopic/notes') }}" class="inline-view">
                                @csrf
                                <input type="hidden" name="courseUnitId" value="{{ $subtopic->courseUnitId }}" />
                                <input type="hidden" name="topicId" value="{{ $subtopic->unitTopicId }}" />
                                <input type="hidden" name="subTopicId" value="{{ $subtopic->courseUnitSubtopicId }}" />
                                <input type="hidden" name="subtopicNumber" value="{{ $subtopic->subtopicNumber }}" />
                                <input type="hidden" name="subtopicName" value="{{ $subtopic->subtopicName }}" />
                                <input type="hidden" name="subTopicNotes" value="{{ $subtopic->subtopicNotes }}" />
                                <button type="submit" class="related-topic-btn flex pt-8 full-width"
                                    style="min-height:70px !important;">
                                    {{ $subtopic->subtopicNumber }}. {{ $subtopic->subtopicName }}
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="separator mt-8"></div>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
