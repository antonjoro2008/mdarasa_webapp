<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark main-nav sticky full-width ph-8" data-bs-theme="dark"
    onmouseenter="hideSubs()">
    <div class="full-width">
        <div class="row">
            <div class="col-md-3 col-4 d-none d-sm-block">
                <a class="navbar-brand" href="/">
                    <img src="{{ url('/images/logo.png')}}" class="logo" alt="MDARASA">
                </a>
            </div>
            <div class="d-sm-none col-3">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMobiCats"
                    aria-controls="sidebarMobiCats" aria-expanded="false" aria-label="Toggle navigation"><span
                        class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="col-4 d-sm-none">
                <a class="navbar-brand pt-0" href="/">
                    <img src="{{ url('/images/logo.png')}}" class="logo" alt="MDARASA">
                </a>
            </div>
            <div class="col-2 d-sm-none flex-vertical-center">
                <a class="nav-link active" href="{{ url('/cart') }}">
                    <i class="fa fa-shopping-cart"></i> (<span id="mobiCartItems">0</span>)
                </a>
            </div>
            <div class="col-2 d-sm-none flex-vertical-center">
                <a class="nav-link" href="{{ url('/contact-us') }}">
                    <i class="fa fa-phone"></i>
                </a>
            </div>
            <div class="d-sm-none mobi-separator"></div>
            <div class="col-md-5 col-12">
                <form class="d-flex mt-8" role="search" action="{{ url('/search') }}">
                    @csrf
                    <input class="form-control me-2" type="search" name="keyword" placeholder="Search"
                        aria-label="Search">
                    <button type="submit" class="btn search-bt" type="submit">Search</button>
                </form>
            </div>
            <div class="col-md-4 col-12">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-8">
                        @if(!is_null(Session::get("token")) && strlen(Session::get("token")) > 0)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/account')}}">
                                <i class="fa fa-user"></i> Account
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('/cart') }}">
                                <i class="fa fa-shopping-cart"></i> Cart (<span id="cartItems">0</span>)
                            </a>
                        </li>
                        @if(is_null(Session::get("token")) || strlen(Session::get("token")) == 0)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/contact-us') }}">
                                <i class="fa fa-phone"></i> Contact Us
                            </a>
                        </li>
                        @endif
                        @if(!is_null(Session::get("token")) && strlen(Session::get("token")) > 0)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/orders')}}">
                                <i class="fa fa-shopping-basket"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/my-units') }}">
                                <i class="fa fa-address-book"></i> My Units
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>