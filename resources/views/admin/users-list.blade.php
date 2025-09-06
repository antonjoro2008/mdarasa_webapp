<div class="mt-12 card">
    <div class="card-header">Administration Portal Users</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><span class="title-color">First Name</span></div>
            <div class="col-md-3"><span class="title-color">Last Name</span></div>
            <div class="col-md-4"><span class="title-color">Email Address</span></div>
            <div class="col-md-2"><span class="title-color">Phone</span></div>
        </div>
        <div class="separator"></div>
        @foreach($users as $user)
        <div class="row">
            <div class="col-md-3"> {{ $user->firstName }} </div>
            <div class="col-md-3"> {{ $user->lastName }} </div>
            <div class="col-md-4"> {{ $user->email }} </div>
            <div class="col-md-2"> {{ $user->phone }} </div>
            <div class="separator"></div>
            @endforeach
        </div>
    </div>
