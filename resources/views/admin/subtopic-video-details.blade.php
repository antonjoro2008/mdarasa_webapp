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
        @if(!empty($videos))
        <div class="row">
            <div class="col-md-9 mt-12">
                @foreach($videos as $video)
                <div class="row mb-8">
                    <div class="col-md-12"><span class="title-color">#</span> {{ $video->videoNumber }}.
                        {{ $video->videoTitle }}</div>
                </div>
                <div id="container">
                    <video class='plyr-video' controls crossorigin playsinline
                        data-poster="{{ url('/images/videos-placeholder-student.jpg') }}" id="player">

                        @if($video->fileDomiciledAt == "Local")
                        <source src="/topic/{{ $video->subtopicVideo }}">
                        @else
                        <source
                            src="{{ strpos($video->subtopicVideo, 'https://') !== false?$video->subtopicVideo:'https://'.$video->subtopicVideo }}">
                        @endif

                        <track kind="captions" label="English" srclang="en"
                            src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt" default />
                        <track kind="captions" label="Français" srclang="fr"
                            src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt" />
                    </video>
                </div>
                <div class="separator"></div>
                @endforeach
            </div>
            <div class="col-md-3 mt-12">
                <div class="title-color">All Subtopics</div>
                <div class="separator"></div>
                @if(!empty($subtopics))
                <div class="row mt-12">
                    @foreach($subtopics as $subtopic)
                    <div class="col-sm-12 flex">
                        <div>
                            <form method="get" action="{{ url('/admin/subtopic/content') }}" class="inline-view">
                                @csrf
                                <input type="hidden" name="courseUnitId" value="{{ $subtopic->courseUnitId }}" />
                                <input type="hidden" name="topicId" value="{{ $subtopic->unitTopicId }}" />
                                <input type="hidden" name="subTopicId" value="{{ $subtopic->courseUnitSubtopicId }}" />
                                <input type="hidden" name="subtopicNumber" value="{{ $subtopic->subtopicNumber }}" />
                                <input type="hidden" name="subtopicName" value="{{ $subtopic->subtopicName }}" />
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
        @else
        <div class="row">
            <div class="col-md-12 mt-12">
                No videos on this topic yet
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
