<nav class="main-nav" role="navigation">

	  <!-- Mobile menu toggle button (hamburger/x icon) -->
	  <input id="main-menu-state" type="checkbox" />
	  <label class="main-menu-btn" for="main-menu-state">
		<span class="main-menu-btn-icon"></span> Toggle main menu visibility
	  </label>

	  <!-- Sample menu definition -->
	  <ul id="main-menu" class="sm sm-blue">			
		<li><a href="{{ route('admin.home') }}"><i data-feather="home"></i>Dashboard</a>
			
		</li>
		<!-- <li>
			<a href="{{route('admin.account_types.index')}}">
				<i data-feather="home"></i>
				<span> Account Type </span>
			</a>
		</li> -->
		@if(auth()->user()->can('weighbridge') )
    		<li><a href="#"><i data-feather="file-plus"></i>Weighbridge</a>					
    			  <ul>
    			      @if(auth()->user()->can('add-inward') )					
    				     <li><a href="{{route('admin.inwards.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Inward</a></li>
    				  @endif
    				  @if(auth()->user()->can('add-outward') )
    				    <li><a href="{{route('admin.outwards.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Outward</a></li>
                        
                      @endif
    				
    			</ul>
    		</li> 
        @endif
        @if(auth()->user()->can('accounts') )
    		<li><a href="#"><i data-feather="mail"></i>Accounts</a>
    			<ul>
                    @if(auth()->user()->can('add-accounts') )
        				<li><a href="#"><i data-feather="box"></i>Chart Of Accounts</a>
        					<ul>
        					@foreach($grand_parents AS $grand)
        								@if($grand->childs->isNotEmpty())
        									<li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>{{ $grand->name }}</a>
        
        										
        											<ul class="nav-second-level">
        												@foreach($grand->childs()->get() AS $child)
        													<li><a href="{{ route('admin.accounts.add', ['grand_parent_id'=>$grand->hashid, 'parent_id'=>$child->hashid]) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>{{ $child->name }}</a></li>
        												@endforeach
        											</ul>
        										
        									</li>
        								@endif
        							@endforeach 	
        					</ul>
        				</li>
        			@endif
        			@if(auth()->user()->can('all-accounts') )
                        <li><a href="{{route('admin.accounts.index')}}"><i data-feather="box"></i>All Accounts</a>			
        						  
        				</li>
    				@endif
    				@if(auth()->user()->can('purchasebook') )
        				<li><a href="#"><i data-feather="box"></i>Purchase BooK</a>			
        					<ul>
        									
        						<li><a href="{{route('admin.purchases.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Purchase BooK</a>
        						<li>
        						<li><a href="{{route('admin.purchases.all_purchase')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>All Purchases</a>
        						
        						</li>		
        						
        					</ul>		  
        				</li>
                    @endif
                    @if(auth()->user()->can('salebook') )
        				<li>
        					<a href="#">
        						<i data-feather="home"></i>
        						<span> Sales BooK </span>
        					</a>
        					<ul>
        						<li>
        							<a href="{{route('admin.sales.index')}}">
        								<i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
        								<span>  Sales Book </span>
        							</a>
        						</li>
        						<li>
        							<a href="{{route('admin.sales.all_sales')}}">
        								<i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
        								<span> All Sales  </span>
        							</a>
        						</li>
        					</ul>
        				</li>
                    @endif
                    @if(auth()->user()->can('add-cashbook') )
        				<li>
        					<a href="{{route('admin.cash.index')}}">
        					<i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
        						<span> Cash BooK </span>
        					</a>
        				</li>  
    		        @endif
    				<li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Ledger</a>
    					<ul>
							<li>
							    @if(auth()->user()->can('purchasebook-ledger') )
    								<a href="{{route('admin.reports.purchase')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Purchase Book Ledger</a>
								@endif
								@if(auth()->user()->can('salebook-ledger') )
								<a href="{{route('admin.reports.sale_book')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Sale Book Ledger</a>
								@endif
								@if(auth()->user()->can('inward-ledger') )
								<a href="{{route('admin.reports.inward')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Inward Ledger</a>
								@endif
								@if(auth()->user()->can('outward-ledger') )
								<a href="{{route('admin.reports.outward')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Outward Ledger</a>
								@endif
								@if(auth()->user()->can('account-ledger') )
								<a href="{{route('admin.reports.account')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>General Account Ledger</a>
							    @endif
							</li>
    					</ul>			  
    				</li> 
    				
    			</ul>
    		</li> 
        @endif
        @if(auth()->user()->can('add-item') )
    		<li><a href="#"><i data-feather="file-plus"></i>Items</a>					
    		  	<ul>					
    			  	<li><a href="{{route('admin.items.add')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Item</a></li>
                	<li><a href="{{route('admin.items.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>All Sale Items</a></li>			
                	<li><a href="{{route('admin.items.purchase_item')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>All Purchase Items</a></li>			
    		  	</ul>
    		</li> 
		@endif
		
		<li><a href="#"><i data-feather="file-plus"></i>Order</a>					
		  	<ul>
		  	    @if(auth()->user()->can('add-dc') )					
			  	<li><a href="{{route('admin.dcs.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Dc Detial</a></li>
			  	@endif
			  	@if(auth()->user()->can('add-order') )
            	<li><a href="{{route('admin.orders.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>All Orders</a></li>	
            	@endif
            	@if(auth()->user()->can('add-otherdc') )
            	<li><a href="{{route('admin.othersdcs.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Others Dc Detail</a></li>		
                @endif
            </ul>
		</li> 
	
	
        <li><a href="#"><i data-feather="file-plus"></i>Reports</a>					
			  <ul>
			    @if(auth()->user()->can('debitor-report') )					
    				  <li><a href="{{route('admin.reports.creditor')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Creditor Report </a></li>
    			@endif	
    			
    			@if(auth()->user()->can('creditor-report') )
    				  <li><a href="{{route('admin.reports.debtor')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Debtor Report </a></li>
    			@endif
    		
                @if(auth()->user()->can('general-stock-report') )
    				  <li><a href="{{route('admin.inwards.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>General Items Stock Report </a></li>
    			@endif
    			@if(auth()->user()->can('bankaccount-report') )
    				  <li><a href="{{route('admin.reports.trial_balance')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Trial Balance Report</a></li>
                @endif
				
			</ul>
		</li>
		
		@if(auth()->user()->can('add-staff') )		
    		<li><a href="#"><i data-feather="lock"></i>Staff &amp; Permission</a>
    		  <ul>
    			<li><a href="{{route('admin.staffs.all')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Staff</a>
    			</li>
    			<li><a href="{{route('admin.permissions.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Permissions</a>
    			  		  
    			</li>
    		  </ul>		  
    		</li>
        @endif
		@if(auth()->user()->can('add-consumption') )		
    		<li>
    			<a href="{{route('admin.consumptions.index')}}">
    			<i class="fa fa-minus-circle" aria-hidden="true"></i>
    				<span> Consumption </span>
    			</a>
    		</li>
        @endif
        @if(auth()->user()->can('add-manufacturing') )		
        	<li>
        		<a href="{{route('admin.manufactures.index')}}">
        		<i class="fa fa-minus-circle" aria-hidden="true"></i>
        			<span> Manufacture </span>
        		</a>
        	</li>
        @endif
        @if(auth()->user()->can('add-formulation') )		
    		<li><a href="#"><i data-feather="mail"></i>Formulation</a>
    		
    		  <ul>
    		      @if(auth()->user()->can('add-formulation') )
        			<li><a href="{{route('admin.formulations.add')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Formulation</a>			  
        			</li>
        		@endif
        		@if(auth()->user()->can('all-formulation') )
        			<li><a href="{{route('admin.formulations.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>All Formulation</a>	
        			</li>
        		@endif
    		  </ul>		  	
    		</li>  
		@endif
		@if(auth()->user()->can('add-formulation') )		
    		<li><a href="#"><i data-feather="mail"></i>Payment Book</a>
    		
    		  <ul>
    		     
        		@if(auth()->user()->can('all-formulation') )
        			<li><a href="{{route('admin.paymentbooks.index')}}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Payment</a>	
        			</li>
        		@endif
    		  </ul>		  	
    		</li>  
		@endif
		
		
	  </ul>
	</nav>

 

