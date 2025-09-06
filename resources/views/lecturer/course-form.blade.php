<div class="mt-12 card">
    <div class="card-header">Add your Course</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                @if(!isset($course) || is_null($course))
                <form method="post" action="{{ url('/lecturer/add-course') }}">
                    @else
                    <form method="post" action="{{ url('/lecturer/update-course') }}">
                        <input type="hidden" name="courseId" value="{{ $course->courseId }}" />
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="categoryId">Category</label>
                                    <select class="form-control @error('categoryId') is-invalid @enderror"
                                        id="categoryId" name="categoryId" required onchange="filterSubs()">
                                        <option value="">Please select category of the course you wish to teach</option>
                                        @if(!empty($categories))
                                        @foreach($categories as $category)
                                        <option value="{{ $category->categoryId }}" @if(isset($course) &&
                                            !is_null($course) && $course->categoryId == $category->categoryId)
                                            selected @endif>
                                            {{ $category->categoryName }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @error('categoryId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-12 title-color" id="categoriesDescriptions" style="line-height:1.5;">
                            <div class="col-sm-12">
                                @if(!empty($categories))
                                @foreach($categories as $category)
                                <div class="row hidden categorydesc"
                                    id="category-{{ $category->categoryId }}-description">
                                    <div class="col-sm-12">
                                        {{ $category->description }}
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="subcategory">Sub-category</label>
                                    <!-- <select class="form-control @error('subcategory') is-invalid @enderror"
                                        id="subcategory" name="subcategory" required>
                                        <option value="">Please select your subcategory</option>
                                        @if(isset($course) && !is_null($course))
                                        @if(strlen($course->subCategoryName) > 0)
                                        <option value="{{ $course->subCategoryId }}" selected>
                                            {{ $course->subCategoryName }}
                                        </option>
                                        @endif
                                        @endif
                                    </select> -->
                                    <select class="form-control @error('subcategory') is-invalid @enderror"
                                        id="subcategory" name="subcategory" required>
                                        <option value="">Please select your subcategory</option>
                                        @if(isset($course) && !is_null($course))
                                        @if(strlen($course->subCategoryName) > 0)
                                        <option value="{{ $course->categoryId }}" selected>
                                            {{ $course->categoryName }}
                                        </option>
                                        @endif
                                        @else
                                        @if(!empty($subCategories))
                                        @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->categoryId }}">
                                            {{ $subCategory->categoryName }}
                                        </option>
                                        @endforeach
                                        @endif
                                        @endif
                                    </select>
                                    @error('subcategory')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="courseName">Course Name</label>
                                    <input placeholder="Enter Course Name" type="text" id="courseName" name="courseName"
                                        class="form-control"
                                        value="@if(isset($course) && !is_null($course)) {{ $course->courseName }}  @endif">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Course Description</label>
                                    <textarea id="description"
                                        name="description">@if(isset($course) && !is_null($course)) {{ $course->courseDescription }}  @endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" name="submitButt" value="remain"
                                    class="btn btn-primary">Save</button>
                            </div>
                            <div class="col-sm-6">
                                @if(!isset($course) || is_null($course))
                                <button type="submit" name="submitButt" value="proceed"
                                    class="btn btn-primary pull-right">
                                    Save and Proceed to Add Units
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
function filterSubs() {

    let categoryId = $("#categoryId").val();

    axios.post(BASEURL + "/subcategories", {
        categoryId: categoryId
    }).then(function(response) {
        console.log(response.data.Data);
        let respJSON = response.data.Data;

        let responseStr = `<option value="">Please select sub-category</option>`;

        for (let rec in respJSON) {

            let subcategory = respJSON[rec];
            responseStr = responseStr + `<option value="` + subcategory.categoryId + `">` + subcategory
                .categoryName + `</option>`

        }

        $('#subcategory').html(responseStr)
        $('.categorydesc').addClass("hidden");
        $('#category-' + categoryId + '-description').removeClass("hidden")

    }).catch(function(error) {
        console.error(error);
    });
}
</script>