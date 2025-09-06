<div class="mt-12 card">
    <div class="card-header">Add Admin User</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <form method="post" action="{{ url('/admin/add-user') }}">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="spot_group_identifier">Salutation</label>
                                <select class="form-control @error('salutation') is-invalid @enderror" id="salutation">
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
                            <div class="mb-3">
                                <label class="form-label" for="firstName">First Name</label>
                                <input placeholder="Enter First Name" type="text" id="firstName" name="firstName"
                                    class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="lastName">Last Name</label>
                                <input placeholder="Enter Last Name" type="text" id="lastName" name="lastName"
                                    class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input placeholder="Enter Phone Number" type="text" id="phone" name="phone"
                                    class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="email">Email Address</label>
                                <input placeholder="Enter Email Address" type="email" id="email" name="email"
                                    class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input placeholder="Enter Password" type="password" id="password" name="password"
                                    class="form-control" value="" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="conf-password">Confirm Password</label>
                                <input placeholder="Re-enter password to confirm" type="password" id="confirmPassword"
                                    name="confirmPassword" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
