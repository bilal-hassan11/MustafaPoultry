@extends('layouts.admin')
@section('content')
   
<div class="main-content app-content mt-5">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
       
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">Add Company Details</h3>
                </div>
                <div class="card-body">
                
                  <div class="card-block">
                    <div class="item_row">
                      
                    <form class="ajaxForm" role="form" action="{{ route('admin.companys.store') }}" method="POST" novalidate>
                      @csrf
                        <div class="row">
                          
                          <input type="hidden" name="company_id" value="{{ @$edit_company->hashid }}">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label>Categories </label>
                              <select class="form-control select2" name="category" id="category">
                                <option value="">Select Category</option>
                                @foreach($categories AS $c)
                                  <option value="{{ $c->hashid }}" @if(@$edit_company->category_id == $c->id) selected @endif>{{ $c->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label>Name</label>
                              <input class="form-control" name="name" value="{{ @$edit_company->name }}" required>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label>Phone No</label>
                              <input class="form-control" name="phone_no" value="{{ @$edit_company->phone_no }}" >
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label>Status </label>
                                <select class="form-control select2" name="status" id="status">
                                  <option value="enable">Enable</option>
                                  <option value="disable">Disable</option>
                                </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="">Address</label>
                                <textarea class="form-control" name="address" value="{{ @$edit_company->address }}"  cols="30" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 ">
                                <div class="form-group">
                                    <button type="submit" value="Add" name="save_company" class="btn btn-primary"> Add </button>
                                </div>
                            </div>
                        </div>
                      </form>
                      <br /><br />
                    </div>

                  </div>
                </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <!-- ROW-5 END -->
        
       
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Companies </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                      <div id="data-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                          
                            <div class="row">
                              <div class="col-sm-12">
                                <table id="example54" class="table table-bordered text-nowrap mb-0 dataTable no-footer" role="grid" aria-describedby="data-table_info">
                                  <thead>
                                    <tr class="text-dark">
                                        <th>Category</th>
                                        <th>Company Name</th>
                                        <th>Phone No</th>
                                        <th>Status</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                      @foreach($companies AS $c)
                                      <tr>
                                          <td class="text-dark">{{ $c->category->name }}</td>
                                          <td><span class="waves-effect waves-light btn btn-success-light">{{ $c->name }}</span></td>
                                          <td>{{ $c->phone_no }}</td>
                                          <td>{{ $c->status }}</td>
                                          <td>{{ $c->address }}</td>
                                          <td width="120">
                                              <div class="btn-list"> 
                                                <a  href="{{route('admin.companys.edit', $c->hashid)}}" class="btn btn-icon btn-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit"> <i class="ri-pencil-fill lh-1"></i> </a> 
                                                    
                                              </div>
                                              
                                              <!-- <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.companys.delete', $c->hashid) }}"  class="btn btn-danger btn-xs waves-effect waves-light">
                                                  <i class="fas fa-trash"></i>
                                              </button> -->
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
    <!-- CONTAINER END --> 
  </div>
</div>

@endsection
