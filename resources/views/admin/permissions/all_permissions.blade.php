@extends('layouts.admin')
@section('content')
<div class="main-content app-content mt-6">
  <div class="side-app">
    <!-- CONTAINER --> 
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER --> 
        
        <!-- PAGE-HEADER END --> <!-- ROW-1 --> 
      
        <!-- COL END --> <!-- ROW-3 END --> <!-- ROW-5 --> 
  
        <!-- ROW-5 END -->
        
        <div class="row">
          <div class="col-12 col-sm-12">
              <div class="card ">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="card-title mb-0">Permissions Detail</h3>
                        <a href="javascript:void(0)" onclick="add_permission()" class="btn btn-primary">Add New Permission</a>
                    </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <div id="data-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                      <div class="row">
                          <div class="col-sm-12">
                            <table id="example54" class="table table-bordered text-nowrap mb-0 dataTable no-footer" role="grid" aria-describedby="data-table_info">

                              <thead>
                                <tr class="text-dark">
                                  <th width="20">S.No</th>
                                  <th>Name</th>
                                  <th>Slug</th>
                                  <th>Added On</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($permissions as $k => $permission)
                                  <tr class="text-dark">
                                      <td>
                                          <p class="m-0 text-center">{{ $k + 1 }}</p>
                                      </td>
                                      <td><span class="waves-effect waves-light btn btn-rounded btn-primary-light">{{ $permission->name }} </span></td>
                                      <td><small>{{ $permission->slug }}</small></td>
                                      <td>
                                          <p class="m-0"><small>{{ get_date($permission->created_on) }}</small></p>
                                      </td>
                                      <td width="120">
                                        <div class="btn-list"> 
                                            <a onclick="add_permission(true, '{{$permission->hashid}}', '{{$permission->name}}')" href="javascript:void(0)" class="btn btn-icon btn-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Edit"> 
                                                <i class="ri-pencil-fill lh-1"></i> 
                                            </a> 
                                            <button type="button" onclick="ajaxRequest(this)" data-url="{{ route('admin.permissions.delete', $permission->hashid) }}"  class="btn btn-icon btn-danger btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <i class="ri-delete-bin-5-fill lh-1"></i>
                                            </button> 
                                        </div>
                                      </td>
                                  </tr>
                                @endforeach
                              </tbody>

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
    </div>
    <!-- CONTAINER END --> 
  </div>
</div>

<!-- Center modal content -->
<div class="modal fade" id="permission_modal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="mypermission_modalLabel"><span id="modal_title"></span></h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form class="ajaxForm" action="{{route('admin.permissions.save')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="permission_name">Permission Name</label>
                    <input class="form-control" name="name" type="text" id="permission_name"  required="required" />
                </div>
                <div class="form-group">
                    <input class="form-control" name="permission_id" type="hidden" id="permission_id" />
                    <button class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('page-scripts')
@include('admin.partials.datatable')
<script type="text/javascript">
    function add_permission(is_update = false, permission_id = null, permission_name = null){
        $("#permission_id").val(permission_id);
        $("#permission_name").val(permission_name);
        if(is_update){
            $("#modal_title").html('Update '+permission_name);
        }else{
            $("#modal_title").html('Add New Permission');
        }

        $("#permission_modal").modal('show');
    }
</script>
@endsection
