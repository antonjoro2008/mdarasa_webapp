<div class="mt-12 card">
    <div class="card-header">Existing Subtopics</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-1"><span class="title-color">#</span></div>
            <div class="col-md-3"><span class="title-color">Topic Name</span></div>
            <div class="col-md-4"><span class="title-color">Subtopic</span></div>
            <div class="col-md-4"><span class="title-color"></span></div>
        </div>
        <div class="separator"></div>
        @if(!is_null($unitSubtopics))
        @foreach($unitSubtopics as $unitSubtopic)
        <div class="row">
            <div class="col-md-1"> {{ $unitSubtopic->subtopicNumber }}. </div>
            <div class="col-md-3"> {{ $unitSubtopic->topicName }} </div>
            <div class="col-md-4"> {{ $unitSubtopic->subtopicName }}</div>
            <div class="col-md-4">
                <a href="{{ url('/unit/subtopic/resources/') }}/{{ $unitSubtopic->courseUnitSubtopicId }}">
                    <button class="publish-btn">Videos/Notes</button>
                </a>
                <a
                    href="{{ url('/lecturer/subtopic/questions/') }}/{{ $unitSubtopic->courseUnitId }}/{{ $unitSubtopic->courseUnitSubtopicId }}">
                    <button class="edit-btn">Questions</button>
                </a>
                <a href="{{ url('/unit/subtopic/edit/') }}/{{ $unitSubtopic->courseUnitSubtopicId }}/{{ $viewType }}">
                    <button class="publish-btn">Edit</button>
                </a>
                <form method="post" action="{{ url('/lecturer/remove-subtopic') }}" class="inline-view">
                    @csrf
                    <input type="hidden" name="courseUnitSubtopicId" id="courseUnitSubtopicId"
                        value="{{ $unitSubtopic->courseUnitSubtopicId }}">
                    <button type="submit" class="unpublish-btn">Remove</button>
                </form>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
        @endif
    </div>
</div>