<div class="mt-12 card">
    <div class="card-header">Wallet</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 mb-12"><span class="title-color">Wallet Balance:</span> KShs.
                {{ number_format($walletInfo->balance,2) }}</div>
        </div>
        <div class="row mb-16">
            <div class="col-sm-12">
                <span class="title-color">Commission Rate:</span>
                {{ $profileInfo->commissionRate }}%
            </div>
        </div>
        <div class="separator"></div>
        <div class="row">
            <div class="col-sm-12 pt-16">
                <form method="post" action="{{ url('/admin/commission/rate') }}">
                    @csrf
                    <input type="hidden" name="profileId" id="profileId" value="{{ $profileInfo->profileId }}" />
                    <input type="number" name="percentRate" id="percentRate" max="100"
                        placeholder="Enter New % Commission Rate" />
                    <button type="submit" class="btn btn-success btn btn-primary mb-4px">
                        Update Rate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
