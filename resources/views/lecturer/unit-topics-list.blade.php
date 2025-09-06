<div class="mt-12 card">
    <div class="card-header">Existing Topics</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-1"><span class="title-color">Topic #</span></div>
            <div class="col-md-3"><span class="title-color">Unit Name</span></div>
            <div class="col-md-4"><span class="title-color">Topic</span></div>
            <div class="col-md-4"><span class="title-color"></span></div>
        </div>
        <div class="separator"></div>
        @foreach($unitTopics as $unitTopic)
        <div class="row">
            <div class="col-md-1"> {{ $unitTopic->topicNumber }}. </div>
            <div class="col-md-3"> {{ $unitTopic->courseUnitName }} </div>
            <div class="col-md-4"> {{ $unitTopic->topicName }}</div>
            <div class="col-md-4">
                <a href="{{ url('/lecturer/subtopics') }}/{{ $unitTopic->courseUnitTopicId }}" class="mr-8">
                    <button class="publish-btn">Subtopics</button>
                </a>
                <a href="{{ url('/unit/topic/edit/') }}/{{ $unitTopic->courseUnitTopicId }}/{{ $viewType }}">
                    <button class="edit-btn">Edit</button>
                </a>
                <a href="{{ url('/lecturer/course/unit/') }}/{{ $unitTopic->courseUnitId }}" target="_blank">
                    <button class="publish-btn">Preview</button>
                </a>
                <form method="post" action="{{ url('/lecturer/remove-topic') }}" class="inline-b-view">
                    @csrf
                    <input type="hidden" name="courseUnitTopicId" id="courseUnitTopicId"
                        value="{{ $unitTopic->courseUnitTopicId }}">
                    <button type="submit" class="unpublish-btn">Remove</button>
                </form>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
