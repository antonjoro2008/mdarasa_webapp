<div class="mt-12 card">
    <div class="card-header">Withdrawals</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">Phone Number</span></div>
            <div class="col-md-2"><span class="title-color">Amount</span></div>
            <div class="col-md-2"><span class="title-color">Time</span></div>
            <div class="col-md-3"><span class="title-color">Reference</span></div>
            <div class="col-md-2"><span class="title-color">Status</span></div>
        </div>
        <div class="separator"></div>
        @foreach($withdrawals as $withdrawal)
        <div class="row">
            <div class="col-md-3"> {{ $withdrawal->msisdn }} </div>
            <div class="col-md-2"> {{ $withdrawal->amount }} </div>
            <div class="col-md-2"> {{ date('d/m/Y H:i', strtotime($withdrawal->created)) }} </div>
            <div class="col-md-3"> {{ $withdrawal->reference }} </div>
            <div class="col-md-2"> {{ $withdrawal->status }}</div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
