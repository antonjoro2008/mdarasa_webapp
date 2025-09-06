<div class="mt-12 card">
    <div class="card-header">Add Subtopic Notes</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <form method="post" action="{{ url('/subtopic/notes/add') }}" enctype="multipart/form-data"
                    id="notesSubmitForm">
                    @csrf
                    <input type="hidden" name="courseUnitSubtopicId" value="{{ $courseUnitSubtopicId }}" />
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="notesTitle">Title</label>
                                <input placeholder="Enter Notes Title" type="text" id="notesTitle" name="notesTitle"
                                    class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="notesNumber">Notes Number</label>
                                <input placeholder="Enter the notes number" type="number" id="notesNumber"
                                    name="notesNumber" class="form-control" value="" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="subtopicNotesFile">
                                    Upload Subtopic Notes
                                </label>
                                <input type="file" id="subtopicNotesFile" name="subtopicNotesFile" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
