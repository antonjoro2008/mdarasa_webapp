<div class="mt-12 card">
    <div class="card-header">My Questions</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-10"><span class="title-color">Question</span></div>
            <div class="col-md-2"><span class="title-color"></span></div>
        </div>
        <div class="separator"></div>
        @if(!is_null($subtopicQuestions))
        @foreach($subtopicQuestions as $question)
        <div class="row pt-8">
            <div class="col-md-10"> {!! $question->question !!} </div>
            <div class="col-md-2">

            </div>
        </div>
        @if($question->status==2)
        <div class="row quiz-answer">
            <div class="col-sm-12">
                <div>{!! $question->answer !!}</div>
            </div>
        </div>
        @endif
        <div class="separator"></div>
        @endforeach
        @endif
    </div>
</div>
