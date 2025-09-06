<div class="mt-12 card">
    <div class="card-header">Add Subtopic Video</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                @csrf
                <input type="hidden" name="courseUnitSubtopicId" id="courseUnitSubtopicId"
                    value="{{ $courseUnitSubtopicId }}" />
                <div class="row">
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label class="form-label" for="videoTitle">Title</label>
                            <input placeholder="Enter Video Title" type="text" id="videoTitle" name="videoTitle"
                                class="form-control" value="" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label class="form-label" for="videoNumber">Video Number</label>
                            <input placeholder="Enter the video number" type="number" id="videoNumber"
                                name="videoNumber" class="form-control" value="" min="1" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div>
                            <label for="" class="form-label" style="display:block;">Video Source</label>
                            <div style="display:inline-block;">
                                <input type="radio" id="uploadVideo" name="videoSource" value="upload" checked>
                                <label for="uploadVideo">Upload Video</label>
                            </div>
                            <div style="display:inline-block;">
                                <input type="radio" id="thirdPartyUrl" name="videoSource" value="url">
                                <label for="thirdPartyUrl">Use Third-Party URL</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="uploadVideoField">
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label class="form-label" for="subtopicVideoFile">
                                Upload Subtopic Video
                            </label>
                            <input type="file" id="subtopicVideoFile" name="subtopicVideoFile" class="form-control">
                            <div class="progress-wrapper" id="progressWrapper">
                                <div class="progressBar" id="progressBar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="thirdPartyUrlField" style="display: none;">
                    <div class="col-sm-12">
                        <div class="mb-3">
                            <label class="form-label" for="thirdPartyVideoUrl">Third-Party Video URL</label>
                            <input type="url" id="thirdPartyVideoUrl" name="thirdPartyVideoUrl" class="form-control"
                                placeholder="Enter third-party video URL">
                        </div>
                    </div>
                </div>
                <button type="button" id="saveVideoButton" class="btn btn-primary" onclick="saveVideo()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const uploadVideoRadio = document.getElementById('uploadVideo');
        const thirdPartyUrlRadio = document.getElementById('thirdPartyUrl');
        const uploadVideoField = document.getElementById('uploadVideoField');
        const thirdPartyUrlField = document.getElementById('thirdPartyUrlField');

        // Add event listeners to radio buttons
        uploadVideoRadio.addEventListener('change', toggleVideoSourceFields);
        thirdPartyUrlRadio.addEventListener('change', toggleVideoSourceFields);

        function toggleVideoSourceFields() {
            if (uploadVideoRadio.checked) {
                uploadVideoField.style.display = 'block';
                thirdPartyUrlField.style.display = 'none';
            } else if (thirdPartyUrlRadio.checked) {
                uploadVideoField.style.display = 'none';
                thirdPartyUrlField.style.display = 'block';
            }
        }

        // Initial call to set the correct visibility on page load
        toggleVideoSourceFields();
    });
</script>