<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DayBook Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <style>
       .challan_details {
    display: flex;
    /* align-items: center; */
    justify-content: space-between;
    margin: 25px 0px;
    width: 100%;
}
.bottom{
    margin-top: 100px;
}
table,th,td{
    border: 1px solid #000;
    padding: 6px;
    font-size: 14px;
}
p{
    font-size: 13px;
}
h3{
    margin-top: 50px;
    text-decoration: underline;
}
h4{
    margin-top: 15px;
}
h5{
    margin: 30px 0px;
    color: rgb(24, 94, 225);
    font-size: 18px;
}
.cr{
    color: rgb(24, 94, 225);
}
.dr{
    color: rgb(255, 229, 71);
}
.debit{
    color: rgb(60, 179, 113);
}
.credit{
    color: rgb(255, 0, 0);
}
.opening{
    color: rgb(106, 90, 205);
}
.ac{
    color: rgb(23, 194, 69);
}

    </style>
  </head>
  <body style=" background-repeat: no-repeat;  background-position: center center; background-size: contain;">
<div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="challan_wrapper">
                    <center>
                    <table style="border: none;">
                    
                    <tr style="border: none;">
                        <td style="border: none;"><span><img src="{{ asset('new_assets') }}/images/mustafa-poultry.jpg" alt="" width="150" height=""></span></td>
                        <td style="border: none;">
                            <h3 class="text-center">Tawakkal Marketing Traders | DayBook Report</h3>
                           
                            <h5 class="text-center">Date : {{@$from_date}}</h5>
        
                            
                        </td>
                    </tr>
                  
                   </table>
                    </center>
                    
                    <!-- Purchase Medicne -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Purchase Medicine</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    <!--sale Medicine -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Sale Medicine</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    <!-- Return Medicine -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Return Medicine</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                        @if(@$return_medicine != "")
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
                        @endif    
                    </tbody>
                    <tr class="text-dark">
                        <th colspan="3">Total :</th>
                        <th>0</th>
                        <th>0</th>
                        <th> {{@$tot_rm_val }} </th>
                        
                    </tr>
                    
                    </table>

                  <!-- Purchase feed -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Purchase Feed</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    <!-- Sale Feed -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Sale Feed</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    
                    <!-- Return Feed -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Return Feed</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    
                    
                    <!-- Purchase Chick -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Purchase Chick</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    
                    <!-- Sale Feed -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Sale Chick</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    
                    <!-- Purchase Murghi -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Purchase Murghi</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    
                    <!-- Sale Feed -->
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">Sale Chick</h5>
                            </td>
                        </tr>
                  
                   </table>
                    </center>
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
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
                    
                    <!-- CashBook -->
                    
                    <center>
                        <table style="border: none;">
                    
                        <tr style="border: none;">
                            
                            <td style="border: none;">
                                <h5 class="text-center">CashBook</h5>
                            </td>
                             <h5 class="text-center" style="color:red; float:right;">Opening Cash In Hand : {{number_format(@$account_opening)}}</h5>
                        </tr>
                  
                   </table>
                    </center>
                    
                     
                    <table  class="table table-bordered border-secondary" style="border-size:2px;" border="1">
                       
                        <thead>
                        <tr class="text-dark">
                            <th>Id</th>
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
                                        
                                    
                                    
                                </tr>
                                <?php $tot_balance = $tot_balance ; ?>
                                @foreach($cashbook AS $c)
                                    
                                    <tr class="text-dark">
                                        <td> {{ @$c->id}}</td>
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
                                        
                                       
                                    </tr>
                                @endforeach
                                <tr class="text-dark">
                                    <th>1</th>
                                    <th colspan="1"> Expense </th>
                                    <th><span class="waves-effect waves-light btn btn-primary-light">{{ @$day_exp }}</span>  </th>
                                    <th><span class="waves-effect waves-light btn btn-primary-light"> 0</span> </th>
                                    <th> <span class="waves-effect waves-light btn btn-primary-light">{{ @$tot_balance - @$day_exp }} </span> </th>
                                    <th>{{number_format(@$tot_balance - @$day_exp)}}</th>
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
                        
                        
                    </tr>
                    </tfoot>
                    
                    </table>
                    <center>
                        <h5 style="color:blue; float:right;">Closing Cash In Hand : {{number_format(@$tot_balance - @$day_exp)}}</h5>
                        </center>
                </div>
            </div>
       
           
        </div>
       
    
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
  </html>

