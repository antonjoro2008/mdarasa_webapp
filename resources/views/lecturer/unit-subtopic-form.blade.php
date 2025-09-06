<div class="mt-12 card">
    <div class="card-header">Add Unit Subtopic</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                @if(!isset($unitSubtopic) || is_null($unitSubtopic))
                <form method="post" action="{{ url('/lecturer/add-subtopic') }}" enctype="multipart/form-data">
                    @else
                    <form method="post" action="{{ url('/lecturer/update-subtopic') }}" enctype="multipart/form-data">
                        <input type="hidden" name="courseUnitSubtopicId"
                            value="{{ $unitSubtopic->courseUnitSubtopicId }}" />
                        <input type="hidden" name="viewType" value="{{ $viewType }}" />
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitId">Course Unit</label>
                                    <select class="form-control" id="courseUnitId" name="courseUnitId"
                                        onchange="filterTopics()">
                                        <option value=""></option>
                                        @if(!is_null($courseUnits))
                                        @foreach($courseUnits as $unit)
                                        <option value="{{ $unit->courseUnitId }}" @if($unit->courseUnitId ==
                                            $selectedUnitId) selected @endif>{{ $unit->courseUnitName }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitTopicId">Unit Topic</label>
                                    <select class="form-control" id="courseUnitTopicId" name="courseUnitTopicId">
                                        <option value=""></option>
                                        @if(!is_null($unitTopics))
                                        @foreach($unitTopics as $unitTopic)
                                        <option value="{{ $unitTopic->courseUnitTopicId }}" @if($unitTopic->
                                            courseUnitTopicId ==
                                            $selectedUnitTopicId) selected @elseif(!is_null($unitTopicId) &&
                                            $unitTopicId == $unitTopic->courseUnitTopicId) selected
                                            @endif>{{ $unitTopic->topicName }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label" for="unitTopicName">Subtopic</label>
                                    <input placeholder="Enter Subtopic Name" type="text" id="subtopicName"
                                        name="subtopicName" class="form-control"
                                        value="@if(isset($unitSubtopic) && !is_null($unitSubtopic)) {{ $unitSubtopic->subtopicName }}  @endif"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="subtopicNumber">Subtopic Number</label>
                                    <?php $subTopicNum = $unitSubtopic?$unitSubtopic->subtopicNumber:""; ?>
                                    <input placeholder="Enter the subtopic number" type="number" id="subtopicNumber"
                                        name="subtopicNumber" class="form-control" value="{{$subTopicNum}}" min="1"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="subtopicDescription">Subtopic Description</label>
                                    <textarea id="subtopicDescription"
                                        name="subtopicDescription">@if(isset($unitSubtopic) && !is_null($unitSubtopic)) {{ $unitSubtopic->description }}  @endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" name="submitButt" value="remain"
                                    class="btn btn-primary">Save</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" name="submitButt" value="proceed"
                                    class="btn btn-primary pull-right">
                                    Save and Proceed to add Videos/Notes
                                </button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

<script>
function filterTopics() {

    let courseUnitId = $("#courseUnitId").val();

    axios.post(BASEURL + "/course-unit/topics/find", {
        courseUnitId: courseUnitId
    }).then(function(response) {
        console.log(response.data.Data);
        let respJSON = response.data.Data;

        let responseStr = `<option value=""></option>`;

        for (let rec in respJSON) {

            let unitTopic = respJSON[rec];
            responseStr = responseStr + `<option value="` + unitTopic.courseUnitTopicId + `">` + unitTopic
                .topicName + `</option>`

        }

        $('#courseUnitTopicId').html(responseStr)

    }).catch(function(error) {
        console.error(error);
    });
}
</script>