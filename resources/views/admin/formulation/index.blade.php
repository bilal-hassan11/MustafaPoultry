@extends('layouts.admin')
@section('content')

<div class="row">       
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title">Formulations</h4>
                    {{-- <a href="{{ route('admin.staffs.add') }}" class="btn btn-primary">Add Ac</a> --}}
                </div><br />
                <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                    <thead>
                        <tr class="text-dark">
                            <th width="20">S.No</th>
                            <th>Sale Item</th>
                            <th>Sale Weight</th>
                            <th>Total Purchase Weight</th>
                            <th>View</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($formulations AS $formulation)
                            <tr class="text-dark">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $formulation->item->name }}</td>
                                <td>{{  $formulation->sale_weight }}</td>
                                <td>{{  $formulation->total_purchase_weight }}</td>
                                <td>
                                    <a href="{{route('admin.formulations.view', $formulation->hashid)}}" class="waves-effect waves-light btn btn-rounded btn-warning-light">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                                <td width="120">
                                    <a href="{{route('admin.formulations.edit', $formulation->hashid)}}" >
                                    <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                                    </a>
                                    <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.formulations.delete', $formulation->hashid) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        {{-- @foreach($accounts AS $account)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ @$account->grand_parent->name }}</td>
                                <td>{{ @$account->parent->name }}</td>
                                <td>{{ $account->name }}</td>
                                <td>{{ number_format($account->opening_balance, 2) }}</td>
                                <td>{{ date('d-M-Y', strtotime($account->opening_date)) }}</td>
                                <td>{{ $account->account_nature }}</td>
                                <td>{{ $account->ageing }}</td>
                                <td>{{ $account->commission }} %</td>
                                <td>{{ $account->discount }} %</td>
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