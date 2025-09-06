<div class="mt-12 card">
    <div class="card-header">Asked Questions</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10"><span class="title-color">Questions</span></div>
            <div class="col-md-2"><span class="title-color"></span></div>
        </div>
        <div class="separator"></div>
        @if(!is_null($subtopicQuestions))
        @foreach($subtopicQuestions as $question)
        <div class="row pt-8">
            <div class="col-md-10">
                <span class="title-color">By {{ $question->firstName }} {{ $question->lastName }}</span>
                {!! $question->question !!}
            </div>
            <div class="col-md-2 pt-16">
                <button class="edit-btn" onclick="toggleAnswerDiv({{ $question->subtopicQuestionId }})">Answer</button>
            </div>
            <div class="row answerdiv" id="answerdiv-{{ $question->subtopicQuestionId }}">
                <div class="col-sm-12">
                    <form method="post" action="{{ url('/lecturer/question/answer') }}">
                        @csrf
                        <input type="hidden" name="subtopicQuestionId" value="{{ $question->subtopicQuestionId }}" />
                        <textarea class="ckeditor" name="questionAnswer"
                            id="answer-{{ $question->question }}">@if($question->status==2){!! $question->answer !!}@endif</textarea>

                        <button type="submit" class="publish-btn">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
        @endif
    </div>
</div>
<script>
$('.answerdiv').hide();

function toggleAnswerDiv(subtopicId) {

    if ($('#answerdiv-' + subtopicId).is(":visible")) {
        $('#answerdiv-' + subtopicId).hide()
    } else {
        $('#answerdiv-' + subtopicId).show()
    }
}
</script>
