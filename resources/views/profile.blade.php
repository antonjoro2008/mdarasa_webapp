<div class="mt-12 card">
    <div class="card-header">Profile Information</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-md-12">
                        <span class="title-color">Salutation:</span> {{ $profileInfo->salutation }}.
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="title-color">First Name:</span> {{ $profileInfo->firstName }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="title-color">Last Name:</span> {{ $profileInfo->lastName }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="title-color">Email:</span> {{ $profileInfo->email }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="title-color">Phone:</span> {{ $profileInfo->phone }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="title-color">Your Referral Code:</span> {{ $profileInfo->referralCode }}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="file-upload">
                    <div class="image-upload-wrap">
                        @if(!$profileInfo->profilePhoto)
                        <img id="previewProfilePhoto" src="{{ url('/images/empty-profile-photo.png') }}" width="190"
                            alt="" />
                        @else
                        <img id="previewProfilePhoto" src="/profilephotos/{{ $profileInfo->profilePhoto }}" width="190"
                            alt="" />
                        @endif
                    </div>
                    <form id="profilePhotoForm" method="post" action="{{ url('profile/photo/upload') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input id="profilePhoto" class="file-upload-input" type='file' accept="image/*"
                            onchange="showPreview(event)" name="profilePhoto" />
                    </form>
                    <div class="file-upload-content">
                        <div>
                            <button type="button" class="save-image mt-12" onclick="submitProfilePhoto()">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>