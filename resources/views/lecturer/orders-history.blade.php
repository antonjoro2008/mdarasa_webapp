<div class="mt-12 card">
    <div class="card-header">Orders History</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2"><span class="title-color">Ordered At</span></div>
            <div class="col-md-2"><span class="title-color">Name</span></div>
            <div class="col-md-4"><span class="title-color">Unit Name</span></div>
            <div class="col-md-2"><span class="title-color">Payment Status</span></div>
            <div class="col-md-2"><span class="title-color">Order Value</span></div>
        </div>
        <div class="separator"></div>
        @foreach($orders as $order)
        <div class="row">
            <div class="col-md-2"> {{ $order->createdAt }} </div>
            <div class="col-md-2"> {{ $order->firstName }} {{ $order->lastName }}</div>
            <div class="col-md-4"> {{ $order->courseUnitName }} </div>
            <div class="col-md-2"> {{ $order->paymentStatus }}</div>
            <div class="col-md-2"> {{ $order->totalOrderValue }}</div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>