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
                    <h4 class="page-title">{{ (isset($is_update)) ? 'Edit' : 'Add' }} Expense</h4>
                </div>
                <div class="card-body">
                
                  <div class="card-block">
                    <div class="item_row">
                      
                        <form action="{{ route('admin.expenses.expensestore') }}" class="ajaxForm" method="POST">
                            @csrf
                            <div class="row">
                                
                                <div class="col-md-4 form-group">
                                <label for="">Expense Date</label>
                                <input type="date" class="form-control" placeholder="0" value="{{ isset($is_update) ? date('Y-m-d', strtotime(@$edit_expense->date)) : date('Y-m-d') }}"  name="date"  required>

                            </div>
                                <div class="col-md-4 form-group">
                                    <label for="">Expense Category</label>
                                <select class="form-control select2" name="category_id" id="company_id">
                                <option value=""> Select Category</option>
                                    @foreach(@$categories AS $c)
                                
                                        <option value="{{ $c->hashid }}" @if(@$edit_expense->category_id == $c->id) selected @endif>{{ $c->name }}</option>
                                    @endforeach
                            </select>
                                </div>
                            
                            <div class="col-md-4 form-group">
                                <label for="">Expense Ammount</label>
                                <input type="text" class="form-control" placeholder="0" value="{{ @$edit_expense->ammount }}"  name="ammount"  required>

                            </div>
                            

                        
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks" value="{{ @$edit_expense->remarks }}" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" value="{{ @$edit_expense->hashid }}" name="expense_id" id="expense_id">
                                    <input type="submit" value="{{ (isset($is_update) ? 'Update' : 'Add') }}" class="btn btn-primary mt-2 float-right" style="float:right">
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
                    <h3 class="card-title mb-0">All Expenses Detail </h3>
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
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th>Expense Ammount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tot_net_amt = 0; ?>
                                    @foreach($expenses AS $ex)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-M-Y', strtotime($ex->date)) }}</td>
                                                <td><span class="waves-effect waves-light btn btn-info-light">{{ @$ex->category->name }}</span></td>
                                                <?php $tot_net_amt +=  @$ex->ammount; ?>
                                                <td><span class="waves-effect waves-light btn btn-info-light">{{ @$ex->ammount }}</span></td>
                                                
                                                <td width="120">
                                                    <div class="btn-list"> 
                                                        <a  href="{{route('admin.expenses.expenseedit', $ex->hashid)}}" class="btn btn-icon btn-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit"> <i class="ri-pencil-fill lh-1"></i> </a> 
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-dark">
                                            <th >Total Expense :</th>
                                            <th>-</th>
                                            <th>-</th>
                                            <th>{{ @$tot_net_amt }}</th>
                                            <th>-</th>
                                        </tr>
                                    </tfoot>    
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
