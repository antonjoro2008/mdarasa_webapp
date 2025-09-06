<div class="card topic-card-img-bg">
    <a class="nav-link center-flex" href="/course/unit/{{ $courseUnit->courseUnitId }}">
        <img class="card-img-top topic-card-img-bg br-12 topic-image img-fluid"
            src=@if($courseUnit->coverImage)<?php echo "/uploads/cover-images/$courseUnit->coverImage"; ?>
        @else <?php echo "/images/graduated-cap.webp"; ?> @endif alt="">
    </a>
    <div class=" card-body txt-align-center">
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