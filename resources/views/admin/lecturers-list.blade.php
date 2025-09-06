<div class="mt-12 card">
    <div class="card-header">Registered Lecturers</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2"><span class="title-color">Name</span></div>
            <div class="col-md-3"><span class="title-color">Email Address</span></div>
            <div class="col-md-2"><span class="title-color">Phone</span></div>
            <div class="col-md-3"><span class="title-color">Course Unit</span></div>
            <div class="col-md-2"><span class="title-color">Comm. % Rate</span></div>
        </div>
        <div class="separator"></div>
        @foreach($lecturers as $lecturer)
        <div class="row">
            <div class="col-md-2"> {{ $lecturer->salutation." ".$lecturer->firstName." ".$lecturer->lastName }} </div>
            <div class="col-md-3"> {{ $lecturer->email }} </div>
            <div class="col-md-2"> {{ $lecturer->phone }}</div>
            <div class="col-md-3"> {{ $lecturer->courseName }}</div>
            <div class="col-md-2"> {{ $lecturer->commissionRate }}
                <a href="{{ url('/admin/profile') }}/{{ $lecturer->profileId }}">
                    <button class="view-btn pull-right">View</button>
                </a>
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
