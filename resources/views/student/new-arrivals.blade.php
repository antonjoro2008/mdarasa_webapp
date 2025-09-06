<div class="cards-container mtp-4">
    <h1> New Arrivals </h1>
    <div class="row">
        @if(!is_null($newArrivals))
        @foreach($newArrivals as $arrival)
        <div class="pb-12 col-md-3">
            @include('student.course-unit-card', ['courseUnit' => $arrival])
        </div>
        @endforeach
        @endif
    </div>
</div>
