@extends('layouts.admin')
@section('content')

<div class="main-content app-content mt-6">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
              <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-info">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div>
                        <h1 class="mb-0 text-secondary">Total Stock Available (In Kg)</h1><br />
                        <h1 class="my-1 text-info">{{@$tot_stock}}</h1><br />
                        <p class="mb-0 font-13">+2.5% from last week</p>
                      </div>
                      <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-cart'></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-danger">
                  <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div>
                      <h1 class="mb-0 text-secondary">Total Stock Value</h1><br />
                      <h1 class="my-1 text-danger">{{ @$tot_Stock_value ? @$tot_Stock_value :0 }}</h1><br />
                      <p class="mb-0 font-13">+5.4% from last day</p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-wallet'></i>
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            
				  
				  </div>
        <!-- ROW-5 END -->
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Purchase Items Detail</h3>
                </div>
                <div class="card-body">
                
                <table id="example543" class="table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                <thead>
                <tr class="text-dark">
                    <th>Category</th>
                    <th>Item <br />Name</th>
                    <th>Available <br />Stock</th>
                    <th>Rate</th>
                    <th>Stock Value</th>
                    <th>Item <br /> Type</th>
                    <th>Stock <br /> Status</th>
                    <th>Item <br />Status</th>
                    <!--<th>Remarks</th>-->
                    <th>Action</th>
                </tr>
            </thead>
           <tbody>
               <?php $tot = 0; ?>
                @foreach($items AS $item)
                    <tr class="text-dark">
                        <td>{{ $item->category->name }}</td>
                        <td><span class="waves-effect waves-light btn btn-danger-light">{{ $item->name }}</span></td>
                        <td>{{ $item->stock_qty }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                         <?php $tot +=  $item->price * $item->stock_qty; ?>
                        <td>{{ $item->price * $item->stock_qty }}</td>
                        <td>{{ $item->type }}</td>
                        <td>
                            @if($item->stock_status == 1)
                                Enabled
                            @else
                                Disabled
                            @endif
                        </td>
                        <td>
                            @if($item->status == 1)
                                Active
                            @else
                                Deactive
                            @endif
                        </td>
                        <!--<td>{!! wordwrap($item->remarks, 10, "<br />\n", true) !!}</td>-->
                        <td width="120">
                            <a href="{{route('admin.items.edit', $item->hashid)}}" >
                            <span class="waves-effect waves-light btn btn-rounded btn-primary-light"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></span>

                            </a>
                            <!-- <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.items.delete', $item->hashid) }}"  class="waves-effect waves-light btn btn-rounded btn-primary-light">
                                <i class="fas fa-trash"></i>
                            </button> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
               <tr class="text-dark">
                   <td >Total:</td>
                   <td >-</td>
                   <td >-</td>
                   <td >-</td>
                   
                   <td>{{$tot}}</td>
                   <td >-</td>
                   <td >-</td>
                   <td >-</td>
               </tr>
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
<script>
  $('#example543').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
</script>
@endsection