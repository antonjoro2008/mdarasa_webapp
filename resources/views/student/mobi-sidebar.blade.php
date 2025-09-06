<div class="sidebar collapse navbar-collapse d-sm-none" id="sidebarMobiCats">
    <ul>
        @if(!is_null(Session::get("token")) && strlen(Session::get("token")) > 0)
        <li class="nav-item">
            <a class="nav-link mobi-loggedin-color" href="{{ url('/account')}}">
                <i class="fa fa-user"></i> Account
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link mobi-loggedin-color" href="{{ url('/orders')}}">
                <i class="fa fa-shopping-basket"></i> Orders
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link mobi-loggedin-color" href="{{ url('/my-units') }}">
                <i class="fa fa-address-book"></i> My Units
            </a>
        </li>
        @endif
        @if(!is_null($categories))
        @foreach($categories as $category)
        <li onclick="loadMobiSubcategories({{ $category->categoryId }})">
            <a class="nav-link" href="#">
                <i class="fa fa-book"></i> {{ $category->categoryName}}
                <i class="fa fa-angle-right pull-right right-arrow"></i>
            </a>
        </li>
        @endforeach
        @endif
    </ul>
</div>
