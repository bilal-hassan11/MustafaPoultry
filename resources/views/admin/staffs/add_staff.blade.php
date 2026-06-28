@extends('layouts.admin')
@section('content')
<div class="main-content app-content mt-6">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
      <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      <div class="row">
        <div class="col-12 col-sm-12">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h3 class="card-title mb-0">{{ isset($user) ? 'Edit' : 'Add'}} Staff User</h3>
              <a href="{{ route('admin.staffs.all') }}" class="btn btn-secondary">Back to Staff</a>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.staffs.save') }}" class="ajaxForm" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                @if(isset($user) && $user->image)
                    <div class="mb-4 text-center">
                      <img src="{{check_file($user->image, 'user')}}" alt="{{ $user->full_name ?? 'No Image' }}" class="img-fluid fit-image avatar-xl rounded-circle">
                    </div>
                @endif

                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="first_name" class="form-label">First Name<span class="text-danger">*</span></label>
                      <input type="text" name="first_name" placeholder="Enter first name" class="form-control" id="first_name" value="{{ $user->first_name ?? '' }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="last_name" class="form-label">Last Name<span class="text-danger">*</span></label>
                      <input type="text" name="last_name" placeholder="Enter last name" class="form-control" id="last_name" value="{{ $user->last_name ?? '' }}">
                    </div>
                  </div>
                </div>

                <div class="row g-4 mt-2">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="username" class="form-label">Username<span class="text-danger">*</span></label>
                      <input type="text" @if (!isset($user)) name="username" @endif @if (isset($user)) disabled @endif placeholder="Enter username" class="form-control" id="username" value="{{ $user->username ?? '' }}">
                      @if (isset($user))
                        <input type="hidden" name="username" value="{{ $user->username }}">
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                      <input type="email" name="email" placeholder="Enter email address" class="form-control" id="email" value="{{ $user->email ?? '' }}">
                    </div>
                  </div>
                </div>

                <div class="row g-4 mt-2">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="role" class="form-label">Role<span class="text-danger">*</span></label>
                      <select class="form-control" name="role" id="role" required>
                        <option value="">Select Role</option>
                        @foreach ($roles as $role)
                          <option {{isset($user) && in_array($role->id, $user_roles ?? []) ? 'selected' : ''}} value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                @if(!isset($user))
                <div class="row g-4 mt-2">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                      <input type="password" name="password" placeholder="Enter password at least 8 characters long" class="form-control" id="password">
                    </div>
                  </div>
                </div>
                @else
                  <input type="hidden" value="{{ $user->hashid }}" name="user_id" />
                @endif

                <div class="row g-4 mt-2">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="profile_img" class="form-label">Profile Image</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="profile_img" id="profile_img" accept=".gif, .jpg, .png">
                          <label class="custom-file-label profile_img_label" for="profile_img">Choose file</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row g-4 mt-4">
                  <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                      {{ isset($user) ? 'Update Staff' : 'Add Staff' }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      @if(isset($user))
      <div class="row mt-4">
        <div class="col-12 col-sm-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mb-0">Update Password For Staff</h3>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.staffs.update_password') }}" class="ajaxForm" method="post">
                @csrf
                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="new_password" class="form-label">New Password<span class="text-danger">*</span></label>
                      <input type="password" name="password" placeholder="Enter password at least 8 characters long" class="form-control" id="new_password">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="password_confirmation" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                      <input type="password" name="password_confirmation" placeholder="Confirm new password" class="form-control" id="password_confirmation">
                    </div>
                  </div>
                </div>
                <div class="row g-4 mt-4">
                  <div class="col-md-12 text-end">
                    <input type="hidden" value="{{ $user->hashid }}" name="user_id" />
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                      Update Password
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('page-scripts')
<script>
    $('#profile_img').change(function() {
        var filename = $('#profile_img').val();
        if (filename.substring(3,11) == 'fakepath') {
            filename = filename.substring(12);
        }
        if(filename && filename != ''){
            $('.profile_img_label').html(filename);
        }else{
            $('.profile_img_label').html('Choose file');
        }
   });
</script>
@endsection
