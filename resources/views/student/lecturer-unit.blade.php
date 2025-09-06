<div class="cards-container mtp-4">
    <h1> Available Lecturer Units </h1>
    <div class="row">
        @foreach($lecturerUnits as $lecturerUnit)
        <div class="pb-12 col-md-3">
            @include('student.course-unit-card', ['courseUnit' => $lecturerUnit])
        </div>
        @endforeach
    </div>
</div>
