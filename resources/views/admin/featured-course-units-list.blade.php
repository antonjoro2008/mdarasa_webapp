<div class="mt-12 card">
    <div class="card-header">Course Units</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">Course</span></div>
            <div class="col-md-3"><span class="title-color">Unit Name</span></div>
            <div class="col-md-1"><span class="title-color">Unit Code</span></div>
            <div class="col-md-2"><span class="title-color">Publish Status</span></div>
            <div class="col-md-3"><span class="title-color"></span></div>
        </div>
        <div class="separator"></div>
        @foreach($courseUnits as $courseUnit)
        <div class="row">
            <div class="col-md-3"> {{ $courseUnit->courseName }} </div>
            <div class="col-md-3"> {{ $courseUnit->courseUnitName }} </div>
            <div class="col-md-1"> {{ $courseUnit->unitCode }}</div>
            <div class="col-md-2">
                {!! ($courseUnit->publishStatus == 1)?"<span class='published'>Published</span>":"<span
                    class='not-published'>Not Published</span>" !!}
            </div>
            <div class="col-md-3">
                @if($courseUnit->publishStatus == 1)
                <form action="{{ url('/admin/unit/unpublish') }}" method="post" class="inline-view">
                    @csrf
                    <input type="hidden" name="courseUnitId" value="{{ $courseUnit->courseUnitId }}" />
                    <button class="unpublish-btn" type="submit">Unpublish</button>
                </form>
                @else
                <form action="{{ url('/admin/unit/publish') }}" method="post" style="width:50% !important"
                    class="inline-view">
                    @csrf
                    <input type="hidden" name="courseUnitId" value="{{ $courseUnit->courseUnitId }}" />
                    <button type="submit" class="publish-btn">Publish</button>
                </form>
                @endif
                <a href="{{ url('/admin/course/unit') }}/{{ $courseUnit->courseUnitId }}">
                    <button class="view-btn">View</button>
                </a>
                <form action="{{ url('/admin/unit/unfeature') }}" method="post" class="inline-view">
                    @csrf
                    <input type="hidden" name="courseUnitId" value="{{ $courseUnit->courseUnitId }}" />
                    <button class="rem-featured-btn" type="submit">Remove Featured</button>
                </form>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
