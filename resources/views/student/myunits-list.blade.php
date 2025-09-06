<div class="mt-12 card">
    <div class="card-header">My Units</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2"><span class="title-color">Purchased On</span></div>
            <div class="col-md-2"><span class="title-color">Unit Code</span></div>
            <div class="col-md-4"><span class="title-color">Unit Name</span></div>
            <div class="col-md-2"><span class="title-color">Course Name</span></div>
            <div class="col-md-2"><span class="title-color"></span></div>
        </div>
        <div class="separator"></div>
        @foreach($myUnits as $myUnit)
        <div class="row">
            <div class="col-md-2"> {{ $myUnit->createdAt }} </div>
            <div class="col-md-2"> {{ $myUnit->unitCode }} </div>
            <div class="col-md-4"> {{ $myUnit->courseUnitName }} </div>
            <div class="col-md-2"> {{ $myUnit->courseName }} </div>
            <div class="col-md-2">
                <a class="nav-link" href="/course/student/unit/{{ $myUnit->courseUnitId }}">
                    <button type="button" class="publish-btn">Open Course Unit</button>
                </a>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
