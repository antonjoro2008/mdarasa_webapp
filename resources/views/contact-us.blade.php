@extends('layouts.app')

@section('content')
<div class="mb-24">
    <div>
        @include('top-nav')
        @include('student.main-nav')
    </div>
    <div class="accounts container">
        @include('student.mobi-sidebar')
        <div class="mt-12 card">
            <div class="card-header">Get in Touch with us Today for any Inquiries!</div>
            <div class="card-body">
                <div class="row">
                    <div class="contact-details-align col-md-6">
                        <div>
                            <div class="title-color">CONTACT DETAILS</div>
                            <div>MDARASA Limited</div>
                            <div>3rd Floor, Orbit Place</div>
                            <div>Murang'a Road</div>
                            <div>P.O. Box 00000 – 00100</div>
                            <div>Nairobi, Kenya</div>
                            <div>Tel: +254 708068851</div>
                            <div>Mobile: +254 708068851</div>
                            <div>Email: support@mdarasa.com</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form class="">
                            <div class="mb-3"><label class="form-label" for="name">Your Name</label><input
                                    placeholder="Enter your Name" type="text" id="name" class="form-control"></div>
                            <div class="mb-3"><label class="form-label" for="email">Your Email Address</label><input
                                    placeholder="Enter your email address" type="text" id="email"
                                    class="form-control"><small class="muted-text form-text">We'll never share your
                                    email with anyone else.</small></div>
                            <div class="mb-3"><label class="form-label" for="msisdn">Phone Number</label><input
                                    placeholder="Enter your mobile number" type="text" id="msisdn"
                                    class="form-control"><small class="muted-text form-text">We'll never share your
                                    phone number with anyone else.</small></div>
                            <div class="mb-3"><label class="form-label" for="message">Message</label><textarea rows="5"
                                    placeholder="Your Message to Us" id="message" class="form-control"></textarea></div>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
