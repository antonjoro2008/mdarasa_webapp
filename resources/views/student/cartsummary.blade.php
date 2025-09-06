<div class="mt-12 card">
    <div class="card-header">Cart Summary</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-5">Total</div>
            <div class="col-md-7 checkout-color">Kshs. <span id="cartSubtotal"></span></div>
        </div>
        <div class="row">
            <div class="title-color col-md-12">Happy learning!</div>
        </div>
        <div class="row">
            <div class="title-color col-md-12">
                <form method="post" action="{{ url('/checkout') }}">
                    @csrf
                    <div id="courseUnitsIds"></div>
                    <button type="submit" class="btn btn-success mt-12 full-width btn btn-primary">CHECKOUT</button>
                </form>
            </div>
        </div>
    </div>
</div>
