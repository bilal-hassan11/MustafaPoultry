@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Activity Logs</li>
                    </ol>
                </div>
                <h4 class="page-title">All Activity Logs</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="header-title">Activity Logs</h4>
                    </div>
                    <form action="{{ route('admin.logs.all') }}" method="get"
                        class="d-flex mb-3 mt-2 border-top border-bottom py-2 align-items-center">
                        <div class="ms-2">
                            <label for="s" class="sr-onlys mr-1 text-sm">Search Text:</label>
                            <input type="text" class="form-control" id="s"
                                value="{{ request('s') ?? null }}" name="s">
                        </div>
                        <div class="ms-2">
                            <label for="from" class="sr-onlys mr-1 text-sm">Start Date:</label>
                            <input type="text" class="form-control human_datepicker" id="from"
                                value="{{ request('from') ?? null }}" name="from">
                        </div>
                        <div class="ms-2">
                            <label for="to" class="sr-onlys mr-1 text-sm">End Date:</label>
                            <input type="text" class="form-control human_datepicker" id="to"
                                value="{{ request('to') ?? null }}" name="to">
                        </div>
                        <div class="ms-2">
                            <label for="user" class="mr-1 d-block text-sm">Search By User:</label>
                            <select name="user" class="form-control" data-toggle="select2" style="width:200px">
                                <option value="">All</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->hashid }}"
                                        {{ request('user') == $user->hashid ? 'selected' : '' }}>{{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ms-2 mt-2">Submit</button>
                    </form>
                    <table class="table dt_table table-bordered w-100 nowrap" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th width="20">S.No</th>
                                <th>Activity By</th>
                                <th>Activity</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $k => $log)
                                <tr>
                                    <td>
                                        <p class="m-0 text-center">{{ $logs->firstItem() + $k }}</p>
                                    </td>
                                    <td>{{ $log->user->name ?? 'n/a' }}</td>
                                    <td><small>{{ $log->activity }}</small></td>
                                    <td>
                                        <p class="m-0"><small>{{ get_date($log->created_at) }}</small></p>
                                    </td>
                                    <td>
                                        <button type="button" onclick="show_log_modal('{{ $log->hashid }}')"
                                            class="btn btn-info btn-xs waves-effect waves-light">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$logs->appends(request()->all())->links()}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="log_modal" tabindex="-1" aria-labelledby="log_modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="log_modalLabel">View Log Activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-scripts')
    <script>
        function show_log_modal(id) {
            const url = "{{ route('admin.logs.view') }}";
            getAjaxRequests(url, {
                id
            }, 'GET', function(response) {
                $('#log_modal').modal('show');
                $('#log_modal .modal-body').html(response.html);
            });
        }
    </script>
@endsection
