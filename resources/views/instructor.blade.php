@extends('layouts.app')

@section('content')
<div class="mb-24">
    <div>
        @include('top-nav')
        @include('student.main-nav')
    </div>
    <div class="container">
        <div class="row mt-24 mb-24">
            <div class="col-sm-6 instructor-main">
                <div>
                    <div class="instructor-title">Start Teaching with us Today</div>
                    <div class="mb-12">
                        Instructors from various universities, colleges and business leaders teach thousands of students
                        on
                        mDarasa. We provide the tools and skills to teach what you love.
                    </div>
                    <button type="button" class="btn btn-primary instructor-btn" data-bs-toggle="modal"
                        data-bs-target="#registerLecturerModal">
                        Get Started
                    </button>
                </div>
                <div class="mt-12">
                    <div class="instructor-title">Are you a Learning Institution?</div>
                    <div class="mb-12">
                        You can sign up as an institution, add your content and reach out to many learners beyond your
                        physical limits. It's that simple!
                    </div>
                    <button type="button" class="btn btn-primary instructor-btn" data-bs-toggle="modal"
                        data-bs-target="#registerInstitutionModal">
                        Register as an Institution
                    </button>
                </div>
            </div>
            <div class="col-sm-6 d-none d-sm-block">
                <img src="{{ url('/images/business-man.png') }}" width="" height="" alt="" />
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title h5">Create, Monetize and Earn in Quick Simple Steps</div>
                        <ol style="padding-left:16px !important;">
                            <li class="mb-8">
                                <div class="title-color">Create & Publish</div>
                                Easily create and publish your own engaging courses, tutorials, and content tailored to
                                your expertise. Let your creativity shine as you design interactive learning
                                experiences.
                            </li>
                            <li class="mb-8">
                                <div class="title-color">Set Your Pricing</div>
                                Take full control of your earnings by setting your own pricing. Decide the value of your
                                content and attract your ideal audience with flexible payment options.
                            </li>
                            <li class="mb-8">
                                <div class="title-color">Reach Your Audience</div>
                                With mDarasa, your potential clients and the general public can easily discover your
                                content. Our robust marketing channels ensure that your message reaches the right people
                                at the right time.
                            </li>
                            <li class="mb-8">
                                <div class="title-color">Withdraw Anytime</div>
                                Say goodbye to waiting for payouts. Withdraw the amount paid by your subscribers
                                whenever you desire. Your hard work should be rewarded instantly.
                            </li>
                            <li class="mb-8">
                                <div class="title-color">Revenue Share</div>
                                We believe in fair partnerships. Earn a generous 75% of the revenue generated from your
                                content, while mDarasa receives 25% to support platform maintenance and development.
                                Don't miss this opportunity to join a thriving community of trainers and educators at
                                mDarasa Learning Hub. Together, let's transform the way knowledge is shared and empower
                                learners in Africa and beyond.
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="registerLecturerModal" tabindex="-1" aria-labelledby="registerLecturerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">Sign up as an Instructor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('/register/lecturer') }}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="spot_group_identifier">Salutation</label>
                                <select class="form-control @error('salutation') is-invalid @enderror" id="salutation"
                                    name="salutation">
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Miss.">Miss.</option>
                                    <option value="Dr.">Dr.</option>
                                    <option value="Prof.">Prof.</option>
                                </select>
                                @error('salutation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                                    id="firstName" name="firstName" placeholder="Enter your First Name" value="">
                                @error('firstName')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                                    id="lastName" name="lastName" placeholder="Enter your Last Name" value="">
                                @error('lastName')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="msisdn">Phone Number</label>
                                <input type="text" class="form-control @error('msisdn') is-invalid @enderror"
                                    id="msisdn" name="msisdn" placeholder="Enter your phone number" value="">
                                @error('msisdn')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" placeholder="Enter your email" value="">
                                <small class="muted-text form-text">We'll never share your email or phone number
                                    with
                                    anyone
                                    else.</small>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="profileSummary">Profile Summary (Not More than 100 Words)</label>
                                <textarea name="profileSummary" id="profileSummary"></textarea>
                                @error('profileSummary')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Enter your password" value="">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="confirmpassword">Confirm Password</label>
                                <input type="password"
                                    class="form-control @error('confirmpassword') is-invalid @enderror"
                                    id="confirmpassword" name="confirmPassword" placeholder="Confirm password" value="">
                                @error('confirmpassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary modal-main-action-btn">Sign Up</button>
                    <button type="button" class="btn btn-danger modal-close-btn" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="registerInstitutionModal" tabindex="-1" aria-labelledby="registerInstitutionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4">Sign up as an Institution</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('/register/institution') }}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="institutionName">Institution Name</label>
                                <input type="text" class="form-control @error('institutionName') is-invalid @enderror"
                                    id="institutionName" name="institutionName"
                                    placeholder="Enter your Institution Name" value="">
                                @error('institutionName')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="msisdn">Phone Number</label>
                                <input type="text" class="form-control @error('msisdn') is-invalid @enderror"
                                    id="msisdn" name="msisdn" placeholder="Enter your phone number" value="">
                                @error('msisdn')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" placeholder="Enter your email" value="">
                                <small class="muted-text form-text">We'll never share your email or phone number
                                    with
                                    anyone
                                    else.</small>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="institutionProfileSummary">Institution Profile Summary (Not More than 100
                                    Words)</label>
                                <textarea name="institutionProfileSummary" id="institutionProfileSummary"></textarea>
                                @error('profileSummary')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Enter your password" value="">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="confirmpassword">Confirm Password</label>
                                <input type="password"
                                    class="form-control @error('confirmpassword') is-invalid @enderror"
                                    id="confirmpassword" name="confirmPassword" placeholder="Confirm password" value="">
                                @error('confirmpassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary modal-main-action-btn">Sign Up</button>
                    <button type="button" class="btn btn-danger modal-close-btn" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
