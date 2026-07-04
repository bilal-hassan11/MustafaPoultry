@extends('layouts.admin')
@section('content')
    <div class="main-content app-content mt-5">
        <div class="side-app">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Filter --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Purchase Medicine Return</h4>
                            <a href="{{ route('admin.medicine-invoices.purchase_return.create') }}"
                               class="btn btn-primary btn-sm">
                                <i class="ri-add-line"></i> New Return
                            </a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.medicine-invoices.purchase_return.index') }}" method="GET">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label>Account</label>
                                        <select class="form-control select2" name="account_id">
                                            <option value="">All Accounts</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->hashid }}"
                                                    {{ request('account_id') == $account->hashid ? 'selected' : '' }}>
                                                    {{ $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Invoice No</label>
                                        <input type="text" class="form-control" name="invoice_no"
                                               value="{{ request('invoice_no') }}" placeholder="Invoice No">
                                    </div>
                                    <div class="col-md-2">
                                        <label>Item</label>
                                        <select class="form-control select2" name="item_id">
                                            <option value="">All Items</option>
                                            @foreach ($products as $item)
                                                <option value="{{ $item->hashid }}"
                                                    {{ request('item_id') == $item->hashid ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>From</label>
                                        <input type="date" class="form-control" name="from_date"
                                               value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label>To</label>
                                        <input type="date" class="form-control" name="to_date"
                                               value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">All Purchase Medicine Returns</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example54" class="text-fade table table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="text-dark">
                                            <th>S.No</th>
                                            <th>Date</th>
                                            <th>Invoice No</th>
                                            <th>Account</th>
                                            <th>Item</th>
                                            <th>Rate</th>
                                            <th>Quantity</th>
                                            <th>Net Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $tot_q = 0; $tot_amt = 0; @endphp
                                        @forelse ($returns as $return)
                                            <tr class="text-dark">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-M-Y', strtotime($return->date)) }}</td>
                                                <td>{{ $return->invoice_no }}</td>
                                                <td>
                                                    <span class="waves-effect waves-light btn btn-rounded btn-danger-light">
                                                        {{ $return->account->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="waves-effect waves-light btn btn-rounded btn-info-light">
                                                        {{ $return->item->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>{{ abs($return->quantity) > 0 ? number_format($return->net_amount / abs($return->quantity), 2) : '-' }}</td>
                                                @php $tot_q += abs($return->quantity); @endphp
                                                <td>{{ abs($return->quantity) }}</td>
                                                @php $tot_amt += $return->net_amount; @endphp
                                                <td>{{ number_format($return->net_amount, 2) }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a class="btn btn-outline-info rounded-pill btn-wave"
                                                           href="{{ route('admin.medicine-invoices.purchase_return.show', $return->invoice_no) }}"
                                                           title="View"><i class="ri-eye-line"></i></a>
                                                        <a class="btn btn-outline-warning rounded-pill btn-wave"
                                                           href="{{ route('admin.medicine-invoices.purchase_return.edit', $return->invoice_no) }}"
                                                           title="Edit"><i class="ri-edit-line"></i></a>
                                                        <a class="btn btn-outline-info rounded-pill btn-wave"
                                                           href="{{ route('admin.medicine-invoices.purchase_return.show', [$return->invoice_no, 'generate_pdf' => 1]) }}"
                                                           target="_blank" title="PDF"><i class="ri-download-2-line"></i></a>
                                                        <a class="btn btn-outline-danger rounded-pill btn-wave"
                                                           href="{{ route('admin.medicine-invoices.purchase_return.delete', $return->invoice_no) }}"
                                                           title="Delete"
                                                           onclick="return confirm('Delete this return? This will reverse the stock deduction.')">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">No records found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-dark">
                                            <th colspan="6">Total</th>
                                            <th>{{ $tot_q }}</th>
                                            <th>{{ number_format($tot_amt, 2) }}</th>
                                            <th>-</th>
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
@endsection
