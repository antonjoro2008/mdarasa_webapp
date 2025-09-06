<div class="mt-12 card">
    <div class="card-header">Wallet</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 mb-12"><span class="title-color">Wallet Balance:</span> KShs.
                {{ number_format($walletInfo->balance,2) }}</div>
        </div>
        <div class="row mb-16">
            <div class="col-sm-12">
                <form method="post" action="{{ url('/deposit') }}">
                    @csrf
                    <input type="number" name="depositAmount" id="depositAmount" placeholder="Enter Deposit Amount" />
                    <button type="submit" class="btn btn-primary mb-4px">
                        Deposit
                    </button>
                </form>
            </div>
        </div>
        <div class="separator"></div>
        <div class="row">
            <div class="col-sm-12 pt-16">
                <form method="post" action="{{ url('/withdraw') }}">
                    @csrf
                    <input type="number" name="withdrawAmount" id="withdrawAmount"
                        placeholder="Enter Amount to Withdraw" />
                    <button type="submit" class="btn btn-success btn btn-primary mb-4px" id="withdrawBtn"
                        disabled="disabled">
                        Withdraw
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
const button = document.getElementById('withdrawBtn');

let _onload = window.onload || function() {
    document.getElementById('withdrawBtn').disabled = false;
}

_onload();

button.addEventListener('click', function(event) {
    setTimeout(function() {
        event.target.disabled = true;
    }, 0);
});
</script>