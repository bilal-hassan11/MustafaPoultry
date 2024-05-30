@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Update Profile</li>
                </ol>
            </div>
            <h4 class="page-title">Update Profile</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title m-t-0">Update Profile</h4>
                <p class="text-muted font-14 m-b-20">
                    Update your profile
                </p>
    
                <form action="{{ route('admin.staffs.save_profile') }}" class="ajaxForm" method="post" enctype="multipart/form-data" novalidate>
                    @csrf
                    @if(isset($staff) && $staff->image)
                        <div class="form-group my-3">
                            <img src="{{check_file($staff->image, 'user')}}" alt="{{ $staff->full_name ?? 'No Image' }}" class="img-fluid cover-img fit-image avatar-xl rounded-circle">
                        </div>
                    @endif
                    <div class="form-group mb-3">
                        <label class="form-label" for="profile_img">Profile Image</label>
                        <input type="file" class="form-control" name="profile_img" id="profile_img" accept=".gif, .jpg, .png, .jpeg">
                    </div>
                    <div class="form-group mb-3">
                        <label for="full_name">Full Name<span class="text-danger">*</span></label>
                        <input type="text" name="full_name" placeholder="Enter full name" class="form-control" id="full_name" value="{{ $staff->name ?? '' }}" data-parsley-required>
                    </div>
    
                    <div class="form-group mb-3">
                        <label for="username">Username<span class="text-danger">*</span></label>
                        <input type="text" disabled placeholder="Enter username" class="form-control" id="username" value="{{ $staff->username ?? '' }}">
                    </div>
    
                    <div class="form-group mb-3">
                        <label for="email">Email<span class="text-danger">*</span></label>
                        <input type="text"  disabled placeholder="Enter email" class="form-control" id="email" value="{{ $staff->email ?? '' }}">
                    </div>
    
                    <div class="form-group mb-3">
                        <label for="cnic">CNIC<span class="text-danger">*</span></label>
                        <input type="text" name="cnic" placeholder="Enter CNIC format: 4xxxx-xxxxxxx-x" class="form-control" id="cnic" value="{{ $staff->cnic ?? '' }}" data-parsley-pattern="^[0-9]{5}-[0-9]{7}-[0-9]$" data-parsley-required>
                        <p class="m-0 text-muted"><small>CNIC format: 4xxxx-xxxxxxx-x</small></p>
                    </div>
    
                    <div class="form-group mb-3">
                        <label for="contact_no">Mobile Contact No<span class="text-danger">*</span></label>
                        <input type="text" name="contact_no" placeholder="Enter Mobile No format: 92-345-xxxxxxx" class="form-control" id="contact_no" value="{{ $staff->contact_no ?? '' }}" data-parsley-pattern="^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$" data-parsley-required>
                        <p class="m-0 text-muted"><small>Format: 92-345-xxxxxxx</small></p>
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title m-t-0 mb-3">Change Your Password</h4>
    
                <form action="{{ route('admin.staffs.change_password') }}" class="ajaxForm" method="post">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="old_password">Password<span class="text-danger">*</span></label>
                        <input type="password" name="old_password" parsley-trigger="change" data-parsley-required placeholder="Enter your current password" class="form-control" id="old_password">
                    </div>
                    <div class="form-group mb-3">
                        <label for="new_password">New Password<span class="text-danger">*</span></label>
                        <input type="password" name="password" parsley-trigger="change" data-parsley-minlength="8" data-parsley-required placeholder="Enter password atleast 8 charactes long" class="form-control" id="new_password">
                    </div>
                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirm New Password<span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" data-parsley-minlength="8" parsley-trigger="change" data-parsley-equalto="#new_password" data-parsley-required placeholder="Enter confirm password atleast 8 charactes long" class="form-control" id="password_confirmation">
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            Submit
                        </button>
                    </div>
    
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $('#cnic').mask("00000-0000000-0", {placeholder: "_____-_______-_"});
    $("#contact_no").mask("+92-300-0000000", {placeholder: "+__-___-_______"});
</script>

@endsection
