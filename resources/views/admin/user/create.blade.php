@extends('admin.layouts.popup')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('message.alert')
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="card-title">Create User</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.user.store') }}" method="post" id="popup-formUserRole" >
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required autocomplete="name" autofocus maxlength="200">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required autocomplete="email">
                        </div>
                        <div class="form-group">
                            <label>Password: <i class="text-info">(Default: password)</i></label>
                            <input type="password" name="password" value="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role">
                                @foreach ($roles as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Job Title</label>
                            <input type="text" name="position" class="form-control" required autocomplete="position" autofocus maxlength="200">
                        </div>
                        <div class="form-group">
                            <label>Company-Branch</label>
                            <select class="form-control select2" id="select2" name="branch_id[]" required autocomplete="branch_id" multiple>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->company->name .' - '. $branch->name}}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select class="form-control select2" id="department_id" name="department_id[]" required autocomplete="department_id" multiple>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{$department->name}}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>

                        <div class="form-group">
                            <label>Parent User</label>
                            <select class="form-control select2" id="parent_id" name="parent_id" required autocomplete="parent_id">
                                <option value=""></option>
                                @foreach ($parents as $parent)
                                    <option value="{{ $parent->id }}">{{$parent->name}}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>

                        <div class="form-group">
                            <label>Time Zone</label>
                            <select class="form-control select2" id="timezone_id" name="timezone_id" required autocomplete="timezone_id">
                                <option value=""></option>
                                @foreach ($timezones as $timezone)
                                    <option value="{{ $timezone->id }}">{{$timezone->country_name}} - {{$timezone->name}}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>
                        <div class="form-group">
                            <label>Remote Employee</label>
                            <select class="form-control select2" id="remote_employee" name="remote_employee" required autocomplete="remote_employee">
                                <option value="Yes">Yes</option>
                                <option value="No" selected="selected">No</option>
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a href="" class="btn btn-secondary"  data-dismiss="modal">Close</a>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // jQuery Validation
    $(function(){
        $('#popup-formUserRole').validate(
        {
            rules:{
              
            }
        }); //valdate end
    }); //function end

    $("#select2").select2({
      placeholder: "Select a company",
      allowClear: true
    });

    $("#department_id").select2({
      placeholder: "Select a department",
      allowClear: true
    });

    $("#parent_id").select2({
      placeholder: "Select a parent user",
      allowClear: true
    });

    $("#timezone_id").select2({
      placeholder: "Select your time zone",
      allowClear: true
    });

    $("#remote_employee").select2({
      placeholder: "Select a remote employee",
      allowClear: true
    });
</script>
@endsection