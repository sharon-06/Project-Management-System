@extends('admin.layouts.master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" id="message">
            @include('message.alert')
        </div>

        <div class="row col-sm-12 page-titles">
            <div class="col-lg-5 p-b-9 align-self-center text-left  " id="list-page-actions-container">
                <!-- <div id="list-page-actions">
                    @can('create user')
                    <a href="{{ route('admin.user.create') }}" class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form" id="popup-modal-buttonUserRole">
                        <span tooltip="Create new team member." flow="right"><i class="fas fa-plus"></i></span>
                    </a>
                    @endcan
                </div> -->
            </div>
            <div class="col-lg-7 align-self-center list-pages-crumbs text-right" id="breadcrumbs">
                <h3 class="text-themecolor">Team Members</h3>
                <!--crumbs-->
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item">App</li>    
                    <li class="breadcrumb-item  active active-bread-crumb ">Team Members</li>
                </ol>
                <!--crumbs-->
            </div>
            
        </div>
        <div class="container">
            <div class="row text-center">

                <!-- Team item -->
                @foreach($teams as $team)
                <div class="col-xl-3 col-sm-6 mb-5">
                    @can('delete Team')
                    <form method="post" class="float-right delete-form-team" action="{{route('admin.team.destroy', ['team' => $team->id ])}}"><input type="hidden" name="_token" value="{{Session::token()}}"><input type="hidden" name="_method" value="delete"><button type="submit" class="close mt-2 mr-2"><span tooltip="Delete" flow="up">X</span></button></form>
                    @endcan

                    <div class="bg-white rounded shadow-sm py-5 px-4">
                        <h5 class="mb-3">{{$team->title}}</h5>
                        <p tooltip="Team Leader: {{$team->leader->name}}" flow="up"><img src="{{$team->leader->getImageUrlAttribute($team->leader->id)}}" alt="" width="100" class="img-fluid rounded-circle mb-2 img-thumbnail shadow-sm"></p>
                        <span class="small text-uppercase text-muted">Team Member</span><br>
                        <div class="avatars_overlapping">
                            @foreach($team->users as $teamMember)
                                <span class="avatar_overlapping">
                                    <p tooltip="{{$teamMember->name}}" flow="up">
                                        <img src="{{$teamMember->getImageUrlAttribute($teamMember->id)}}" width="50" height="50">
                                    </p>
                                </span>
                            @endforeach
                        </div>

                        <p class="card-text mt-2"></p>
                        @can('edit Team')
                        <a href="{{ route('admin.team.edit', ['team' => $team->id]) }}" class="btn btn-success ml-1" id="popup-modal-button" data-original-title="" title=""><span tooltip="Edit" flow="left"><i class="fas fa-edit"></i></span></a>
                        @endcan
                        <a href="{{ route('admin.team.show', ['team' => $team->id]) }}" id="popup-modal-button"  class="btn btn-danger mr-2"><span tooltip="View" flow="right"><i class="fas fa-eye"></i></span></a>
                    </div>
                </div><!-- End -->
                @endforeach

                <!--Add New Team item -->
                @can('create Team')
                <div class="col-xl-3 col-sm-6 mb-5">
                    <div class="bg-white rounded shadow-sm py-5 px-4" style="height: 385px;text-align: center;">
                        <a href="{{ route('admin.team.create') }}" class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form" id="popup-modal-buttonUserRole" style="margin-top: 120px;">
                        <span tooltip="Create new team member." flow="right"><i class="fas fa-plus"></i></span>
                    </a>
                    </div>
                </div>
                @endcan
                <!-- End -->
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Team Members List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body ">
                    <div class="verticals twelve">
                        <section class="management-tree">
                            <div class="mgt-container">
                                <div class="mgt-wrapper">
                                    
                                    <div class="mgt-item">
                                        @foreach($users as $user)
                                        <div @if(count($user->allChildren)>0) class="mgt-item-parent" @else class="mgt-item-lastparent" @endif>

                                            <div class="person">
                                                <img src="{{$user->getImageUrlAttribute($user->id)}}" alt="{{$user->name}}">

                                                <p class="name">
                                                    @if(isset($user->latest_attendance_creator))
                                                        @if($user->latest_attendance_creator->status=='punch_in')
                                                            <span style="color:green;"><i class="fas fa-circle"></i></span>
                                                        @else 
                                                            <span style="color:red;"><i class="fas fa-circle"></i></span>
                                                        @endif
                                                    @else 
                                                        <span style="color:red;"><i class="fas fa-circle"></i></span>
                                                    @endif
                                                    {{$user->name}} / {{$user->position}}
                                                </p>

                                            </div>
                                        </div>
                                        @if(count($user->allChildren)>0)
                                        <div class="mgt-item-children">
                                            @foreach($user->allChildren as $child_user)
                                                @include('admin.user.child_user', ['child_user' => $child_user])
                                            @endforeach
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .card {
        min-height: 200px;
    }
</style>
<script type="text/javascript">
$(document).ready(function () {
    $(document).on('submit','#popup-form-team',function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        $("#pageloader").fadeIn();
        $.ajax({
            method: "POST",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $(this).serialize(),
            success: function(message){
                console.log(message);
                $("#popup-modal").modal('hide');
                if(typeof(message.success) != "undefined" && message.success !== null) {
                    var messageHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success: </strong> '+ message.success +' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    //$('#message').html(messageHtml);
                    $("#navbar_current_Status").load(location.href+" #navbar_current_Status>*","");// after new attendance create. reload navbar_current_Status div
                    setTimeout(function() {   //calls click event after a certain time
                        $("#pageloader").hide();
                        Swal.fire({ icon: 'Success', title: 'Success!', text: message.success})
                        location.reload();
                    }, 1000);
                } else if(typeof(message.delete) != "undefined" && message.delete !== null) {
                    var messageHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Delete: </strong> '+ message.delete +' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    //$('#message').html(messageHtml);
                    $("#navbar_current_Status").load(location.href+" #navbar_current_Status>*","");// after new attendance create. reload navbar_current_Status div
                    setTimeout(function() {   //calls click event after a certain time
                        $("#pageloader").hide();
                        Swal.fire({ icon: 'delete', title: 'Delete!', text: message.delete })
                        location.reload();
                    }, 1000);
                } else if(typeof(message.error) != "undefined" && message.error !== null){
                    var messageHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error: </strong> '+message.error+' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    //$('#message').html(messageHtml);
                    setTimeout(function() {   //calls click event after a certain time
                        $("#pageloader").hide();
                        Swal.fire({ icon: 'error', title: 'Oops...', text: message.error})
                        location.reload();
                    }, 1000);
                }
            },
            error: function(message){
                if(typeof(message.responseJSON.errors) != "undefined" && message.responseJSON.errors !== null){
                    var errors = message.responseJSON.errors;
                    $("#popup-modal").modal('hide');
                    $.each(errors, function (key, val) {
                        var messageHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error: </strong> '+val[0]+' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        //$('#message').append(messageHtml);
                        Swal.fire({ icon: 'error', title: 'Oops...', text: val[0]})
                    });
                    
                    setTimeout(function() {   //calls click event after a certain time
                        $("#pageloader").hide();
                        location.reload();
                    }, 1000);
                }
            },
        });
    }); 
});

$(document).ready(function () {
    $(document).on('submit','.delete-form-team',function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();
        swal({
            title: "Delete?",
            text: "Are you sure want to delete it?",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (r) {
            if (r.value === true) {
                $("#pageloader").fadeIn();
                $.ajax({
                    method: "POST",
                    url: url,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: data,
                    success: function(message){
                        setTimeout(function() {   //calls click event after a certain time
                            location.reload();
                            $("#pageloader").hide();
                            alert_message(message);
                        }, 1000);
                    },
                });
            } else {
                r.dismiss;
            }
        }, function (dismiss) {
            return false;
        })
    }); 
});
</script>
@endsection