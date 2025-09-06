<div class="mt-12 card">
    <div class="card-header">Lecturer's Profile Summary</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 unit-details">
                <img src="{{ url('/images/teacher.png') }}" alt="" width="200px" />
                <div>
                    <span class="title-color">Name:</span> {{ $courseUnit->fullName }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 unit-details">
                <div>
                    {!! $courseUnit->profileSummary !!}
                </div>
            </div>
        </div>
    </div>
</div>
