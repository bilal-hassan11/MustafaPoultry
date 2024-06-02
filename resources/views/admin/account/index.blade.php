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
                    <h3 class="card-title mb-0">All Accounts Detail</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                      <div id="data-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                          
                            <div class="row">
                              <div class="col-sm-12">
                                <table id="data-table" class="table table-bordered text-nowrap mb-0 dataTable no-footer" role="grid" aria-describedby="data-table_info">
                                  <thead>
                                      <tr <span class="text-dark">>
                                          <th width="20">S.No</th>
                                          <th>Grand Parent</th>
                                          <th>Parent</th>
                                          <th>Account <br ./>Name</th>
                                          <th>Opening <br /> Balance</th>
                                          <th>Opening <br /> Date</th>
                                          <th>Account <br />Nature</th>
                                          
                                        
                                          <th>Account Status</th>
                                          <th>Address</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php $tot = 0; ?>
                                      @foreach($accounts AS $account)
                                          <tr class="text-dark" >
                                              <td>{{ $loop->iteration }}</td>
                                              <td>{{ @$account->grand_parent->name }}</td>
                                              <td>{{ @$account->parent->name }}</td>
                                              <td><span class="waves-effect waves-light btn btn-danger-light">{{ $account->name }}</span></td>
                                              <?php $tot += $account->opening_balance; ?>
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
                                            
                                            
                                              <td><strong>{{ $account->status == 0 ? "Active":"Deactive"   }} </strong></td>
                                              <td>{!! wordwrap($account->address, 10, "<br />\n", true) !!}</td>
                                              
                                              <td width="120">
                                                <div class="btn-list"> 
                                                  <a  href="{{route('admin.accounts.edit', $account->hashid)}}" class="btn btn-icon btn-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit"> <i class="ri-pencil-fill lh-1"></i> </a> 
                                                </div>
                                              </td>
                                          </tr>
                                      @endforeach
                                      
                                  </tbody>
                                  <tfoot>
                                      <tr class="text-dark">
                                        <td colspan="4">Total:</td>
                                        <td>{{$tot}}</td>
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
          <!-- COL END --> 
        </div>
        <!-- ROW-5 END --> 
    </div>
    <!-- CONTAINER END --> 
  </div>
</div>



@endsection

@section('page-scripts')

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
   });
</script>
@endsection