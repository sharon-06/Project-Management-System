@extends('admin.layouts.master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" id="message">
            @include('message.alert')
        </div>
        
        <div class="row col-sm-12 page-titles">
            <div class="col-lg-5 p-b-9 align-self-center text-left  " id="list-page-actions-container">
                <div id="list-page-actions">
                    <!--ADD NEW ITEM-->
                    @can('create Task')
                    <a href="{{ route('admin.task.create') }}" class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form" id="popup-modal-button">
                        <span tooltip="Create new task." flow="right"><i class="fas fa-plus"></i></span>
                    </a>
                    @endcan
                    <!--ADD NEW BUTTON (link)-->
                </div>
            </div>
            <div class="col-lg-7 align-self-center list-pages-crumbs text-right" id="breadcrumbs">
                <h3 class="text-themecolor">Tasks</h3>
                <!--crumbs-->
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item">App</li>    
                    <li class="breadcrumb-item  active active-bread-crumb ">Tasks</li>
                </ol>
                <!--crumbs-->
            </div>
            
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Tasks List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive list-table-wrapper">
                        <table class="table table-hover dataTable no-footer" id="table" width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Recurring</th>
                                <th>Due Date Status</th>
                                <th>Allocate User</th>
                                <th>Task Accepted</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th class="noExport" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    
                </div>
                <!-- /.card-body -->
            </div>
        </div>

    </div>
</div>


<script>
function datatables() {

    var table = $('#table').DataTable({
        dom: 'RBfrtip',
        buttons: [],
        select: true,
        
        aaSorting     : [[0, 'asc']],
        iDisplayLength: 25,
        stateSave     : true,
        responsive    : true,
        fixedHeader   : true,
        processing    : true,
        serverSide    : true,
        "bDestroy"    : true,
        pagingType    : "full_numbers",
        ajax          : {
            url     : '{{ url('admin/task/ajax/data') }}',
            dataType: 'json'
        },
        columns       : [
            {data: 'id', name: 'id', visible: false},
            {data: 'title', name: 'title'},
            {data: 'recurring', name: 'recurring'},
            {data: 'due_date_status', name: 'due_date_status'},
            {data: 'users_avatars', name: 'users_avatars'},
            {data: 'taskAccepted', name: 'taskAccepted'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at', visible: false},
            {data: 'updated_at', name: 'updated_at', visible: false},
            {data: 'action', name: 'action', orderable: false, searchable: false,
                fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                    //  console.log( nTd );
                    $("a", nTd).tooltip({container: 'body'});
                }
            }
        ],
    });
}

datatables();

function funChangeStatus(id,status,currentStatusID) {
    $("#pageloader").fadeIn();
    $.ajax({
      url : '{{ route('admin.task.ajax.change_status') }}',
      data: {
        "_token": "{{ csrf_token() }}",
        "id": id,
        "status": status,
        "currentStatusID": currentStatusID
        },
      type: 'get',
      dataType: 'json',
      success: function( result )
      {
        setTimeout(function() {   //calls click event after a certain time
            datatables();
            $("#pageloader").hide();
            alert_message(result);
        }, 1000);
      }
    });
}
</script>


    

@endsection
