@extends('layouts.admin')
@section('content')

<div class="main-content app-content mt-5">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
       
       
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0"> DayBook Filters</h3>
                </div>
                <div class="card-body">
                <form action="{{ route('admin.reports.daybook_report') }}" method="GET">
                              @csrf
                          <div class="row">
                            
                            <div class="col-md-8">
                              <input type="date" class="form-control" name="from_date" value="{{ (isset($is_update)) ? date('d-m-Y', strtotime($from_date)) : date('d-m-Y') }}" id="from_date">
                            </div>
                            
                            <div class="col-md-2">
                              <input type="submit" class="btn btn-primary" value="Search">
                               <button class="btn btn-danger" id="pdf">PDF</button>
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
                  
                    <div class="card-body">
                        <center><h1 class="page-title text-primary">DayBook Report</h1><br /></center>
                    
                    </div>
                </div>
            </div>
          
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Purchase Medicine Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php  @$tot_purchase_val = 0;  ?>
                        @foreach($purchase_medicine AS $a)
                            <tr class="text-dark">
                                <th>{{ @$a->id }}</th>
                                <td> {{ @$a->account->name }}</td>
                                <td> Item Name : {{ @$a->item->name }} , Rate : {{ @$a->rate }} , Quantity : {{ @$a->quantity }} </td>
                                <?php $tot_purchase_val += @$a->purchase_ammount; ?>
                                <td><span class="waves-effect waves-light btn btn-success-light"> {{ @$a->purchase_ammount }}</span></td>
                                <td><span class="waves-effect waves-light btn btn-info-light">0</span></td>
                                <td>0</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>{{ @$tot_purchase_val }}</th>
                        <th>0</th>
                        <th>0</th>
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Sale Medicine Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php @$tot_sm_val = 0; ?>
                                
                        @foreach($sale_medicine AS $sm)
                            <tr class="text-dark">
                                <th>{{ @$sm->id }}</th>
                                <td> {{ @$sm->account->name }}</td>
                                <td> Item Name : {{ @$sm->item->name }} , Rate : {{ @$sm->rate }} , Quantity : {{ @$sm->quantity }} </td>
                                <td><span class="waves-effect waves-light btn btn-success-light"> 0 </span></td>
                                <?php @$tot_sm_val += @$sm->sale_ammount; ?>
                                
                                <td><span class="waves-effect waves-light btn btn-info-light"> {{ @$sm->sale_ammount }}</span></td>
                                <td><span class="waves-effect waves-light btn btn-danger-light"> 0</span></td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>0</th>
                        <th> {{@$tot_sm_val }} </th>
                        <th>0</th>
                        
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Return Medicine Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                        </tr>
                    </thead>
                    <tbody>
                
                       <?php @$tot_rm_val = 0; ?>
                        @foreach($return_medicine AS $rm)
                            <tr class="text-dark">
                                <th>{{ @$rm->id }}</th>
                                <td> {{ @$rm->account->name }}</td>
                                <td> Item Name : {{ @$rm->item->name }} , Rate : {{ @$rm->rate }} , Quantity : {{ @$rm->quantity }} </td>
                                <td> 0 </td>
                                <td> 0</td>
                                <?php @$tot_rm_val += @$rm->net_ammount; ?>
                                
                                <td><span class="waves-effect waves-light btn btn-danger-light"> {{ @$rm->net_ammount }}</span></td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>0</th>
                        <th>0</th>
                        <th> {{@$tot_rm_val }} </th>
                        
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                  
                    <div class="card-body">
                        <center><h1 class="page-title text-primary">Feed Report</h1><br /></center>
                    
                    </div>
                </div>
            </div>
          
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Purchase Feed Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php @$tot_pf_val = 0; ?>
                                
                        @foreach($purchase_feed AS $pf)
                            <tr class="text-dark">
                                <th>{{ @$pf->id }}</th>
                                <td> {{ @$pf->account->name }}</td>
                                <td><span class="waves-effect waves-light btn btn-danger-light"> Item Name : {{ @$pf->item->name }} , Rate : {{ @$pf->rate }} , Quantity : {{ @$pf->quantity }} Begs </span> </td>
                                <?php @$tot_pf_val += @$rm->net_ammount; ?>
                                
                                <td> {{ @$pf->net_ammount }}</td>
                                <td> 0 </td>
                                <td>0</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>{{ @$tot_pf_val }}</th>
                        <th>0</th>
                        <th> 0 </th>
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All sale Feed Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php @$tot_sf_val = 0; ?>
                        @foreach($sale_feed AS $sf)
                            <tr class="text-dark">
                                <th>{{ @$sf->id }}</th>
                                <td> {{ @$sf->account->name }}</td>
                                <td><span class="waves-effect waves-light btn btn-danger-light"> Item Name : {{ @$sf->item->name }} , Rate : {{ @$sf->rate }} , Quantity : {{ @$sf->quantity }} Begs </span> </td>
                                <td> 0 </td>
                                <?php @$tot_sf_val += @$sf->net_ammount; ?>
                                <td> {{ @$sf->net_ammount }}</td>
                                <td>0</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>0</th>
                        <th>{{ @$tot_sf_val }}</th>
                        <th>0</th>
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Return Feed Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php @$tot_rf_val = 0;  ?>
                        @foreach($return_feed AS $rf)
                            <tr class="text-dark">
                                <th>{{ @$rf->id }}</th>
                                <td> {{ @$rf->name }}</td>
                                <td><span class="waves-effect waves-light btn btn-danger-light"> Item Name : {{ @$rf->item->name }} , Rate : {{ @$rf->rate }} , Quantity : {{ @$rf->quantity }} Begs </span> </td>
                                <td> 0</td>
                                <td> 0</td>
                                <?php @$tot_rf_val += @$rf->net_ammount ?>
                                <td><span class="waves-effect waves-light btn btn-danger-light">{{ @$rf->net_ammount }}</span></td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>0</th>
                        <th>0</th>
                        <th> {{ @$tot_rf_val }} </th>
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                  
                    <div class="card-body">
                        <center><h1 class="page-title text-primary">chick Report</h1><br /></center>
                    
                    </div>
                </div>
            </div>
          
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Purchase Chick Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php  @$tot_cp_val = 0;  ?>
                        @foreach($purchase_chick AS $cp)
                            <tr class="text-dark">
                                <th>{{ @$cp->id }}</th>
                                <td> {{ @$cp->account->name }}</td>
                                <td><span class="waves-effect waves-light btn btn-danger-light">Invoice No:{{@$cp->invoice_no}}, Rate : {{ @$cp->rate }} , Quantity : {{@$cp->quantity}}</span></td>
                                <td>{{ @$cp->net_ammount }}</td>
                                <td> 0 </td>
                                <?php @$tot_cp_val += @$cp->net_ammount; ?>
                                <td>0</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>{{ @$tot_cp_val }}</th>
                        <th> 0 </th>
                        <th> 0 </th>
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Sale Chick Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                    <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php  @$tot_cs_val = 0;  ?>
                        @foreach($sale_chick AS $cs)
                            <tr class="text-dark">
                                <th>{{ @$cs->id }}</th>
                                <td> {{ @$cs->account->name }}</td>
                                <td><span class="waves-effect waves-light btn btn-danger-light">Invoice No:{{@$cs->invoice_no}}, Rate : {{ @$cp->rate }} , Quantity : {{@$cp->quantity}}</span></td>
                                <td> 0 </td>
                                <td>{{ @$cp->net_ammount }}</td>
                                <?php @$tot_cp_val += @$cp->net_ammount; ?>
                                <td>0</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th> 0 </th>
                        <th>{{ @$tot_cs_val }}</th>
                        <th> 0 </th>
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                  
                    <div class="card-body">
                        <center><h1 class="page-title text-primary">Murghi Report</h1><br /></center>
                    
                    </div>
                </div>
            </div>
          
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Purchase Murghi Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                        <thead>
                            <tr class="text-dark">
                                <th>ID</th>
                                <th>Account Name</th>
                                <th>Description</th>
                                <th>Purchase Ammount</th>
                                <th>Sale Ammount</th>
                                <th>Return Ammount</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                            <?php @$tot_pmg_val = 0;  ?>
                            @foreach($purchase_murghi AS $pmg)
                                <tr class="text-dark">
                                    <th>{{ @$pmg->id }}</th>
                                    <td> {{ @$pmg->account->name }}</td>
                                    <td> <span class="waves-effect waves-light btn btn-danger-light"> Item Name : {{ @$pmg->item->name }} , Rate : {{ @$pmg->rate }} , Quantity : {{ @$pmg->final_weight }} </span> </td>
                                    <?php @$tot_pmg_val += @$pmg->net_ammount;  ?>
                                    <td> {{ @$pmg->net_ammount }}</td>
                                    <td>0</td>
                                    <td> 0</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tr class="text-dark">
                            <th colspan="3">Total :</th>
                            <th>{{ @$tot_pmg_val }}</th>
                            <th> 0 </th>
                            <th> 0 </th>
                        </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All Sale Murghi Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                <thead>
                        <tr class="text-dark">
                            <th>ID</th>
                            <th>Account Name</th>
                            <th>Description</th>
                            <th>Purchase Ammount</th>
                            <th>Sale Ammount</th>
                            <th>Return Ammount</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php  @$tot_sm_val = 0;  ?>
                        @foreach($sale_murghi AS $sm)
                            <tr class="text-dark">
                                <th>{{ @$sm->id }}</th>
                                <td> {{ @$sm->account->name }}</td>
                                <td> <span class="waves-effect waves-light btn btn-danger-light"> Item Name : {{ @$sm->item->name }} , Rate : {{ @$sm->rate }} , Quantity : {{ @$sm->net_weight }} </span> </td>
                                <td> 0</td>
                                <?php  @$tot_sm_val += @$sm->net_ammount;  ?>
                                <td> {{ @$sm->net_ammount }}</td>
                                <td>0</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>0</th>
                        <th> {{@$tot_sm_val }} </th>
                        <th>0</th>
                    </tr>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                  
                    <div class="card-body">
                        <center><h1 class="page-title text-primary">CashBook Report</h1><br /></center>
                    
                    </div>
                </div>
            </div>
          
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                  
                    <div class="card-body">
                    <h4 style="color:red; float:right;">Opening Cash In Hand : {{number_format(@$account_opening)}}</h4>
                    
                    </div>
                </div>
            </div>
          
        </div>
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <h3 class="card-title mb-0">All CashBook Detail</h3>
                </div>
                <div class="card-body">
                <table id="example" class="text-fade table table-bordered" style="width:100%">
                <thead>
                        <tr class="text-dark">
                            <th>Date</th>
                            <th>Account Name</th>
                            <th colspan="1"> Narration </th>
                            <th> Cr </th>
                            <th> Dr </th>
                            <th> Balance </th>
                            <th> cr/dr </th>
                        </tr>
                    </thead>
                    <tbody>
                
                         @if(@$cashbook != "")
                       
                           
                                @php
                                    $tot_balance = 0; $tot_payment=0;
                                    $tot_receipt=0; $tot_bal = 0;
                                @endphp
                                <tr class="text-dark">
                                    <td> {{ date('d-M-Y', strtotime(@$date)) }}</td>
                                    <td > Opening Balance </td>
                                    
                                    
                                        <?php @$tot_balance += @$account_opening; ?>
                                        <?php @$tot_payment += @$account_opening ;?>
                                        
                                        
                                        <td>-</td>
                                        <td><span class="waves-effect waves-light btn btn-primary-light">0</span></td>
                                        <td><span class="waves-effect waves-light btn btn-success-light">{{ @$account_opening }}</span></td>
                                        <td><span class="waves-effect waves-light btn btn-primary-light">{{ @$tot_balance }}</span></td>
                                    
                                        <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                                        
                                    
                                    <td></td>
                                </tr>
                                <?php $tot_balance = $tot_balance ; ?>
                                @foreach($cashbook AS $c)
                                    
                                    <tr class="text-dark">
                                        <td> {{ date('d-M-Y', strtotime(@$c->date)) }}</td>
                                       <td ><span class="waves-effect waves-light btn btn-success-light">{{ @$c->account->name }}</span></td>
                                        
                                        <td >{{ @$c->narration }}</td>
                                        <?php @$tot_payment += @$c->payment_ammount ;?>
                                        <td><span class="waves-effect waves-light btn btn-danger-light">{{ number_format(@$c->payment_ammount) }}</span></td>
                                        <?php $tot_receipt += $c->receipt_ammount;?>
                                        <td><span class="waves-effect waves-light btn btn-success-light">{{  number_format(@$c->receipt_ammount) }}</span></td>
                                        
                                        <?php @$tot_balance += @$c->receipt_ammount - @$c->payment_ammount ;?>
                                        <td><span class="waves-effect waves-light btn btn-primary-light">{{ number_format(@$tot_balance) }}</span></td>
                                        
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
                                <tr class="text-dark">
                                    <th>{{ date('d-M-Y', strtotime(@$date)) }}</th>
                                    <th colspan="1"> Expense </th>
                                    <th><span class="waves-effect waves-light btn btn-primary-light">{{ @$day_exp }}</span>  </th>
                                    <th><span class="waves-effect waves-light btn btn-primary-light"> 0</span> </th>
                                    <th> <span class="waves-effect waves-light btn btn-primary-light">{{ @$tot_balance - @$day_exp }} </span> </th>
                                    <th> <span class="waves-effect waves-light btn btn-primary-light">dr</span> </th>
                                    
                                </tr>
                            @endif
                    </tbody>
                    <tfoot>
                        <tr class="text-dark">
                        <td>Total:</td>
                        <td colspan="1">-</td>
                        <td >-</td>
                        
                        <td><span class="waves-effect waves-light btn btn-danger-light">{{ number_format(@$debit) }}</span></td>
                        <td><span class="waves-effect waves-light btn btn-success-light">{{  number_format(@$credit) }}</span></td>
                        <?php @$account_opening + @$debit  ?>
                        
                        <td><span class="waves-effect waves-light btn btn-primary-light">{{  number_format(@$tot_balance - @$day_exp) }} Closing Balance</span></td>
                        
                        @if(@$tot_balance > 0)
                        <td><span class="waves-effect waves-light btn btn-primary-light">dr</span></td>
                        <?php @$tot_bal += @$tot_balance; ?>
                        @endif
                        @if( @$tot_balance <= 0)
                        <td><span class="waves-effect waves-light btn btn-info-light">cr</span></td>
                            <?php @$tot_bal += @$tot_balance; ?>
                        @endif
                        
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
                
            </div>
              </div>
          </div>
          <!-- COL END --> 
        </div>
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card ">
                  
                    <div class="card-body">
                    <h4 style="color:blue; float:right;">Closing Cash In Hand : {{number_format(@$tot_balance - @$day_exp)}}</h4>
                    
                    </div>
                </div>
            </div>
          
        </div>
    </div>
    <!-- CONTAINER END --> 
  </div>
</div>



@endsection

@section('page-scripts')
<script>
  $('#example54').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example46').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example40').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example45').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example44').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example53').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example52').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example51').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example50').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example49').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example48').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
    $('#example47').DataTable( {
    "aaSorting": [[ 0, "desc" ]],
		dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ]
	} );
</script>
<script>
    $('#pdf').click(function(event){
        event.preventDefault();
        var form_data = $('form').serialize();
        // console.log(form_data);
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.reports.DayBookPdf') }}",
            data: form_data,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response){
                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "DayBook-Report.pdf";
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