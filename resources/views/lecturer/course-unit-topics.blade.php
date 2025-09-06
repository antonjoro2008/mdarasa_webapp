<div class="mt-12 card">
    <div class="card-header">Topics Covered</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">Unit Code</span></div>
            <div class="col-md-4"><span class="title-color">Course Unit</span></div>
            <div class="col-md-1"><span class="title-color">Topic #</span></div>
            <div class="col-md-4"><span class="title-color">Topic Name</span></div>
        </div>
        <div class="separator"></div>
        @if(!is_null($courseUnitTopics) && !empty($courseUnitTopics))
        @foreach($courseUnitTopics as $courseUnitTopic)
        <div class="row">
            <div class="col-md-3"> {{ $courseUnitTopic->courseUnitCode }} </div>
            <div class="col-md-4"> {{ $courseUnitTopic->courseUnitName }} </div>
            <div class="col-md-1"> {{ $courseUnitTopic->topicNumber }} </div>
            <div class="col-md-4"> {{ $courseUnitTopic->topicName }} </div>
        </div>
        @if(!empty($courseUnitTopic->courseUnitSubtopics))
        <div class="row" style="background:#303030;">
            <div class="col-sm-12">
                <div class="row" style="background: #383838 !important;">
                    <div class="col-md-6"><span class="title-color">Subtopic</span></div>
                    <div class="col-md-6"><span class="title-color pull-right">Notes and Videos</span></div>
                </div>
                @foreach($courseUnitTopic->courseUnitSubtopics as $unitSubtopic)
                <div class="row">
                    <div class="col-md-6"> {{ $unitSubtopic->subtopicNumber }}. {{ $unitSubtopic->subtopicName }}</div>
                    <div class="col-md-6">
                        <form method="post" action="{{ url('/lecturer/remove-subtopic') }}"
                            class="inline-view pull-right">
                            @csrf
                            <input type="hidden" name="courseUnitSubtopicId" id="courseUnitSubtopicId"
                                value="{{ $unitSubtopic->courseUnitSubtopicId }}">
                            <button type="submit" class="unpublish-btn">Remove</button>
                        </form>
                        <form method="get" action="{{ url('/lecturer/subtopic/notes') }}"
                            class="inline-view pull-right mr-8">
                            @csrf
                            <input type="hidden" name="courseUnitId" value="{{ $unitSubtopic->courseUnitId }}" />
                            <input type="hidden" name="topicId" value="{{ $unitSubtopic->unitTopicId }}" />
                            <input type="hidden" name="subtopicId" value="{{ $unitSubtopic->courseUnitSubtopicId }}" />
                            <input type="hidden" name="subtopicNumber" value="{{ $unitSubtopic->subtopicNumber }}" />
                            <input type="hidden" name="subtopicName" value="{{ $unitSubtopic->subtopicName }}" />
                            <button type="submit" class="publish-btn inline-view">Subtopic Notes</button>
                        </form>
                        <form method="get" action="{{ url('/lecturer/subtopic/content') }}"
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
                </div>
                <div class="separator"></div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="separator"></div>
        @endforeach
    </div>
    @endif
</div>
