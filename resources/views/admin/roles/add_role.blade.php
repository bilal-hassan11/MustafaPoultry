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
              <h3 class="card-title mb-0">{{ isset($role) ? 'Edit' : 'Add'}} Role</h3>
              <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back to Roles</a>
            </div>
            <div class="card-body">
              <form action="{{ isset($role) ? route('admin.roles.update', $role->hashid) : route('admin.roles.store') }}" class="ajaxForm" method="post">
                @csrf
                @if(isset($role))
                  <input type="hidden" name="role_id" value="{{ $role->hashid }}">
                @endif

                <div class="row g-4">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                      <input type="text" name="name" parsley-trigger="change" data-parsley-required placeholder="Enter role name" class="form-control" id="name" value="{{ $role->name ?? '' }}">
                    </div>
                  </div>
                </div>

                <div class="row g-4 mt-2">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="description" class="form-label">Description</label>
                      <textarea name="description" class="form-control" id="description" rows="3" placeholder="Enter role description">{{ $role->description ?? '' }}</textarea>
                    </div>
                  </div>
                </div>

                <hr class="my-4">

                <!-- Global Controls -->
                <div class="d-flex gap-2 mb-4">
                  <button type="button" id="selectAllPermissions" class="btn btn-primary">
                    <i class="ri-check-double-line me-1"></i>
                    Select All Permissions
                  </button>
                  <button type="button" id="deselectAllPermissions" class="btn btn-secondary">
                    <i class="ri-close-circle-line me-1"></i>
                    Deselect All Permissions
                  </button>
                </div>

                <!-- Permissions Grouped by Module -->
                <div class="row g-4">
                  @foreach($groupedPermissions as $module => $permissions)
                    @if(count($permissions) > 0)
                      <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border">
                          <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0">{{ $module }}</h5>
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input module-toggle" id="module_{{ Str::slug($module) }}" data-module="{{ Str::slug($module) }}">
                              <label class="form-check-label mb-0" for="module_{{ Str::slug($module) }}">
                                Select All
                              </label>
                            </div>
                          </div>
                          <div class="card-body">
                            <div class="row g-2">
                              @foreach($permissions as $permission)
                                <div class="col-6">
                                  <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input permission-checkbox module-{{ Str::slug($module) }}" 
                                           name="permissions[]" 
                                           id="permission_{{ $permission->id }}" 
                                           value="{{ $permission->name }}"
                                           {{ in_array($permission->name, $assigned_permissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                      {{ Str::after($permission->name, $module . ' ') }}
                                    </label>
                                  </div>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>

                <div class="row g-4 mt-4">
                  <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                      {{ isset($role) ? 'Update Role' : 'Add Role' }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-scripts')
<script>
  $(document).ready(function() {
    // Initialize module toggles on page load
    $('.module-toggle').each(function() {
      updateModuleToggle($(this).data('module'));
    });

    // Global select all
    $('#selectAllPermissions').on('click', function() {
      $('.permission-checkbox').prop('checked', true);
      $('.module-toggle').prop('checked', true);
    });

    // Global deselect all
    $('#deselectAllPermissions').on('click', function() {
      $('.permission-checkbox').prop('checked', false);
      $('.module-toggle').prop('checked', false);
    });

    // Module toggle
    $('.module-toggle').on('change', function() {
      const module = $(this).data('module');
      const isChecked = $(this).is(':checked');
      $('.module-' + module).prop('checked', isChecked);
    });

    // Individual permission change
    $('.permission-checkbox').on('change', function() {
      const classes = $(this).attr('class');
      const moduleClass = classes.match(/module-(\S+)/);
      if (moduleClass) {
        updateModuleToggle(moduleClass[1]);
      }
    });

    function updateModuleToggle(moduleSlug) {
      const total = $('.module-' + moduleSlug).length;
      const checked = $('.module-' + moduleSlug + ':checked').length;
      $('#module_' + moduleSlug).prop('checked', total === checked && total > 0);
    }
  });
</script>
@endsection
