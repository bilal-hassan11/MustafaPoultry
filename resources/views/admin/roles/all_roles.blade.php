@extends('layouts.admin')
@section('content')
<div class="main-content app-content mt-6">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
      <!-- PAGE-HEADER --> 
      
      <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
      <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
      
      <!-- ROW-5 END -->
      
      <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                  <h3 class="card-title mb-0">All Roles Detail</h3>
                  <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Add New Role</a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <div id="data-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                          <table id="example54" class="table table-bordered text-nowrap mb-0 dataTable no-footer" role="grid" aria-describedby="data-table_info">

                            <thead>
                              <tr class="text-dark">
                                <th width="20">S.No</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Permissions</th>
                                <th>Added On</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach($roles as $k => $role)
                                <tr class="text-dark">
                                    <td>
                                        <p class="m-0 text-center">{{ $k + 1 }}</p>
                                    </td>
                                    <td><span class="waves-effect waves-light btn btn-rounded btn-primary-light">{{ $role->name }}</span></td>
                                    <td><small>{{ $role->slug }}</small></td>
                                    <td><small>{{ $role->description ?? 'N/A' }}</small></td>
                                    <td>
                                        <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                                    </td>
                                    <td>
                                        <p class="m-0"><small>{{ get_date($role->created_at) }}</small></p>
                                    </td>
                                    <td width="120">
                                      @if($role->slug !== 'super-admin')
                                          <div class="btn-list"> 
                                              <a href="{{route('admin.roles.edit', $role->hashid)}}" class="btn btn-icon btn-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit"> 
                                                  <i class="ri-pencil-fill lh-1"></i> 
                                              </a> 
                                              <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.roles.destroy') }}"  class="btn btn-icon btn-danger btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Delete" data-role_id="{{ $role->hashid }}">
                                                  <i class="ri-delete-bin-5-fill lh-1"></i>
                                              </button> 
                                          </div>
                                      @else
                                          <span class="badge bg-danger">Super Admin</span>
                                      @endif
                                    </td>
                                </tr>
                              @endforeach
                            </tbody>

                          </table>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- COL END --> 
      </div>
    </div>
    <!-- CONTAINER END --> 
  </div>
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection
