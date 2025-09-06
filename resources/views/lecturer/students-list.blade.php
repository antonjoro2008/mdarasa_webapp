<div class="mt-12 card">
    <div class="card-header">My Students</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">Student's Name</span></div>
            <div class="col-md-4"><span class="title-color">Email Address</span></div>
            <div class="col-md-2"><span class="title-color">Last Order Date</span></div>
            <div class="col-md-3"><span class="title-color">Course Unit Purchased</span></div>
        </div>
        <div class="separator"></div>
        @foreach($lecturerStudents as $student)
        <div class="row">
            <div class="col-md-3"> {{ $student->firstName." ".$student->lastName }} </div>
            <div class="col-md-4"> {{ $student->email }} </div>
            <div class="col-md-2"> {{ $student->lastOrderDate }}</div>
            <div class="col-md-3"> {{ $student->lastPurchasedCourseUnit }}</div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
