@extends('layouts.app')

@section('content')
<div>
    @include('admin.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-6">
            @include('profile', ['profileInfo' => $profileInfo])
        </div>
        <div class="col-sm-6">
            @include('admin.wallet', ['walletInfo' => $walletInfo, 'profileInfo' => $profileInfo])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('transactions-history', ['transactions' => $transactions])
        </div>
    </div>
</div>
@endsection
