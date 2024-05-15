@extends('layouts.admin')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
              <h2 class="box-title text-dark">Filters</h2>
            </div>
            <div class="box-body">
              <form action="{{ route('admin.staffs.all') }}" method="GET">
                @csrf
                <div class="row">
                  <div class="col-md-5">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="name" id="name">
                  </div>
                  <div class="col-md-5">
                    <label for="">Status</label>
                    <select class="form-control select2" name="status" id="status">
                        <option value="">Select status</option>
                        <option value="1">Active</option>
                        <option value="0">Deactive</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <input type="submit" class="btn btn-primary" value="Search">
                  </div>
                </div>
              </form>
            </div>
          </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title">All Staff Details</h4>
                    <a href="{{ route('admin.staffs.add') }}" class="btn btn-primary">Add Staff</a>
                </div><br />
                <table  class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100" id="example">
                    <thead>
                        <tr class="text-dark">
                            <th width="20">S.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Type</th>
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
                            <td><small class="badge bg-{{$staff->user_type == 'admin' ? 'danger' : 'info'}}">{{ ucwords($staff->user_type) }}</small></td>
                            <td>
                                <p class="m-0"><small>{{ get_date($staff->created_at) }}</small></p>
                            </td>
                            
                            <td class="text-center">
                                <div class="form-check form-switch">
                                    <input type="checkbox" onchange="ajaxRequest(this)" data-url="{{ route('admin.staffs.update_status', $staff->hashid) }}" {{ $staff->is_active ? 'checked' : ''}} class="form-check-input nopopup" id="staff_status_{{$k}}">
                                    <label class="form-check-label" for="staff_status_{{$k}}">{{$staff->is_active ? 'Active' : 'Disabled'}}</label>
                                </div>
                            </td>
                            <td width="120">
                                <a href="{{route('admin.staffs.edit', $staff->hashid)}}" >
                                <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                                </a>
                                <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.staffs.delete', $staff->hashid) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection