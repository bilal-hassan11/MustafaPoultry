@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        
                        <h4 class="page-title">{{ (isset($is_update)) ? 'Edit' : 'Add' }} Account</h4>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                     
                </div>
                <form action="{{ route('admin.accounts.store') }}" class="ajaxForm" method="POST">
                    @csrf
                    <div class="row">
                        {{-- <div class="col-md-4 form-group">
                            <label for="">Grand Parent Account</label>
                            <select class="form-control" name="grand_parent_id" id="grand_parent_id" required>
                                <option value="">Select grand parent account</option>
                                @foreach($grand_parents AS $parent)
                                    <option value="{{ $parent->hashid }}" @if(@$edit_account->grand_parent_id == $parent->id) selected @endif>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Parent Account</label>
                            <select class="form-control" name="parent_id" id="parent_id" required>
                                <option value="">Select parent account</option>
                                @if(isset($is_update))
                                    @foreach($parents AS $parent)
                                        <option value="{{ $parent->hashid }}" @if($edit_account->parent_id == $parent->id) selected @endif>{{ $parent->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div> --}}
                        <div class="col-md-12 form-group">
                            <label for="">Account Name</label>
                            
                            <input type="text" class="form-control" placeholder="Enter account name here" value="{{ @$edit_account->name }}" name="name" id="account_name">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Address</label>
                            <textarea class="form-control" name="address" id="address" cols="30" rows="4">{{ @$edit_account->address }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="">Account Opening Date</label>
                            <input type="date" class="form-control" value="{{ (isset($is_update)) ? date('Y-m-d', strtotime($edit_account->opening_date)) : date('Y-m-d') }}"  name="opening_date" id="opening_date">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Acount Opening Balance</label>
                            <input type="number" class="form-control" placeholder="0" value="{{ @$edit_account->opening_balance }}" min="0" name="opening_balance" id="opening_balance">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Sub Head</label>
                            <input type="text" class="form-control"  value="{{ $grand_parent[0]->name}} -> {{$parent[0]->name}}"  name="sub_head" id="sub_head">
                        </div>
                        <div class="col-md-3 form-group ">
                            <label for="">Account Nature</label>
                            <select class="form-control js-example-basic-single22" name="account_nature" id="account_nature" required>
                                <option value="">Select account nature</option>
                                <option value="credit" @if(@$edit_account->account_nature == 'credit') selected @endif>Credit</option>
                                <option value="debit" @if(@$edit_account->account_nature == 'debit') selected @endif>Debit</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="">Acount Ageing</label>
                            <input type="number" class="form-control" placeholder="0" value="{{ @$edit_account->ageing }}" min="0" name="ageing" id="ageing">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Comission</label>
                            <input type="number" class="form-control" placeholder="0%" value="{{ @$edit_account->commission }}" min="0" max="100"  name="commission" id="commission">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Discount</label>
                            <input type="number" class="form-control" placeholder="0%" value="{{ @$edit_account->discount }}" min="0" max="100"  name="discount" id="discount">
                        </div>
                        <div class="col-md-3 form-group ">
                            <label for="">Account Status</label>
                            <select class="form-control js-example-basic-single" name="status" id="account_nature" required>
                                <option value="">Select account nature</option>
                                <option value="1" @if(@$edit_account->status == 1) selected @endif>Enable</option>
                                <option value="0" @if(@$edit_account->status == 0) selected @endif>Disable</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-1">
                            <input type="hidden" value='{{ (isset($is_update)) ? hashids_encode($edit_account->grand_parent_id) : @$grand_parent_id }}' name="grand_parent_id">
                            <input type="hidden" value='{{ (isset($is_update)) ? hashids_encode($edit_account->parent_id) : @$parent_id }}' name="parent_id">

                            <input type="hidden" value="{{ @$edit_account->hashid }}" name="account_id" id="account_id">
                            <input type="submit" class="btn btn-primary mt-2 float-right" style="float:right">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>        
</div>
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
<script>
    $('#grand_parent_id').change(function(){
        var id = $(this).val();
        var route = "{{ route('admin.common.get_parent_account', ':id') }}";
        route     = route.replace(':id', id);
        
        getAjaxRequests(route, '', 'GET', function(resp){
            $('#parent_id').html(resp.html);
        });
    });
</script>
@endsection