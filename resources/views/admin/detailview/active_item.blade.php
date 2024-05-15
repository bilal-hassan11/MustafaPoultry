@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Accounts</li>
                    <li class="breadcrumb-item active">All</li>

                </ol>
            </div>
            <h4 class="page-title">All Accounts</h4>
        </div>
    </div>
</div>

<div class="row">       
<div class="col-12">

<div class="box">
   <div class="box-header with-border">
     <h4 class="box-title">Individual column searching</h4>
   </div>
   <!-- /.box-header -->
   <div class="box-body">
       <div class="table-responsive">
         <table id="example5" class="text-fade table table-bordered" style="width:100%">
           <thead>
               <tr class="text-dark">
                    <th>Name</th>
                   <th>Price</th>
                   <th>Type</th>
                   
                   <th>Status</th>
                   <th>Available Stock</th>
                   <th>Created at</th>
                   
               </tr>
           </thead>
           <tbody>
                @foreach($active_item AS $ac)
                    <tr>
                        <td class="btn btn-sm btn-info-light" >{{ $ac->name }}</td>
                        <td>{{ $ac->price}}</td>
                        <td>{{ $ac->type}}</td>
                        <td class="btn btn-sm btn-info-light">{{ $ac->status = '1' ? "Active" : "Deactive"}}</td>
                        <td class="btn btn-sm btn-info-light">{{ $ac->stock_qty}}</td>                        
                        <td>{{ date('d-M-Y', strtotime($ac->created_at)) }}</td>
                        
                    </tr>
                @endforeach
               
           </tbody>
           <tfoot>
               <tr>
               <th>Id</th>
                    <th>Name</th>
                   <th>Position</th>
                   <th>date</th>
                   
               </tr>
           </tfoot>
       </table>
       </div>
   </div>
   <!-- /.box-body -->
 </div>
 <!-- /.box -->      
</div> 

</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection