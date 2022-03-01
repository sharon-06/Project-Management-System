@extends('admin.layouts.popup')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="card-title">{{$team->title}}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body mt-4">
                    <div class="form-group">
                        <div class="user-add-shedule-list">
                            <h2 class="table-avatar">
                                <label>Team Leader</label>
                                <a href="" class="avatar ml-4" tooltip="Team Leader: {{$team->leader->name}}" flow="right"><img alt="" src="{{$team->leader->getImageUrlAttribute($team->leader->id)}}"></a>
                                <a href="">{{$team->leader->name}} <span>{{$team->leader->departments[0]->name}}</span></a>
                            </h2>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Team Member</label>
                        <div class="avatars_overlapping ml-4">
                            @foreach($team->users as $teamMember)
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
                        <div class="avatars_overlapping ml-4">
                            {!!$team->description?$team->description : 'no description'!!}
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
@endsection