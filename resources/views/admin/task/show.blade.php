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
                            @foreach($data->users as $teamMember)
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
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
@endsection