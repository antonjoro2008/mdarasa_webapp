<div class="sidebar">
    <ul>
        @foreach($lecturers as $lecturer)
        <li>
            <a class="nav-link"
                href={{ url('/category/lecturer') }}/{{ $lecturer->categoryId }}/{{ $lecturer->profileId }}>
                <i class="fa fa-book"></i> {{ $lecturer->salutation}} {{ $lecturer->firstName}} {{ $lecturer->lastName}}
                <i class="fa fa-angle-right pull-right right-arrow"></i>
            </a>
        </li>
        @endforeach
    </ul>
</div>