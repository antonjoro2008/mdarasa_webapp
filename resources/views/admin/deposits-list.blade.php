<div class="mt-12 card">
    <div class="card-header">Deposits</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2"><span class="title-color">Transaction ID</span></div>
            <div class="col-md-2"><span class="title-color">Amount</span></div>
            <div class="col-md-2"><span class="title-color">First Name</span></div>
            <div class="col-md-2"><span class="title-color">Transaction Time</span></div>
            <div class="col-md-2"><span class="title-color">Bill Ref No</span></div>
            <div class="col-md-2"><span class="title-color">Phone</span></div>
        </div>
        <div class="separator"></div>
        @foreach($deposits as $deposit)
        <div class="row">
            <div class="col-md-2"> {{ $deposit->transactionID }} </div>
            <div class="col-md-2"> {{ $deposit->transactionAmount }} </div>
            <div class="col-md-2"> {{ $deposit->firstName }} </div>
            <div class="col-md-2"> {{ date('d/m/Y H:i', strtotime($deposit->transactionTime)) }}</div>
            <div class="col-md-2"> {{ $deposit->billRefNo }}</div>
            <div class="col-md-2"> {{ $deposit->msisdn }}</div>
        </div>
        <div class="separator"></div>
        @endforeach
    </div>
</div>