<div class="small-header">
    <i class="fa fa-star"></i>
    @if(is_null(Session::get("token")) || strlen(Session::get("token")) == 0 || is_null(Session::get("profileId")))
    <a class="sm-header-right-a" href="{{ url('/teach') }}">Be a Tutor on mDarasa</a>
    @elseif(Session::get("role") == "LECTURER")
    <a class="sm-header-right-a" href="{{ url('/lecturer') }}">Go to Lecturer's View</a>
    @else
    <a class="sm-header-right-a" href="{{ url('/teach') }}">Be a Tutor on mDarasa</a>
    @endif
    <nav class="sm-header-right">
        @if(is_null(Session::get("token")) || strlen(Session::get("token")) == 0 || is_null(Session::get("profileId")))
        <div>
            <button type="button" class="clear-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fa fa-sign-in"></i> Sign In |
            </button>
            <button type="button" class="clear-btn" data-bs-toggle="modal" data-bs-target="#registrationModal">
                <i class="fa fa-registered"></i> Sign Up
            </button>
        </div>
        @else
        <div title="Share your referral code with new learners and earn 5% for every course they purchase">
            @if(!Session::get('profilePhoto'))
            <img src="{{ url('/images/empty-profile-photo.png') }}" class="top-bar-img" alt="" width="20" />
            @else
            <img src="/profilephotos/{{ Session::get('profilePhoto') }}" class="top-bar-img" alt="" width="20" />
            @endif
            {{ Session::get('firstName') }} @if(Session::get('referralCode'))(<span
                class="mobi-loggedin-color">{{ Session::get('referralCode') }}</span>)
            @endif
            <a href="{{ url('/logout') }}" title="Logout">
                <i class="fa fa-sign-out"></i>
            </a>
        </div>
        @endif
    </nav>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-defaults">
                <div class="modal-header">
                    <h5 class="modal-title h4">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ url('/login') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Enter your email" value="">
                            <small class="muted-text form-text">We'll never share your email with anyone else.</small>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Enter your password" value="">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="signin-as-lecturer"
                                            name="signInAsLecturer">
                                        <label class="form-check-label" for="signin-as-lecturer">
                                            Sign In as a Lecturer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary modal-main-action-btn">Sign In</button>
                            <button type="button" class="btn btn-danger modal-close-btn"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                        <div class="pt-8">
                            <a href="{{ url('/forgot-password') }}">
                                Forgot Password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4">Sign up as a Learner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ url('/register') }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="profile_type">Your Account Type</label><br />
                                    <input type="radio" id="learner" name="profileType" value="STUDENT"
                                        checked="checked">
                                    <label for="html">Learner</label>
                                    <input type="radio" id="guardian" name="profileType" value="GUARDIAN">
                                    <label for="css">Parent/Guardian</label><br>
                                    @error('profile_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="salutation">Salutation</label>
                                    <select class="form-control @error('salutation') is-invalid @enderror"
                                        id="salutation" name="salutation">
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
                                    <input type="firstName"
                                        class="form-control @error('firstName') is-invalid @enderror" id="firstName"
                                        name="firstName" placeholder="Enter your First Name" value="">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="lastName">Last Name</label>
                                    <input type="lastName" class="form-control @error('lastName') is-invalid @enderror"
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
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Enter your email" value="">
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
                                        id="confirmpassword" name="confirmPassword" placeholder="Confirm password"
                                        value="">
                                    @error('confirmpassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-3">
                                <label for="referredBy">Referral Code</label>
                                <input type="referredBy" class="form-control @error('referredBy') is-invalid @enderror"
                                    id="referredBy" name="referredBy" placeholder="Enter your referrer code (Optional)"
                                    value="">
                                @error('referredBy')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary modal-main-action-btn">Sign Up<button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-danger modal-close-btn"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                    <div class="modal-small-txt mt-8">Already Registered?
                        <span class="modal-action-txt" data-bs-target="#loginModal"> Sign In Instead</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>