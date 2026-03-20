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
            <div class="skillszone-stats mt-12">
                <div class="row">
                    <div class="col-md-3 col-6 stat-item">
                        <div class="stat-value">10K+</div>
                        <div class="stat-label">Active Learners</div>
                    </div>
                    <div class="col-md-3 col-6 stat-item">
                        <div class="stat-value">500+</div>
                        <div class="stat-label">Expert Instructors</div>
                    </div>
                    <div class="col-md-3 col-6 stat-item">
                        <div class="stat-value">1000+</div>
                        <div class="stat-label">Course Units</div>
                    </div>
                    <div class="col-md-3 col-6 stat-item">
                        <div class="stat-value">8+</div>
                        <div class="stat-label">Countries Reached</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-12">
        <div class="col-sm-12">
            <div class="skillszone-why reveal-fade-up">
                <h2>Why Learn With SkillsZone?</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="why-card">
                            <i class="fa fa-laptop-code"></i>
                            <div class="why-title">Practical Learning</div>
                            <p>Learn job-ready skills with practical, real-world modules taught by professionals.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="why-card">
                            <i class="fa fa-mobile"></i>
                            <div class="why-title">Mobile-First Access</div>
                            <p>Study anywhere, anytime on your phone, tablet, or desktop with a smooth learning flow.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="why-card">
                            <i class="fa fa-coins"></i>
                            <div class="why-title">Value For Money</div>
                            <p>Affordable premium content with secure checkout and instant access to purchased units.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-12">
        <div class="col-sm-12">
            <div class="learning-journey reveal-fade-up">
                <h2>Your Learning Journey</h2>
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="journey-step">
                            <div class="journey-index">01</div>
                            <div class="journey-title">Discover</div>
                            <p>Explore curated units by category and learning goals.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="journey-step">
                            <div class="journey-index">02</div>
                            <div class="journey-title">Enroll</div>
                            <p>Buy the course unit and gain instant access to content.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="journey-step">
                            <div class="journey-index">03</div>
                            <div class="journey-title">Practice</div>
                            <p>Watch, read, and answer questions for deeper mastery.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="journey-step">
                            <div class="journey-index">04</div>
                            <div class="journey-title">Achieve</div>
                            <p>Apply your skills confidently in class, work, and business.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-12">
        <div class="col-sm-12">
            <div class="skillszone-testimonials reveal-fade-up">
                <h2>What Learners Say</h2>
                <div class="testimonial-slider" id="testimonialSlider">
                    <div class="testimonial-track">
                        <div class="testimonial-slide active">
                            <div class="testimonial-card">
                                <p>"The units are practical and easy to follow. I improved my skills in weeks."</p>
                                <div class="testimonial-meta">
                                    <strong>Ann N.</strong>
                                    <span>University Student</span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <p>"Great instructors, clean platform, and smooth payment process. Highly recommend."</p>
                                <div class="testimonial-meta">
                                    <strong>Brian K.</strong>
                                    <span>Young Professional</span>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-slide">
                            <div class="testimonial-card">
                                <p>"As a tutor, SkillsZone helped me reach more learners and monetize my expertise."</p>
                                <div class="testimonial-meta">
                                    <strong>Mary W.</strong>
                                    <span>Instructor</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-dots" id="testimonialDots">
                    <button type="button" class="testimonial-dot active" data-slide="0" aria-label="Show testimonial 1"></button>
                    <button type="button" class="testimonial-dot" data-slide="1" aria-label="Show testimonial 2"></button>
                    <button type="button" class="testimonial-dot" data-slide="2" aria-label="Show testimonial 3"></button>
                </div>
            </div>
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
    <div class="row mb-24">
        <div class="col-sm-12">
            <div class="skillszone-cta reveal-fade-up">
                <div class="cta-title">Ready to level up your skills?</div>
                <div class="cta-text">Join SkillsZone today and start learning from experts at your own pace.</div>
                <div class="mt-12">
                    <a href="{{ url('/course-units/1') }}" class="btn btn-warning">Explore Courses</a>
                    <a href="{{ url('/teach') }}" class="btn btn-primary ml-8">Become an Instructor</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="floating-actions">
    <a href="{{ url('/course-units/1') }}" class="fab-link">
        <i class="fa fa-compass"></i> Explore
    </a>
    <button type="button" class="fab-top" id="scrollTopBtn">
        <i class="fa fa-arrow-up"></i> Top
    </button>
</div>

<script>
    (function () {
        const slides = document.querySelectorAll('#testimonialSlider .testimonial-slide');
        const dots = document.querySelectorAll('#testimonialDots .testimonial-dot');
        const topBtn = document.getElementById('scrollTopBtn');
        let active = 0;
        let intervalId = null;

        function setSlide(index) {
            slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
            dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
            active = index;
        }

        function nextSlide() {
            const next = (active + 1) % slides.length;
            setSlide(next);
        }

        if (slides.length > 1) {
            intervalId = setInterval(nextSlide, 4500);
        }

        dots.forEach(dot => {
            dot.addEventListener('click', function () {
                const idx = parseInt(this.getAttribute('data-slide') || '0', 10);
                setSlide(idx);
                if (intervalId) {
                    clearInterval(intervalId);
                    intervalId = setInterval(nextSlide, 4500);
                }
            });
        });

        topBtn?.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();
</script>
@endsection
