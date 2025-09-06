<div class="mt-12 card">
    <div class="card-header">Orders History</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2"><span class="title-color">Ordered At</span></div>
            <div class="col-md-2"><span class="title-color">Reference</span></div>
            <div class="col-md-4"><span class="title-color">Unit Name</span></div>
            <div class="col-md-2"><span class="title-color">Price</span></div>
            <div class="col-md-2"><span class="title-color">Payment Status</span></div>
        </div>
        <div class="separator"></div>
        @foreach($orders as $order)
        <div class="row">
            <div class="col-md-2"> {{ $order->createdAt }} </div>
            <div class="col-md-2"> {{ $order->orderReference }}</div>
            <div class="col-md-4">
                {{ $order->courseUnitName }}
            </div>
            <div class="col-md-2"> {{ $order->totalOrderValue }}</div>
            <div class="col-md-2"> {!! !is_null($order->paymentStatus)?$order->paymentStatus:'<span
                    style="color:#A20;">Incomplete</span>' !!}
                @if(is_null($order->paymentStatus))
                <form method="post" action="{{ url('/student/order/complete') }}">
                    @csrf
                    <input type="hidden" name="totalOrderValue" value="{{ $order->totalOrderValue }}" />
                    <input type="hidden" name="studentOrderId" id="studentOrderId"
                        value="{{ $order->studentOrderId }}" />
                    <button type="submit" class="view-btn">Complete</button>
                </form>
                @endif
            </div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
