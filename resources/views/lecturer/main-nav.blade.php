<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark main-nav sticky  full-width" data-bs-theme="dark">
    <div class="full-width">
        <div class="row">
            <div class="col-md-3 col-4"><a class="navbar-brand" href="/">
                    <img src="{{ url('/images/logo.png')}}" class="logo" alt="MDARASA"></a>
            </div>
            <div class="d-sm-none col-8">
                <button class="navbar-toggler pull-right" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="col-md-9 col-12">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-8">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/lecturer') }}">
                                <i class="fa fa-book"></i> Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/lecturer/units') }}">
                                <i class="fa fa-book"></i> Course Units
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/lecturer/topics') }}">
                                <i class="fa fa-tasks"></i> Unit Topics</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/lecturer/subtopics') }}">
                                <i class="fa fa-volume-up"></i> Unit Subtopics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/lecturer/orders') }}">
                                <i class="fa fa-shopping-basket"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/lecturer/my-students') }}">
                                <i class="fa fa-graduation-cap"></i> My Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/lecturer/account') }}">
                                <i class="fa fa-user"></i> Account
                            </a>
                        </li>
                    </ul>
                    @if(!is_null(Session::get("token")) && strlen(Session::get("token")) > 0)
                    <div class="fg-color mr-8">
                        Welcome <span class="logged-in-color"> {{ Session::get("firstName") }}</span>
                        <a class="nav-link" href="{{ url('/logout') }}" style="display:inline;">
                            <i class="fa fa-sign-out" title="Logout"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>
