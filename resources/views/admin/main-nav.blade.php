<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark main-nav sticky  full-width" data-bs-theme="dark">
    <div class="full-width">
        <div class="row">
            <div class="col-md-2 col-4"><a class="navbar-brand" href="/">
                    <img src="{{ url('/images/logo.png')}}" class="logo" alt="MDARASA"></a>
            </div>
            <div class="d-sm-none col-8">
                <button class="navbar-toggler pull-right" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="col-md-10 col-12">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-8">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/course-units') }}">
                                <i class="fa fa-book"></i> Course Units
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/course/units/featured') }}">
                                <i class="fa fa-book"></i> Featured
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/lecturers') }}">
                                <i class="fas fa-chalkboard-teacher"></i> Lecturers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/students') }}">
                                <i class="fa fa-graduation-cap"></i> Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/orders') }}">
                                <i class="fa fa-shopping-basket"></i> Student Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/deposits') }}">
                                <i class="fa fa-wallet"></i> Deposits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/withdrawals') }}">
                                <i class="fa fa-coins"></i> Withdrawals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin/users') }}">
                                <i class="fa fa-user"></i> Users
                            </a>
                        </li>
                    </ul>
                    @if(!is_null(Session::get("token")) && strlen(Session::get("token")) > 0)
                    <div class="fg-color mr-8">
                        Welcome <span class="logged-in-color"> {{ Session::get("admFirstName") }}</span>
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
