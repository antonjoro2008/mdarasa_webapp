<div class="mt-12 card">
    <div class="card-header">Add Question</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                @if(is_null($subtopicQuestion))
                <form method="post" action="{{ url('/subtopic/add-question') }}">
                    @else
                    <form method="post" action="{{ url('/subtopic/update-question') }}">
                        <input type="hidden" name="courseUnitId" value="{{ $courseUnitId }}"/>
                        <input type="hidden" name="subtopicQuestionId" value="{{ $subtopicQuestion->subtopicQuestionId }}"/>
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <input type="hidden" name="courseUnitSubtopicId" value="{{ $subtopicId }}"/>
                                <label class="form-label" for="question">Your Question</label>
                                <textarea id="question" name="question">@if(!is_null($subtopicQuestion)) {!! $subtopicQuestion->question !!} @endif</textarea>
                            </div>
                        </div>
                    </div>
                    @if(is_null($subtopicQuestion))
                    <button type="submit" class="btn btn-success">Submit</button>
                    @else
                    <button type="submit" class="btn btn-success">Update</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
