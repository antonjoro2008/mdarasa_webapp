@extends('layouts.app')

@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-9">
            @include('student.mobi-sidebar')
            @include('student.cartdetails')
        </div>
        <div class="col-sm-3">
            @include('student.cartsummary')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('student.featured')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('student.new-arrivals')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @include('student.popular')
        </div>
    </div>
</div>
@endsection
