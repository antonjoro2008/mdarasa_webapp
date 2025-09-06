<div class="cards-container mtp-4">
    <h1> Featured Course Units </h1>
    <div class="row">
        @if(!is_null($featuredCourses))
        @foreach($featuredCourses as $featured)
        <div class="pb-12 col-md-3">
            @include('student.course-unit-card', ['courseUnit' => $featured])
        </div>
        @endforeach
        @endif
    </div>
</div>
