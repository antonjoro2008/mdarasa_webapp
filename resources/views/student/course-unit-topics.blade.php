<div class="mt-12 card">
    <div class="card-header">Topics Covered</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 d-none d-sm-block"><span class="title-color">Unit Code</span></div>
            <div class="col-md-3 d-none d-sm-block"><span class="title-color">Course Unit</span></div>
            <div class="col-md-1 d-none d-sm-block"><span class="title-color">Topic #</span></div>
            <div class="col-md-5 d-none d-sm-block"><span class="title-color">Topic Name</span></div>
        </div>
        <div class="separator d-none d-sm-block"></div>
        @if(!is_null($courseUnitTopics) && !empty($courseUnitTopics))
        @foreach($courseUnitTopics as $courseUnitTopic)
        <div class="row">
            <div class="col-md-3 d-none d-sm-block"> {{ $courseUnitTopic->courseUnitCode }} </div>
            <div class="col-12 d-sm-none mt-12 bd-modi-unit pt-8"> <span class="title-color"> Unit Code:
                </span>{{ $courseUnitTopic->courseUnitCode }}
            </div>
            <div class="col-md-3 d-none d-sm-block"> {{ $courseUnitTopic->courseUnitName }} </div>
            <div class="col-12 d-sm-none"> <span class="title-color"> Course Unit:
                </span>{{ $courseUnitTopic->courseUnitName }}
            </div>
            <div class="col-md-1 d-none d-sm-block"> {{ $courseUnitTopic->topicNumber }} </div>
            <div class="col-12 d-sm-none"> <span class="title-color"> Topic Number:
                </span>{{ $courseUnitTopic->topicNumber }}
            </div>
            <div class="col-md-5 d-none d-sm-block"> {{ $courseUnitTopic->topicName }} </div>
            <div class="col-12 d-sm-none"> <span class="title-color"> Topic Name:
                </span>{{ $courseUnitTopic->topicName }}
            </div>
        </div>
        <div class="row">
            <div class="d-sm-none col-12 pv-12">
                <span class="mobi-subtopics">Subtopics</span>
            </div>
        </div>
        @if(!empty($courseUnitTopic->courseUnitSubtopics))
        <div class="row" style="background:#202020;">
            <div class="col-sm-12">
                <div class="row" style="background: #383838 !important;">
                    <div class="col-md-6 d-none d-sm-block"><span class="title-color">Subtopic</span></div>
                    <div class="col-md-6 d-none d-sm-block"><span class="title-color pull-right">Notes, Videos and
                            Questions</span>
                    </div>
                </div>
                @foreach($courseUnitTopic->courseUnitSubtopics as $unitSubtopic)
                <div class="row">
                    <div class="col-md-6 d-none d-sm-block">
                        {{ $unitSubtopic->subtopicNumber }}.
                        {{ $unitSubtopic->subtopicName }}
                    </div>
                    <div class="col-12 d-sm-none">
                        {{ $unitSubtopic->subtopicNumber }}.
                        {{ $unitSubtopic->subtopicName }}
                    </div>
                    <div class="col-md-6 d-none d-sm-block">
                        <a
                            href="{{ url('/unit/subtopic/questions/') }}/{{ $unitSubtopic->courseUnitId }}/{{ $unitSubtopic->courseUnitSubtopicId }}">
                            <button class="edit-btn pull-right">My Questions</button>
                        </a>
                        <form method="get" action="{{ url('/student/subtopic/notes') }}"
                            class="inline-view pull-right mr-8">
                            @csrf
                            <input type="hidden" name="courseUnitId" value="{{ $unitSubtopic->courseUnitId }}" />
                            <input type="hidden" name="topicId" value="{{ $unitSubtopic->unitTopicId }}" />
                            <input type="hidden" name="subtopicId" value="{{ $unitSubtopic->courseUnitSubtopicId }}" />
                            <input type="hidden" name="subtopicNumber" value="{{ $unitSubtopic->subtopicNumber }}" />
                            <input type="hidden" name="subtopicName" value="{{ $unitSubtopic->subtopicName }}" />
                            <button type="submit" class="publish-btn inline-view">Subtopic Notes</button>
                        </form>
                        <form method="get" action="{{ url('/student/subtopic/content') }}"
                            class="inline-view pull-right mr-8">
                            @csrf
                            <input type="hidden" name="courseUnitId" value="{{ $unitSubtopic->courseUnitId }}" />
                            <input type="hidden" name="topicId" value="{{ $unitSubtopic->unitTopicId }}" />
                            <input type="hidden" name="subtopicId" value="{{ $unitSubtopic->courseUnitSubtopicId }}" />
                            <input type="hidden" name="subtopicNumber" value="{{ $unitSubtopic->subtopicNumber }}" />
                            <input type="hidden" name="subtopicName" value="{{ $unitSubtopic->subtopicName }}" />
                            <button type="submit" class="unpublish-btn">Subtopic Videos</button>
                        </form>
                    </div>
                    <div class="separator d-sm-none col-12 mt-8 mb-8"></div>
                    <div class="col-12 d-sm-none">
                        <a
                            href="{{ url('/unit/subtopic/questions/') }}/{{ $unitSubtopic->courseUnitId }}/{{ $unitSubtopic->courseUnitSubtopicId }}">
                            <button class="edit-btn">Questions</button>
                        </a>
                        <form method="get" action="{{ url('/student/subtopic/notes') }}" class="inline-view mr-8">
                            @csrf
                            <input type="hidden" name="courseUnitId" value="{{ $unitSubtopic->courseUnitId }}" />
                            <input type="hidden" name="topicId" value="{{ $unitSubtopic->unitTopicId }}" />
                            <input type="hidden" name="subtopicId" value="{{ $unitSubtopic->courseUnitSubtopicId }}" />
                            <input type="hidden" name="subtopicNumber" value="{{ $unitSubtopic->subtopicNumber }}" />
                            <input type="hidden" name="subtopicName" value="{{ $unitSubtopic->subtopicName }}" />
                            <button type="submit" class="publish-btn inline-view">Notes</button>
                        </form>
                        <form method="get" action="{{ url('/student/subtopic/content') }}" class="inline-view mr-8">
                            @csrf
                            <input type="hidden" name="courseUnitId" value="{{ $unitSubtopic->courseUnitId }}" />
                            <input type="hidden" name="topicId" value="{{ $unitSubtopic->unitTopicId }}" />
                            <input type="hidden" name="subtopicId" value="{{ $unitSubtopic->courseUnitSubtopicId }}" />
                            <input type="hidden" name="subtopicNumber" value="{{ $unitSubtopic->subtopicNumber }}" />
                            <input type="hidden" name="subtopicName" value="{{ $unitSubtopic->subtopicName }}" />
                            <button type="submit" class="unpublish-btn">Videos</button>
                        </form>
                    </div>
                </div>
                <div class="separator d-none d-sm-block"></div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="separator d-none d-sm-block"></div>
        @endforeach
    </div>
    @endif
</div>
