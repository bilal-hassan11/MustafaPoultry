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
            <div class="card-header">
              <h3 class="card-title mb-0">All Staff Users Filters</h3>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.staffs.all') }}" method="GET">
                @csrf
                <div class="row g-3">
                  <div class="col-md-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ request('name') }}">
                  </div>
                  <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" name="status" id="status">
                      <option value="">Select status</option>
                      <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                      <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Deactive</option>
                    </select>
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Search</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-12 col-sm-12">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h3 class="card-title mb-0">All Staff Detail</h3>
              @can('Staffs Create')
              <a href="{{ route('admin.staffs.add') }}" class="btn btn-primary">Add Staff</a>
              @endcan
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
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Added On</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($staffs as $k => $staff)
                            <tr class="text-dark">
                              <td>
                                <p class="m-0 text-center">{{ $k + 1 }}</p>
                              </td>
                              <td>{{ $staff->full_name }}</td>
                              <td> <span class="waves-effect waves-light btn btn-rounded btn-primary-light">{{ $staff->email }}</span></td>
                              <td>
                                @foreach($staff->roles as $role)
                                  <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                              </td>
                              <td>
                                <p class="m-0"><small>{{ get_date($staff->created_at) }}</small></p>
                              </td>
                              <td class="text-center">
                                @can('Staffs Edit')
                                <div class="form-check form-switch">
                                    <input type="checkbox" onchange="ajaxRequest(this)" data-method="GET" data-url="{{ route('admin.staffs.update_status', $staff->hashid) }}" {{ $staff->is_active ? 'checked' : ''}} class="form-check-input nopopup" id="staff_status_{{$k}}">
                                    <label class="form-check-label" for="staff_status_{{$k}}">{{$staff->is_active ? 'Active' : 'Disabled'}}</label>
                                </div>
                                @endcan
                              </td>
                              <td width="120">
                                <div class="btn-list"> 
                                  @can('Staffs Edit')
                                  <a href="{{route('admin.staffs.edit', $staff->hashid)}}" class="btn btn-icon btn-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit"> 
                                    <i class="ri-pencil-fill lh-1"></i> 
                                  </a> 
                                  @endcan
                                  @can('Staffs Delete')
                                  <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.staffs.delete') }}"  class="btn btn-icon btn-danger btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Delete" data-user_id="{{ $staff->hashid }}">
                                    <i class="ri-delete-bin-5-fill lh-1"></i>
                                  </button> 
                                  @endcan
                                </div>
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
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection
