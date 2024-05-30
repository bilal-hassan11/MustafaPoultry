@extends('layouts.admin')
@section('content')

<div class="main-content app-content mt-5">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- ROW-5 END -->
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0"> Purchase Feed Filters</h3>
                </div>
                <div class="card-body">
                <form action="" id="form">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="text-dark">Select Parent</label>
                            <select class="form-control select2" name="parent_head" id="parent_head" required>
                                <option value="" >----Select Parent----</option>
                                <option value="sale_feed" > Sale Feed </option>
                                <option value="purchase_feed" > Purchase Feed </option>
                                <option value="return_feed" > Return Feed </option>
                                <option value="purchase_murghi" >Purchase Murghi</option>
                                <option value="sale_murghi" >Sale Murghi</option>
                                <option value="purchase_chick" >Purchase Chick</option>
                                <option value="sale_chick" >Sale Chick </option>
                                <option value="purchase_medicine" >Purchase Medicine</option>
                                <option value="sale_medicine" >Sale Medicine</option>
                                <option value="return_medicine" >Return Medicine</option>
                                <option value="expire_medicine" >Expire Medicine</option>
                                
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-dark">Account</label>
                            <select class="form-control select2" name="parent_id" id="parent_id">
                                <option value="" >Select Account </option>
                                @foreach($accounts AS $account)
                                    <option value="{{ $account->hashid }}" >{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-dark">Items</label>
                            <select class="form-control select2" name="item_id" id="item_id">
                                <option value="" >Select Item </option>
                                @foreach($items AS $item)
                                    <option value="{{ $item->hashid }}" >{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">From Date</label>
                            <input type="date" class="form-control" name="from_date" id="from_date">
                        </div>
                        <div class="col-md-2">
                            <label for="">To Date</label>
                            <input type="date" class="form-control" name="to_date" id="to_date">
                        </div>
                        <div class="col-md-2">
                        <input type="submit" class="btn btn-primary float-right mt-4">
                        <button class="btn btn-danger mt-4" id="pdf">PDF</button>
                        </div>

                    </div>
                    
                </form>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Purchase Feed Detail</h3>
                </div>
                <div class="card-body">
                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>Date</th>
                            <th colspan="1"> Description </th>
                            <th> Dr </th>
                            <th> Cr </th>
                            <th> Balance </th>
                            <th> cr/dr </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    
                        @if(@$account_ledger != "")
                        
                                @if(@$account_parent == "Assets")
                                    @php
                                        $tot_balance = 0; $tot_deb=0;
                                        $tot_credit=0; $tot_bal = 0;
                                    @endphp
                                    <tr class="text-dark">
                                        <td> {{ date('d-M-Y', strtotime($from_date)) }}</td>
                                        <td > Opening Balance </td>
                                        @if(@$account_opening[0]->account_nature == "credit")
                                            <?php $tot_balance -= $account_opening[0]->opening_balance;?>
                                            <td><span class="waves-effect waves-light btn btn-primary-light">0</span></td>
                                            <td><span class="waves-effect waves-light btn btn-danger-light"> {{ $account_opening[0]->opening_balance }}</span></td>
                                            
                                            <td><span class="waves-effect waves-light btn btn-primary-light">{{ $account_opening[0]->opening_balance }}</span></td>
                                        
                                        @endif
                                        @if(@$account_opening[0]->account_nature == "debit")
                                            <?php $tot_balance += $account_opening[0]->opening_balance ?>
                                            <td><span class="waves-effect waves-light btn btn-success-light">{{ $account_opening[0]->opening_balance }}</span></td>
                                            <td><span class="waves-effect waves-light btn btn-primary-light">0</span></td>
                                            
                                            <td><span class="waves-effect waves-light btn btn-primary-light">{{ $tot_balance }}</span></td>
                                        @endif
                                        
                                        
                                        
                                        @if(@$account_opening[0]->account_nature == "debit")
                                            <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                                            <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                            @if(@$account_opening[0]->account_nature == "credit")
                                            <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                                                <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                        
                                        <td></td>
                                    </tr>
                                    <?php $tot_balance = $account_opening[0]->opening_balance ; ?>
                                    @foreach($account_ledger AS $ac)
                                        <?php @$tot_deb += $ac->debit; $tot_credit += $ac->credit;  ?>
                                        <tr class="text-dark">
                                            <td> {{ date('d-M-Y', strtotime($ac->created_at)) }}</td>
                                            <td >{{ @$ac->description }}</td>
                                            <td><span class="waves-effect waves-light btn btn-danger-light">{{ number_format(@$ac->debit) }}</span></td>
                                            <td><span class="waves-effect waves-light btn btn-success-light">{{  number_format(@$ac->credit) }}</span></td>
                                            
                                            <?php $tot_balance += $ac->debit - $ac->credit ;?>
                                            <td><span class="waves-effect waves-light btn btn-primary-light">{{ number_format($tot_balance) }}</span></td>
                                            
                                            @if($tot_balance > 0)
                                            <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                                            <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                            @if( @$tot_balance <= 0)
                                            <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                                                <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                            
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(@$account_parent == "Not Assets")
                                    @php
                                        $tot_balance = 0; $tot_deb=0;
                                        $tot_credit=0; $tot_bal = 0;
                                    @endphp
                                    <tr class="text-dark">
                                        <td> {{ date('d-M-Y', strtotime($from_date)) }}</td>
                                        <td > Opening Balance </td>
                                        @if(@$account_opening[0]->account_nature == "credit")
                                            <?php $tot_balance -= $account_opening[0]->opening_balance;?>
                                            <td><span class="waves-effect waves-light btn btn-primary-light">0</span></td>
                                            <td><span class="waves-effect waves-light btn btn-danger-light"> {{ $account_opening[0]->opening_balance }}</span></td>
                                            
                                            <td><span class="waves-effect waves-light btn btn-primary-light">{{ $account_opening[0]->opening_balance }}</span></td>
                                        
                                        @endif
                                        @if(@$account_opening[0]->account_nature == "debit")
                                            <?php $tot_balance += $account_opening[0]->opening_balance ?>
                                            <td><span class="waves-effect waves-light btn btn-success-light">{{ $account_opening[0]->opening_balance }}</span></td>
                                            <td><span class="waves-effect waves-light btn btn-primary-light">0</span></td>
                                            
                                            <td><span class="waves-effect waves-light btn btn-primary-light">{{ $tot_balance }}</span></td>
                                        @endif
                                        
                                        
                                        
                                    @if(@$account_opening[0]->account_nature == "debit")
                                            <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                                            <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                        @if(@$account_opening[0]->account_nature == "credit")
                                            <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                                            <?php @$tot_bal += @$tot_balance; ?>
                                        @endif
                                        
                                        <td></td>
                                    </tr>

                                    <?php $tot_balance = $account_opening[0]->opening_balance ; ?>

                                    @foreach($account_ledger AS $ac)
                                    <?php @$tot_deb += $ac->debit; $tot_credit += $ac->credit;  ?>
                                        <tr class="text-dark">
                                            <td> {{ date('d-M-Y', strtotime($ac->created_at)) }}</td>
                                            <td >{{ @$ac->description }}</td>
                                            <td><span class="waves-effect waves-light btn btn-danger-light">{{ number_format(@$ac->debit) }}</span></td>
                                            <td><span class="waves-effect waves-light btn btn-success-light">{{  number_format(@$ac->credit) }}</span></td>
                                            
                                            <?php $tot_balance += $ac->credit - $ac->debit ;?>
                                            <td><span class="waves-effect waves-light btn btn-primary-light">{{ number_format($tot_balance) }}</span></td>
                                            
                                            @if($tot_balance > 0)
                                            <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                                            <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                            @if( @$tot_balance <= 0)
                                            <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                                                <?php @$tot_bal += @$tot_balance; ?>
                                            @endif
                                            
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endif    
                        
                        @endif    
                    </tbody>
                    <tfoot>
                        <td colspan="2"></td>
                        @if(@$account_opening[0]->account_nature == "debit")
                            <td ><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_deb + $account_opening[0]->opening_balance ) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_credit) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(  @$account_opening[0]->opening_balance + @$tot_deb - @$tot_credit) == 0 ?"Nill":number_format($account_opening[0]->opening_balance + @$tot_deb - @$tot_credit) }}</span></td>

                        @endif

                        @if(@$account_opening[0]->account_nature == "credit")
                            <td ><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_deb  ) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format(@$tot_credit + $account_opening[0]->opening_balance) }}</span></td>
                            <td><span class="waves-effect waves-light btn btn-warning-light">{{ number_format($account_opening[0]->opening_balance + @$tot_deb - @$tot_credit) == 0 ?"Nill":number_format($account_opening[0]->opening_balance + @$tot_deb - @$tot_credit) }}</span></td>

                        @endif
                        
                    </tfoot>
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
<script>
    $('#pdf').click(function(event){
        event.preventDefault();
        var form_data = $('form').serialize();
        // console.log(form_data);
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.account_pdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "account_report.pdf";
                link.click();
                return false;
            },
            error: function(blob){
                console.log(blob);
            }
        });
    });
</script>
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