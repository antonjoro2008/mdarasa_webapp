<div class="cards-container mtp-4">
    <h1> Available Course Units </h1>
    <div class="row">
        @foreach($courseUnits as $courseUnit)
        <div class="pb-12 col-md-3">
            @include('student.course-unit-card', ['courseUnit' => $courseUnit])
        </div>
        @endforeach
    </div>
</div>
