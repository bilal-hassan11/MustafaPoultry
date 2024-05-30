

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
                        <h3 class="card-title mb-0">Add Expense Category</h3>
                    </div>
                    <div class="card-body">
                    
                        <div class="card-block">
                            <div class="item_row">
                            
                                <form action="{{ route('admin.expenses.store') }}" class="ajaxForm" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-10">
                                            <label for="">Category Name</label>
                                            <input type="text" class="form-control" placeholder="Enter category name" value="{{ @$edit_category->name }}" name="name" id="name" required>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <input type="hidden" value="{{ @$edit_category->hashid }}" name="category_id" id="category_id">
                                            <input type="submit" class="btn btn-primary" value="{{ (isset($is_update)) ? 'Update' : 'Add' }}">
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
                        <h3 class="card-title mb-0">All Expenses Detail</h3>
                    </div>
                    <div class="card-body">
                        <table id="example54" class="text-fade table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="20">S.No</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories AS $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="waves-effect waves-light btn btn-info-light">{{ @$category->name }}</span></td>
                                        <td width="120">
                                            <a href="{{route('admin.categories.edit', $category->hashid)}}" >
                                                <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>
                                            </a>
                                            <!--<button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.categories.delete', $category->hashid) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">-->
                                            <!--    <i class="fas fa-trash"></i>-->
                                            <!--</button>-->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    
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



