@extends('layouts.admin')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title"> Manufacture item</h4>
                </div>
                <form action="{{ route('admin.manufactures.store') }}" class="ajaxForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Items</label>
                            <select class="form-control select2" name="item_id" id="item_id" required>
                                <option value="">Select Item</option>
                                @foreach($items AS $item)
                                    <option value="{{ $item->hashid }}" @if(@$edit_manufacture->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" value="{{ @$edit_manufacture->quantity }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="">Dispatch</label>
                            <input type="number" class="form-control" name="dispatch" id="dispatch" value="{{ @$edit_manufacture->dispatch }}" required>
                        </div>
                    </div>
                    <input type="hidden" name="manufacture_id" id="manufacture_id" value="{{ @$edit_manufacture->hashid }}">
                    <input type="submit" class="btn btn-primary mt-3" value="{{ isset($is_update) ? 'Update' : 'Add' }}">
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
              <h2 class="box-title text-dark">Filters</h2>
            </div>
            <div class="box-body">
              <form action="{{ route('admin.consumptions.index') }}" method="GET">
                @csrf
                <div class="row">
                <div class="col-md-4">
                    <label for="">Items</label>
                    <select class="form-control select2" name="item_id" id="item_id" required>
                        <option value="">Select Item</option>
                        @foreach($items AS $item)
                            <option value="{{ $item->hashid }}" @if(@$edit_consumption->item_id == $item->id) selected @endif>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="">From</label>
                    <input type="date" class="form-control" name="from_date" id="from_date">
                  </div>
                  <div class="col-md-4">
                    <label for="">To</label>
                    <input type="date" class="form-control" name="to-date" id="to-date">
                  </div>
                  <div class="col-md-2">
                    <input type="submit" class="btn btn-primary" value="Search">
                  </div>
                </div>
              </form>
            </div>
          </div>
    </div>         --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title">All Manufacture Items</h4>
                    {{-- <a href="{{ route('admin.staffs.add') }}" class="btn btn-primary">Add Ac</a> --}}
                </div><br /> 
                <table  class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100" id="example">
                    <thead>
                        <tr class="text-dark">
                            <th >S.No</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Dispatch</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($manufactures AS $manufacture)
                            <tr class="text-dark">
                                <td>{{ $loop->iteration }}</td>
                                <td> <span class="waves-effect waves-light btn btn-rounded btn-primary-light">{{ $manufacture->item->name }} </span></td>
                                <td>{{ $manufacture->quantity}}</td>
                                <td>{{ $manufacture->dispatch}}</td>

                                <td>{{ date('d-M-Y', strtotime($manufacture->created_at)) }}</td>
                                <td >
                                    <a href="{{route('admin.manufactures.edit', $manufacture->hashid)}}" >
                                    <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>

                                    </a>
                                    <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.manufactures.delete', $manufacture->hashid) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
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
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection