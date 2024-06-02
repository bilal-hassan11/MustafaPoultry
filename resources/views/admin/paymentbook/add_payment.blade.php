@extends('layouts.admin')
@section('content')
<div class="main-content app-content mt-0">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">Add Payments Detail</h3>
                </div>
                <div class="card-body">
                
                <form action="{{ route('admin.paymentbooks.store') }}" class="ajaxForm" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                    
                            <label>Date</label>
                            <input class="form-control" type="date" name="date" value="{{ isset($is_update) ? date('Y-m-d', strtotime(@$edit_Payment->date)) : date('Y-m-d') }}"
                            required> 

                        </div>
                        <div class="col-md-3">
                            <label >Debtor Account</label>
                            <select class="form-control select2"  type="text" name="debtor_id" >
                                <option value="">Select account </option>
                                @foreach($accounts AS $account)
                                <option value="{{ $account->hashid }}" @if(@$edit_Payment->debtor_account_id == $account->id) selected @endif>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Ammount</label>
                            <input type="text" class="form-control" placeholder="Enter Amount Paid" value="{{ @$edit_Payment->debtor_ammount	 }}" name="debtor_amount" id="debtor_amount" required>
                        </div>
                        <div class="col-md-3">
                            <label for="">Creditor Account</label>
                            <select class="form-control select2"  type="text" name="creditor_id" >
                                <option value="">Select account </option>
                                @foreach($accounts AS $account)
                                <option value="{{ $account->hashid }}" @if(@$edit_Payment->creditor_account_id == $account->id) selected @endif>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label >Ammount</label>
                            <input type="text" class="form-control" placeholder="Enter Amount Receive" value="{{ @$edit_Payment->credit_ammount }}" name="creditor_amount" id="creditor_amount" required>
                        </div>
                    </div><br />
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Remarks</label>
                            <textarea class="form-control" name="remarks" value="{{ @$edit_Payment->remarks }}" id="remarks" cols="30" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 mt-2">
                            <input type="hidden" value="{{ @$edit_Payment->hashid }}" name="payment_id" id="payment_id">
                            <input type="submit" class="btn btn-primary" value="{{ (isset($is_update)) ? 'Update' : 'Add' }}">
                        </div>
                    </div>
                </form>
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
                    <h3 class="card-title mb-0">All Payments Detail</h3>
                </div>
                <div class="card-body">
                
                <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
						    <thead>
							    <tr class="text-dark">
                                    <th>Date</th>
                                    <th> Creditor Account </th>
                                    <th> Amount </th>
                                    <th> Debtor Account </th>
                                    <th> Amount </th>
                                    <th> Remarks  </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payment as $pay)
                                
                                    <tr class="text-dark">
                                        <td>{{ date('d-M-Y', strtotime($pay->date)) }}</td>
                                        <td>{{ @$pay->account->name }}</td>
                                        <td>{{ @$pay->credit_ammount }}</td>
                                        <td>{{ @$pay->d_account->name  }}</td>
                                        <td>{{ @$pay->debtor_ammount }}</td>
                                        
                                        <td>{{ @$pay->remarks }}</td>
                                        <td width="120">
                                            <div class="btn-list"> 
                                                <a  href="{{route('admin.paymentbooks.edit', $pay->hashid)}}" class="btn btn-icon btn-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit"> <i class="ri-pencil-fill lh-1"></i> </a> 
                                            </div>
                                            
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

@section('page-scripts')
@include('admin.partials.datatable')
@endsection