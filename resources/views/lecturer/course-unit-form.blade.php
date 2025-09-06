<style>
    .error {
        color: red;
    }
</style>
<div class="mt-12 card">
    <div class="card-header">Add Course Unit</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                @if(!isset($courseUnit) || is_null($courseUnit))
                    <form method="post" action="{{ url('/lecturer/add-unit') }}" enctype="multipart/form-data"
                        id="courseUnitForm">
                @else
                    <form method="post" action="{{ url('/lecturer/update-unit') }}" enctype="multipart/form-data"
                        id="courseUnitForm">
                        <input type="hidden" name="courseUnitId" value="{{ $courseUnit->courseUnitId }}" />
                        <input type="hidden" name="susp" value="{{ $courseUnit->isSuspended }}" />
                        <input type="hidden" name="susReason" value="{{ $courseUnit->suspensionReason }}" />
                @endif
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitName">Select your Course</label>
                                    <select class="form-control @error('courseId') is-invalid @enderror" id="courseId"
                                        name="courseId" required>
                                        <option value="">Please select course</option>
                                        @if(!empty($courses))
                                                                            @foreach($courses as $course)
                                                                                                                <option value="{{ $course->courseId }}" @if(
                                                                                                                        !is_null($courseUnit) &&
                                                                                                                        $courseUnit->courseId == $course->courseId
                                                                                                                    ) selected
                                                                                                                @elseif(!is_null($courseId) && $courseId == $course->courseId) selected
                                                                                                                @endif>{{ $course->courseName }}
                                                                                                                </option>
                                                                            @endforeach
                                        @endif
                                    </select>
                                    @error('courseId')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitName">Unit Name</label>
                                    <input placeholder="Enter Course Unit Name" type="text" id="courseUnitName"
                                        name="courseUnitName" class="form-control"
                                        value="@if(isset($courseUnit) && !is_null($courseUnit)) {{ $courseUnit->courseUnitName }}  @endif">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitCode">Unit Code</label>
                                    <input placeholder="Enter Unit Code" type="text" id="courseUnitCode"
                                        name="courseUnitCode" class="form-control"
                                        value="@if(isset($courseUnit) && !is_null($courseUnit)) {{ $courseUnit->unitCode }}  @endif">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitSection">Unit Section</label>
                                    <input placeholder="Course Unit Section" type="text" id="courseUnitSection"
                                        name="courseUnitSection" class="form-control"
                                        value="@if(isset($courseUnit) && !is_null($courseUnit)) {{ $courseUnit->courseUnitSection }}  @endif">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitPart">Unit Part</label>
                                    <input placeholder="Course Unit Part" type="text" id="courseUnitPart"
                                        name="courseUnitPart" class="form-control"
                                        value="@if(isset($courseUnit) && !is_null($courseUnit)) {{ $courseUnit->courseUnitPart }}  @endif">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="price">Price</label>
                                    <input placeholder="Your Price" type="text" id="price" name="price"
                                        class="form-control"
                                        value="@if(isset($courseUnit) && !is_null($courseUnit)) {{ $courseUnit->price }}  @endif">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="highlights">Unit Highlights</label>
                                    <textarea id="highlights"
                                        name="highlights">@if(isset($courseUnit) && !is_null($courseUnit)) {{ $courseUnit->highlight }}  @endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Unit Description</label>
                                    <textarea id="description"
                                        name="description">@if(isset($courseUnit) && !is_null($courseUnit)) {{ $courseUnit->unitDescription }}  @endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="courseUnitImage">
                                        Course Unit Image
                                    </label>
                                    <input type="file" id="courseUnitImage" name="courseUnitImage" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="error" id="errorMessage"></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="purchasesExpirationDays">Purchases Expiration Number
                                        of Days (Leave
                                        Blank for Access without Expiration)</label>
                                    <input placeholder="Number of Days for access after Purchase" type="number"
                                        id="purchasesExpirationDays" name="purchasesExpirationDays" class="form-control"
                                        value="@if(isset($courseUnit) && !is_null($courseUnit)) {{ $courseUnit->purchasesExpirationDays }}  @endif">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" name="submitButt" value="remain"
                                    class="btn btn-primary">Save</button>
                            </div>
                            <div class="col-sm-6">
                                @if(!isset($courseUnit) || is_null($courseUnit))
                                    <button type="submit" name="submitButt" value="proceed"
                                        class="btn btn-primary pull-right">
                                        Save and Proceed to Add Topics
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('courseUnitForm').addEventListener('submit', function (event) {

        const fileInput = document.getElementById('courseUnitImage');
        const file = fileInput.files[0];
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = '';

        if (file) {
            event.preventDefault();
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = new Image();
                img.onload = function () {
                    if (img.width <= img.height) {

                        errorMessage.textContent = 'The image must be in landscape orientation.';

                    } else {
                        document.getElementById('courseUnitForm').submit();
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>