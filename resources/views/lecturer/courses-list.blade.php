<div class="mt-12 card">
    <div class="card-header">Existing Courses</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-9"><span class="title-color">Course</span></div>
            <div class="col-md-3"><span class="title-color"></span></div>
        </div>
        <div class="separator"></div>
        @if($courses != null)
        @foreach($courses as $course)
        <div class="row">
            <div class="col-md-9"> {{ $course->courseName }} </div>
            <div class="col-md-3">
                <form method="post" action="{{ url('/lecturer/remove-course') }}" class="pull-right inline-b-view">
                    @csrf
                    <input type="hidden" name="courseId" id="courseId" value="{{ $course->courseId }}">
                    <button type="submit" class="unpublish-btn">Remove</button>
                </form>
                <a href="{{ url('/course/edit') }}/{{ $course->courseId }}" class="pull-right mr-8">
                    <button class="edit-btn">Edit</button>
                </a>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
        @endif
    </div>
</div>
