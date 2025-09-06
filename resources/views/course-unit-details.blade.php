<div class="mt-12 card">
    <div class="card-header">Course Unit Details</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <img src="{{ url('/images/topics/ai.jpg') }}" width="200px" alt="" />
            </div>
            <div class="col-md-9 unit-details">
                <div class="row">
                    <div class="col-md-12">
                        {{ $courseUnit->courseUnitName }}
                        <div class="instructor-name">{{ $courseUnit->fullName }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        KShs. {{ number_format($courseUnit->price, 2) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="mt-12 btn btn-warning full-width" onclick="addToCart({{ $courseUnit->courseUnitId }},
                {{ '"'.$courseUnit->courseUnitName.'"' }},
                {{ $courseUnit->price }},
                {{ '"'.$courseUnit->fullName.'"' }})">
                            <i class="fa fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>