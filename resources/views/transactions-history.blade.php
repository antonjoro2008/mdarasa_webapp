<div class="mt-12 card">
    <div class="card-header">Transactions History</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">Reference</span></div>
            <div class="col-md-2"><span class="title-color">Type</span></div>
            <div class="col-md-2"><span class="title-color">Amount</span></div>
            <div class="col-md-3"><span class="title-color">Time</span></div>
            <div class="col-md-2"><span class="title-color">Running Balance</span></div>
        </div>
        <div class="separator"></div>
        @foreach($transactions as $transaction)
        <div class="row">
            <div class="col-md-3"> {{ $transaction->reference }} </div>
            <div class="col-md-2"> {{ $transaction->transactionType }} </div>
            <div class="col-md-2">KES {{ $transaction->amount }}</div>
            <div class="col-md-3"> {{ $transaction->transactionTime }}</div>
            <div class="col-md-2">KES {{ $transaction->runningBalance }}</div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>
