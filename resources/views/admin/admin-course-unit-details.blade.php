<div class="mt-12 card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-10">
                Course Unit Details
            </div>
            <div class="col-sm-2">
                <a class="nav-link inline-view pull-right" href="{{ url('/admin/course-units') }}">
                    <button type="button" class="publish-btn inline-view">Back to Listed Units</button>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <img src="{{ url('/images/topics/ai.jpg') }}" width="200px" alt="" />
            </div>
            <div class="col-md-9 unit-details">
                <div class="row">
                    <div class="col-md-12">
                        {{ $courseUnit->courseUnitName }}
                        <div class="instructor-name">{{ $courseUnit->fullName }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
