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
                    @can('create attendance')
                    <a href="{{ route('admin.attendance.create') }}" class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form" id="popup-modal-button">
                        <span tooltip="Create new attendance." flow="right"><i class="fas fa-plus"></i></span>
                    </a>
                    @endcan
                    <!--ADD NEW BUTTON (link)-->
                </div>
            </div>
            <div class="col-lg-7 align-self-center list-pages-crumbs text-right" id="breadcrumbs">
                <h3 class="text-themecolor">Attendances</h3>
                <!--crumbs-->
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item">App</li>    
                    <li class="breadcrumb-item  active active-bread-crumb ">Attendances</li>
                </ol>
                <!--crumbs-->
            </div>
            
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Attendances Activity List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive list-table-wrapper">
                        <div class="form-row" id="search"> 

                            <div class="form-group col-md-3">
                                <span>Search by users</span>
                                <select class="select2 form-control" id="user_id" name="user_id" required autocomplete="user_id">
                                    <option value="All">All</option>
                                    @foreach ($users as $key => $user)
                                        <option value="user_id_{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <span>Search by company - branch</span>
                                <select class="select2 form-control" id="branch" name="branch" required autocomplete="branch">
                                    <option value="All">All</option>
                                    @foreach ($branches as $key => $branch)
                                        <option value="{{ $branch->company->name }} - {{ $branch->name }}">{{ $branch->company->name }} - {{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <span>Search by activity date</span>
                                <input class="search-area form-control" type="text" name="datefilter" id="daterange" value=""  oninput="datatables()" />  
                            </div>

                            <div class="mt-4 form-group col-md-2">
                                <button type="button  form-control" id="Clear_Filters" class="btn btn-primary"><i class="fas fa-sync"></i></button>
                            </div>

                        </div>
                        <table class="table table-hover dataTable no-footer" id="table" width="100%">
                            <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Company - Branch</th>
                                <th>Total Hrs.</th>
                                <th class="noExport">User Id</th>
                                <th class="noExport">ID</th>
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
    var daterange = $( "#daterange" ).val();
    var table = $('#table').DataTable({
        dom: 'Rltipr',
        buttons: [],
        select: true,
        aaSorting     : [[2, 'desc']],
        iDisplayLength: 25,
        stateSave     : true,
        responsive    : true,
        fixedHeader   : true,
        processing    : true,
        serverSide    : true,
        "bDestroy"    : true,
        pagingType    : "full_numbers",
        "bLengthChange": false,
        ajax          : {
            url     : '{{ url('admin/report/ajax/data') }}',
            dataType: 'json',
            data: {
                    "daterange": daterange
                },
        },
        columns       : [
            {data: 'username', name: 'username'},
            {data: 'branch', name: 'branch'},
            {data: 'total_hrs', name: 'total_hrs'},
            {data: 'search_username', name: 'search_username', visible: false},
            {data: 'id', name: 'id', visible: false}
        ],
    });

    $('#user_id').on('change', function () {
        if(this.value != 'All'){
            table.columns(3).search( this.value ).draw();
        }else{
            table.search( '' ).columns().search( '' ).draw();
        }
    } );

    $('#branch').on('change', function () {
        if(this.value != 'All'){
            table.columns(1).search( this.value ).draw();
        }else{
            table.search( '' ).columns().search( '' ).draw();
        }
    } );

    // Date range vars
    minDateFilter = "";
    maxDateFilter = "";

    $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            minDateFilter = Date.parse(picker.startDate);
            maxDateFilter = Date.parse(picker.endDate);
            datatables();
            if(this.value == ''){
                table.search( '' ).columns().search( '' ).draw();
            }
    });

    $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
          location.reload();
    });

    $('#Clear_Filters').click(function () {
        $('#Clear_Filters').attr("disabled", true);
        $('input[name="datefilter"]').val('');
        $('#user_id').val(null).trigger('change');
        $('#branch').val(null).trigger('change');
        location.reload();
    });
    
}

datatables();

$('input[name="datefilter"]').daterangepicker({
  autoUpdateInput: false,   
});

$("#user_id").select2({
  placeholder: "select users",
  allowClear: true
});

$("#branch").select2({
  placeholder: "select company - branch",
  allowClear: true
});

</script>

@endsection
