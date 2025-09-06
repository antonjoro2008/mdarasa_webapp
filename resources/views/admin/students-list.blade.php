<div class="mt-12 card">
    <div class="card-header">Registered Students</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">Student's Name</span></div>
            <div class="col-md-4"><span class="title-color">Email Address</span></div>
            <div class="col-md-2"><span class="title-color">Last Order Date</span></div>
            <div class="col-md-3"><span class="title-color">Course Unit Purchased</span></div>
        </div>
        <div class="separator"></div>
        @if(!is_null($students) && !empty($students))
        @foreach($students as $student)
        <div class="row">
            <div class="col-md-3"> {{ $student->firstName." ".$student->lastName }} </div>
            <div class="col-md-4"> {{ $student->email }} </div>
            <div class="col-md-2"> {!! !is_null($student->lastOrderDate)?$student->lastOrderDate:"<span
                    style='color:#A20000' ;>Not purchased yet</span>" !!}
            </div>
            <div class="col-md-3">{!!
                !is_null($student->lastPurchasedCourseUnit)?$student->lastPurchasedCourseUnit:"<span
                    style='color:#A20000' ;>Not Applicable</span>" !!}</div>
        </div>
        <div class="separator"></div>
        @endforeach
        @endif
    </div>
</div>
