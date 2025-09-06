<div class="mt-12 card">
    <div class="card-header">Orders History</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">Ordered At</span></div>
            <div class="col-md-4"><span class="title-color">Unit Name</span></div>
            <div class="col-md-2"><span class="title-color">Payment Status</span></div>
            <div class="col-md-3"><span class="title-color">Price</span></div>
        </div>
        <div class="separator"></div>
        @foreach($orders as $order)
        <div class="row">
            <div class="col-md-3"> {{ $order->createdAt }} </div>
            <div class="col-md-4"> {{ $order->courseUnitName }} </div>
            <div class="col-md-2"> {{ $order->paymentStatus }}</div>
            <div class="col-md-3"> {{ number_format($order->totalOrderValue, 2) }}</div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
