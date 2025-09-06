@extends('layouts.app')

@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-6">
            @include('profile', ['profileInfo' => $profileInfo])
        </div>
        <div class="col-sm-6">
            @include('wallet', ['walletInfo' => $walletInfo])
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('transactions-history', ['transactions' => $transactions])
        </div>
    </div>
</div>
@endsection
