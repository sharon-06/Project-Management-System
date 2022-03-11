@extends('admin.layouts.popup')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="card-title">{{$data->title}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body mt-4">
                    <div class="form-group">
                        <div class="user-add-shedule-list">
                            <h2 class="table-avatar">
                                <label>Task Accepted : </label>
                                @if(isset($data->Tasks_has_taskstatus[0]) && isset($data->Tasks_has_taskstatus[0]->creator))
                                    @if($data->Tasks_has_taskstatus[0]->taskstatuses_id==1)
                                        <label> {!!  'Not Accepte'!!}</label>
                                    @else
                                        <a href="" class="avatar ml-4" flow="right"><img src="{{$data->Tasks_has_taskstatus[0]->creator->getImageUrlAttribute($data->Tasks_has_taskstatus[0]->creator->id)}}" alt="user_id_{{$data->Tasks_has_taskstatus[0]->creator->id}}" class="profile-user-img-small img-circle"></a>
                                        <a href="">{{$data->Tasks_has_taskstatus[0]->creator->name}} <span>{{$data->Tasks_has_taskstatus[0]->creator->departments[0]->name}}</span></a>
                                    @endif
                                @else
                                    <label> {!!  'Not Accepte'!!}</label>
                                @endif
                            </h2>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Assigned Employee</label>
                        <div class="avatars_overlapping ml-4">
                            @foreach($data->users->reverse() as $teamMember)
                                <span class="avatar_overlapping">
                                    <p tooltip="{{$teamMember->name}}" flow="up">
                                        <img src="{{$teamMember->getImageUrlAttribute($teamMember->id)}}" width="50" height="50">
                                    </p>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        {!!$data->description?$data->description : 'no description'!!}
                    </div>

                    <div class="form-group">
                        <label>Task History</label>
                        <table class="table table-hover dataTable no-footer" id="table_task_history" width="100%">
                            <thead>
                            <tr>
                                <th>Task Accepted User</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <!-- <th>Updated At</th> -->
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

    var table = $('#table_task_history').DataTable({
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
            url     : '{{ url('admin/task/ajax/datatables_task_history') }}',
            dataType: 'json',
            data: {
                "task_id": {{$data->id}}
            },
        },
        columns       : [
            {data: 'taskAccepted', name: 'taskAccepted'},
            {data: 'status', name: 'status'},
            {data: 'data_created_at', name: 'data_created_at'},
            /*{data: 'data_updated_at', name: 'data_updated_at'},*/
        ],
    });
}

datatables()
</script>
@endsection