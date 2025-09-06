<div class="cards-container mtp-4">
    <h1> Search Results </h1>
    <div class="row">
        @if(!is_null($searchedCourseUnits))
        @if(!empty($searchedCourseUnits))
        @foreach($searchedCourseUnits as $courseUnit)
        <div class="pb-12 col-md-3">
            @include('student.course-unit-card', ['courseUnit' => $courseUnit])
        </div>
        @endforeach
        @else
        <div class="search-no-items">
            No course units with the specified search keyword. Please try changing the search
            keywords and try again.

            <div class="mt-12">
                <a href="{{ url('/') }}">
                    <button class="publish-btn search-back-btn">
                        << Back to Home </button>
                </a>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
