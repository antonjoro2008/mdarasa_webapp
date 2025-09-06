<div class="cards-container mtp-4">
    <h1> Popular Course Units </h1>
    <div class="row  scrollable">
        @if(!is_null($popularUnits))
        @foreach($popularUnits as $popular)
        <div class="pb-12 col-md-3 scroll-item">
            @include('student.course-unit-card', ['courseUnit' => $popular])
        </div>
        @endforeach
        @endif
    </div>
</div>
