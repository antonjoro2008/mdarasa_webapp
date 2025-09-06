@extends('layouts.app')

@section('content')
<div>
    @include('top-nav')
    @include('student.main-nav')
</div>
<div class="container">
    <div class="row">
        <div class="d-sm-none col-12">
        </div>
        <div class="col-sm-3">
            @include('student.sidebar')
        </div>
        <div class="d-none d-sm-block col-sm-7">
            @include('student.carousel')
        </div>
        <div class="d-sm-none col-12">
            @include('student.carousel-mobile')
        </div>
        <div class="pr-0 d-none d-sm-block col-sm-2 col">
            <div class="placeholder-det">
                <img src="{{ url('/images/books.png') }}" alt="" width="150" />
                Revolutionalizing Knowledge acquisition and teaching for thousands of students
                and lecturers
            </div>
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
    <div class="row">
        <div class="col-sm-12">
            @include('instructor-summary')
        </div>
    </div>
    <div class="row mt-32">
        <div class="col-sm-12 about-us-summary mb-12">
            @include('about-summary')
        </div>
    </div>
</div>
@endsection
