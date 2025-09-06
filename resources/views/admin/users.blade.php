@extends('layouts.app')

@section('content')
<div>
    @include('admin.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-12">
            <button type="button" class="btn btn-success pull-right mt-12" onclick="toggleForm('addUserForm')">
                New</button>
        </div>
        <div class="separator mt-8 mb-8"></div>
    </div>
    <div class="row" id="addUserForm">
        <div class="col-sm-12">
            @include('admin.user-form')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('admin.users-list', ['users' => $users])
        </div>
    </div>
</div>
@endsection
