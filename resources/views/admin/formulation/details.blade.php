@extends('layouts.admin')
@section('content')

<div class="row">       
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="header-title">Formulation Details</h4>
                    {{-- <a href="{{ route('admin.staffs.add') }}" class="btn btn-primary">Add Ac</a> --}}
                </div>
                {{-- <p class="sub-header">Following is the list of all the formulations.</p> --}}
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Sale Item</label>
                        <input type="text" class="form-control" readonly value="{{ $details->item->name }}">
                    </div>
                    <div class="col-md-4">
                        <label for="">Sale Weight</label>
                        <input type="text" class="form-control" readonly value="{{ $details->sale_weight }}">
                    </div>
                    <div class="col-md-4">
                        <label for="">Total Purchase Weight</label>
                        <input type="text" class="form-control" readonly value="{{ $details->total_purchase_weight }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
@foreach($details->formulation_details()->get() AS $d)
<div class="col-md-6 col-xl-3">
					<div class="box pull-up">
						<div class="box-body">
							<div class="row align-items-center">
								<div class="col-3">
                                <i class="cc LTC fs-40" title="{{ $d->item->name }}"></i>
								</div>
								<div class="col-12">
									<h4 class="counter text-center mb-0 fs-40">{{ $d->item->name }} : {{ $d->quantity }} <small class="text-danger ps-10"><i class="mdi mdi-arrow-down text-danger"></i></small></h4>
								</div>
								
								<div class="col-12">
									<div id="sparkline0" class="mt-40"><a href="http://localhost:3000/public/item.html?={{ $d->item->id }}" class="card-link"><span class="btn btn-danger">Go to WeighBridge</span> </a></div>
								</div>
							</div>
						</div>
					</div>
				</div>

@endforeach
</div>

@endsection

@section('page-scripts')
@include('admin.partials.datatable')
@endsection