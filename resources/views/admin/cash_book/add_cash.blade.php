
@extends('layouts.admin')
@section('content')

<div class="main-content">

  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">  
          <div class="card-block">
            <div class="item_row">
              <div class="row">
              </div>
              <h1>Cash Book</h1><br />
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label style="font-size:20px;">Cash In Hand</label>
                    <input class="form-control" type="number" name="open_balance" value="{{@$cash_in_hand}}" readonly>
                  </div>
                </div>

              </div>
              <br />
              <h3>Receipts</h3><br />
              <form class="ajaxForm" role="form" action="{{ route('admin.cash.store') }}" method="POST" novalidate>
              @csrf
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Date</label>
                      <input class="form-control" type="date" required data-validation-required-message="This field is required"  name="date" value="{{ (isset($is_update_receipt)) ? date('Y-m-d', strtotime(@$edit_receipt->date)) : date('Y-d-d') }}" required>
                      
                    </div>
                  </div>
                  <input type="hidden" name="cash_id" value="{{ @$edit_receipt->hashid }}">
                  <input type="hidden" name="status" value="receipt">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Account </label>
                      <select class="form-control select2" style="width: 100%;" id="payment_account" type="text" name="account_id" >
                        <option value="">Select account </option>
                        @foreach($accounts AS $account)
                          <option value="{{ $account->hashid }}" @if(@$edit_receipt->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                        @endforeach
                        </select>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Instrument No </label>
                      <input class="form-control" name="bil_no" value="{{ @$edit_receipt->bil_no }}" required>
                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-8">
                      <div class="form-group">
                        <label style="margin-right:10px;">Narration</label>                     
                          <datalist id="cityname" >
                          @forelse($accounts AS $account)
                            <option style="width:300px;" value="{{ $account->name }}" @if(@$edit_receipt->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                            @empty
                            <option style="width:300px;" value="">No Account Found!</option>
                            
                          @endforelse
                          </datalist>
                          <input  class="form-control" name="narration" value="{{ @$edit_receipt->narration }}" autocomplete="on" style="width:920px;" list="cityname">
                      </div>
                    </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Amount </label>
                      <input class="form-control" type="number" name="receipt_ammount" value="{{ @$edit_receipt->receipt_ammount }}" >
                    </div>
                  </div>
                  <div class="col-md-1 mt-3">
                    <div class="form-group">
                      <button type="reset" class="btn btn-danger "><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp Reset</button>
                    </div>
                  </div>
                  <input type="hidden" name="cash_id" value="{{ @$edit_receipt->hashid }}">
                  <div class="col-md-1 mt-3">
                    <div class="form-group">
                      <button type="submit" value="{{ (@$is_update_receipt ? 'Update' : 'Add') }}" name="save_receipt" class="btn btn-success">&nbsp Save </button>

                    </div>
                  </div>
                </div>
              </form>
              <br />
              <h3>Payments</h3><br />
              <form class="ajaxForm" role="form" action="{{ route('admin.cash.store') }}" method="POST" novalidate>
              @csrf
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Date</label>
                      <input class="form-control" type="date" name="date" value="{{ (@$is_update_payment) ? date('Y-m-d', strtotime($edit_payment->date)) : date('Y-d-d') }}" required>
                    </div>
                  </div>
                  <input type="hidden" name="cash_id" value="{{ @$edit_payment->hashid }}">
                  <input type="hidden" name="status" value="payment">
                  <div class="col-md-6">
                    <div class="form-group ">
                      <label>Account </label>
                      <select class="form-control select2" id="payment_account" type="text" name="account_id"   >
                        <option value="">Select account </option>
                        @foreach($accounts AS $account)
                          <option value="{{ $account->hashid }}" @if(@$edit_payment->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                        @endforeach
                        </select>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Instrument No </label>
                      <input class="form-control" name="bil_no" value="{{ @$edit_payment->bil_no }}" required>
                    </div>
                  </div>

                </div>
                
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label style="margin-right:10px;">Narration</label>                     
                        <datalist id="cityname" >
                        @forelse($accounts AS $account)
                          <option style="width:300px;" value="{{ $account->name }}" @if(@$edit_payment->account_id == $account->id) selected @endif>{{ $account->name }}</option>
                          @empty
                          <option style="width:300px;" value="">No Account Found!</option>
                          
                        @endforelse
                        </datalist>
                        <input  class="form-control" name="narration" autocomplete="on" value="{{ @$edit_payment->narration }}" style="width:920px;" list="cityname">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Amount </label>
                      <input class="form-control" name="payment_ammount" value="{{ @$edit_payment->payment_ammount }}" required>
                    </div>
                  </div>
                  <div class="col-md-1 mt-3 ">
                    <div class="form-group">
                      <button type="reset" class="btn btn-danger "><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp Reset</button>
                    </div>
                  </div>
                  <input type="hidden" name="cash_id" value="{{ @$edit_payment->hashid }}">
                  <div class="col-md-1 mt-3 ">
                    <div class="form-group">

                      <button type="submit" value="{{ (isset($is_update_payment) ? 'Update' : 'Add') }}" name="save_payment" class="btn btn-success">&nbsp Save </button>
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
        </div>
      </div>
    </div>
    <div class="box">
      <div class="box-header with-border">
        <h2 class="box-title text-dark">Filters</h2>
      </div>
      <div class="box-body">
        <form action="{{ route('admin.cash.index') }}" method="GET">
          @csrf
          <div class="row">
            <div class="col-md-4">
              <label for="">Grand Parent</label>
              <select class="form-control select2" name="grand_parent_id" id="grand_parent_id">
                <option value="">Select grand parent</option>
                @foreach($account_types AS $type)
                  @foreach($type->childs AS $child)
                    <option value="{{ $child->hashid }}">{{ $type->name }}--{{ $child->name }}</option>
                  @endforeach
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label for="">Accounts</label>
              <select class="form-control select2" name="parent_id" id="parent_id">
                <option value="">Select  Account</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="">Status</label>
              <select class="form-control select2" name="status" id="status">
                <option value="">Select status</option>
                <option value="payment">Payment</option>
                <option value="receipt">Reciept</option>
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
            <div class="col-md-2 mt-3">
              <input type="submit" class="btn btn-primary" value="Search">
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="box">
				<div class="box-header with-border">
				  <h2 class="box-title text-dark">All Cash Book Entries</h2>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
          <table id="example" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
          <thead>
                <tr class="text-dark">
                  <th>Id.No</th>
                  <th>Date</th>
                  <th> Account Name </th>
                  <th> Payment </th>
                  <th> Receipt </th>
                  <th>Action</th>
                  </tr>
            </thead>
            <tbody>
              <?php $total_receipt = 0; $total_payment = 0; ?>
              @foreach($cash AS $c)
                <tr class="text-dark">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d-M-Y', strtotime($c->date)) }}</td>
                    <td ><span class="waves-effect waves-light btn btn-primary-light">{{ $c->account->name }}</span></td>
                    <?php $total_receipt += $c->receipt_ammount; $total_payment +=$c->payment_ammount; ?>
                    <td>{{ $c->payment_ammount }}</td>
                    <td>{{ $c->receipt_ammount }}</td>
                    <td width="120">
                      <a href="{{route('admin.cash.edit', $c->hashid)}}" >
                      <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fas fa-edit"></i></span>
                      </a>
                      <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.cash.delete', $c->hashid) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                          <i class="fas fa-trash"></i>
                      </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
            <tr class="text-dark">
                  <td colspan="3">Total:</td>
                  <td><strong><?= @$total_payment ?></strong></td>
                  <td><strong><?= @$total_receipt ?></strong></td>
                  
                </tr>
            </tfoot>
          </table>
					</div>              
				</div>
				<!-- /.box-body -->
			  </div>
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
<script>
  $('#grand_parent_id').change(function(){
    var id    = $(this).val();
    var route = "{{ route('admin.cash.get_parent_accounts', ':id') }}";
    route     = route.replace(':id', id);

   if(id != ''){
      getAjaxRequests(route, "", "GET", function(resp){
        $('#parent_id').html(resp.html);
      });
    }
  })
</script>
@endsection