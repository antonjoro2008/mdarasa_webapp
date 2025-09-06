@extends('layouts.app')
@section('content')
<div>
    @include('admin.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.course-units-list', ['courseUnits' => $courseUnits])
        </div>
    </div>
</div>
@endsection
