@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Accounts</li>
                    <li class="breadcrumb-item active">All</li>

                </ol>
            </div>
            <h4 class="page-title">All Accounts</h4>
        </div>
    </div>
</div>

<div class="row">       
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title">Accounts</h4>
                    {{-- <a href="{{ route('admin.staffs.add') }}" class="btn btn-primary">Add Ac</a> --}}
                </div>
                <p class="sub-header">Following is the list of all the accounts.</p>
                <table class="table dt_table table-bordered w-100 nowrap table-responsive" id="laravel_datatable">
                    <thead>
                        <tr>
                            <th width="20">S.No</th>
                            <th>Grand Parent</th>
                            <th>Parent</th>
                            <th>Account <br ./>Name</th>
                            <th>Opening <br /> Balance</th>
                            <th>Opening <br /> Date</th>
                            <th>Account <br />Nature</th>
                            <th>Ageing</th>
                            <th>Commission</th>
                            <th>Discount</th>
                            <th>Account Status</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts AS $account)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ @$account->grand_parent->name }}</td>
                                <td>{{ @$account->parent->name }}</td>
                                <td>{{ $account->name }}</td>
                                <td>{{ number_format($account->opening_balance, 2) }}</td>
                                <td>{{ date('d-M-Y', strtotime($account->opening_date)) }}</td>
                                {{-- <td>
                                    @if($account->account_nature == 'credit')
                                        <span class="badge  badge-info">credit</span>
                                    @else
                                        <span class='badge badge-warning'>debit</span>
                                    @endif
                                </td> --}}
                                <td>{{ $account->account_nature }}</td>
                                <td>{{ $account->ageing }}</td>
                                <td>{{ $account->commission }} %</td>
                                <td>{{ $account->discount }} %</td>
                                <td><strong>{{ $account->status == 0 ? "Active":"Deactive"   }} </strong></td>
                                <td>{!! wordwrap($account->address, 10, "<br />\n", true) !!}</td>
                                
                                <td width="120">
                                    <a href="{{route('admin.accounts.edit', $account->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.accounts.delete', $account->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        {{-- @foreach($accounts AS $account)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ @$account->grand_parent->name }}</td>
                                <td>{{ $account->name }}</td>
                                <td width="120">
                                    <a href="{{route('admin.account_types.edit', $account->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.account_types.delete', $account->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach --}}
                        {{-- @foreach($staffs as $k => $staff)
                        <tr>
                            <td>
                                <p class="m-0 text-center">{{ $k + 1 }}</p>
                            </td>
                            <td>{{ $staff->full_name }}</td>
                            <td><small>{{ $staff->email }}</small></td>
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
                                <a href="{{route('admin.staffs.edit', $staff->hashid)}}" class="btn btn-warning btn-xs waves-effect waves-light">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.staffs.delete', $staff->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    
                        @endforeach --}}
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