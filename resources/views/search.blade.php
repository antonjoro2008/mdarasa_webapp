@extends('layouts.app')

@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            @include('search-items')
        </div>
    </div>
</div>
@endsection
