<div class="mt-12 card">
    <div class="card-header">Add Unit Topic</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                @if(!isset($unitTopic) || is_null($unitTopic))
                <form method="post" action="{{ url('/lecturer/add-topic') }}" enctype="multipart/form-data">
                    @else
                    <form method="post" action="{{ url('/lecturer/update-topic') }}" enctype="multipart/form-data">
                        <input type="hidden" name="courseUnitTopicId" value="{{ $unitTopic->courseUnitTopicId }}" />
                        <input type="hidden" name="viewType" value="{{ $viewType }}" />
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitId">Course Unit</label>
                                    <select class="form-control" id="courseUnitId" name="courseUnitId">
                                        <option value=""></option>
                                        @foreach($courseUnits as $unit)
                                        <option value="{{ $unit->courseUnitId }}" @if(isset($unitTopic) &&
                                            !is_null($unitTopic)) @if($unit->courseUnitId ==
                                            $unitTopic->courseUnitId) selected @endif
                                            @elseif(!is_null($courseUnitId) &&
                                            $courseUnitId == $unit->courseUnitId) selected
                                            @endif>{{ $unit->courseUnitName }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label" for="unitTopicName">Topic Name</label>
                                    <input placeholder="Enter Topic Name" type="text" id="unitTopicName"
                                        name="unitTopicName" class="form-control"
                                        value="@if(isset($unitTopic) && !is_null($unitTopic)) {{ $unitTopic->topicName }}  @endif"
                                        required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label class="form-label" for="unitTopicNumber">Topic Number</label>
                                    <input placeholder="Enter the topic number" type="number" id="topicNumber"
                                        name="topicNumber" class="form-control"
                                        value="@if(isset($unitTopic) && !is_null($unitTopic)){{$unitTopic->topicNumber}}@endif"
                                        min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="topicDescription">Topic Description</label>
                                    <textarea id="topicDescription"
                                        name="topicDescription">@if(isset($unitTopic) && !is_null($unitTopic)) {{ $unitTopic->description }}  @endif</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="topicVideoFile">
                                    Upload Topic Video
                                </label>
                                <input type="file" id="topicVideoFile" name="topicVideoFile" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="topicNotesFile">
                                    Upload Topic Notes
                                </label>
                                <input type="file" id="topicNotesFile" name="topicNotesFile" class="form-control">
                            </div>
                        </div>
                    </div> -->
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" name="submitButt" value="remain"
                                    class="btn btn-primary">Save</button>
                            </div>
                            <div class="col-sm-6">
                                @if(!isset($unitTopic) || is_null($unitTopic))
                                <button type="submit" name="submitButt" value="proceed"
                                    class="btn btn-primary pull-right"
                                    title="If you do not have multiple subtopics under the topic, you can simply use the a single subtopic with the same name as the topic in the subtopics section">
                                    Save and Proceed to Add Subtopics
                                </button>
                                @endif
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>