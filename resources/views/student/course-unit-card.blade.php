@php
    $raw = $courseUnit->coverImage ?? ($courseUnit->coverImageUrl ?? null);
    $raw = is_string($raw) ? trim($raw) : '';
    if ($raw === '') {
        $coverSrc = asset('images/learning-placeholder.svg');
        $coverAlt = 'Learning course placeholder';
        $coverClass = 'course-cover course-cover--placeholder';
    } else {
        if (preg_match('#^https?://#i', $raw)) {
            $coverSrc = $raw;
        } elseif (strpos($raw, '/') === 0) {
            $coverSrc = $raw;
        } elseif (preg_match('#^(uploads|storage)/#i', $raw)) {
            $coverSrc = '/' . ltrim($raw, '/');
        } else {
            $coverSrc = '/uploads/cover-images/' . $raw;
        }
        $coverAlt = $courseUnit->courseUnitName ?? 'Course cover';
        $coverClass = 'course-cover course-cover--photo';
    }
@endphp
<div class="card topic-card-img-bg course-card-modern">
    <div class="course-card-badge">Featured Learning</div>
    <a class="nav-link center-flex" href="/course/unit/{{ $courseUnit->courseUnitId }}">
        <img class="card-img-top topic-card-img-bg br-12 topic-image img-fluid {{ $coverClass }}"
            src="{{ $coverSrc }}" alt="{{ $coverAlt }}">
    </a>
    <div class="card-body txt-align-center">
        <a class="nav-link" href="/course/unit/{{ $courseUnit->courseUnitId }}">
            <div class="card-title h5">{{ ucwords(strtolower($courseUnit->courseUnitName)) }}</div>
            <div>
                <i class="fa fa-star rating"></i>
                <i class="fa fa-star rating"></i>
                <i class="fa fa-star rating"></i>
                <i class="fa fa-star rating"></i>
            </div>
            <div class="instructor-name"> {{ $courseUnit->fullName }} </div>
        </a>
        <div class="course-card-price mt-8">
            KSh {{ number_format((float) $courseUnit->price, 0) }}
        </div>
        <div>
            <button type="button" class="buy-course-button btn btn-primary" onclick="addToCart({{ $courseUnit->courseUnitId }},
                {{ '"'.$courseUnit->courseUnitName.'"' }},
                {{ $courseUnit->price }},
                {{ '"'.$courseUnit->fullName.'"' }})">
                Buy Course Unit
            </button>
        </div>
    </div>
</div>
