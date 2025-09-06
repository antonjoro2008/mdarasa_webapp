<div class="mt-12 card">
    <div class="card-header">Existing Course Units</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">Course</span></div>
            <div class="col-md-3"><span class="title-color">Unit Name</span></div>
            <div class="col-md-2"><span class="title-color">Unit Code</span></div>
            <div class="col-md-2"><span class="title-color">Published?</span></div>
            <div class="col-md-3"><span class="title-color"></span></div>
        </div>
        <div class="separator"></div>
        @if(!empty($courseUnits))
        @foreach($courseUnits as $courseUnit)
        <div class="row">
            <div class="col-md-3"> {{ $courseUnit->courseName }} </div>
            <div class="col-md-3"> {{ $courseUnit->courseUnitName }} </div>
            <div class="col-md-2"> {{ $courseUnit->unitCode }}</div>
            <div class="col-md-1">
                {!! ($courseUnit->publishStatus == 1)?"<span class='published'>Yes</span>":"<span
                    class='not-published'>No</span>" !!}
            </div>
            <div class="col-md-3">
                <form method="post" action="{{ url('/lecturer/remove-unit') }}" class="pull-right inline-b-view">
                    @csrf
                    <input type="hidden" name="courseUnitId" id="courseUnitId" value="{{ $courseUnit->courseUnitId }}">
                    <button type="submit" class="unpublish-btn">Remove</button>
                </form>
                <a href="{{ url('/lecturer/topics') }}/{{ $courseUnit->courseUnitId }}" class="pull-right mr-8">
                    <button class="publish-btn">Topics</button>
                </a>
                <a href="{{ url('/course/unit/edit') }}/{{ $courseUnit->courseUnitId }}" class="pull-right mr-8">
                    <button class="edit-btn">Edit</button>
                </a>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
        @endif
    </div>
</div>
