@extends('layouts.app')

@section('content')
<div>
    @include('lecturer.main-nav')
</div>
<div class="container accounts">
    <div class="row">
        <div class="col-sm-12">
            @include('lecturer.students-list', ['lecturerStudents' => $lecturerStudents])
        </div>
    </div>
</div>
@endsection
