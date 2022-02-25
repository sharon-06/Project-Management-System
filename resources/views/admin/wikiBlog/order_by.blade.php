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
                    <a href="{{ route('admin.wikiBlog.index') }}" class="btn btn-primary"><i class="fas fa-backward nav-icon"></i> Wiki Blogs</a>
                </div>
            </div>
            <div class="col-lg-7 align-self-center list-pages-crumbs text-right" id="breadcrumbs">
                <h3 class="text-themecolor">Wiki Blogs</h3>
                <!--crumbs-->
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item">App</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.wikiBlog.index') }}">Wiki Blogs</a></li>    
                    <li class="breadcrumb-item active active-bread-crumb ">Order By</li>
                </ol>
                <!--crumbs-->
            </div>
            
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Wiki Blogs List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive list-table-wrapper">
                        <table class="table table-hover dataTable no-footer" id="table" width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Order By</th>
                                <th>Title</th>
                                <th>Category</th>
                            </tr>
                            </thead>
                            <tbody class="sort"></tbody>
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
        dom: 'RBrt',
        buttons: [],
        select: true,
        aaSorting     : [[1, 'asc']],
        iDisplayLength: -1,
        stateSave     : true,
        responsive    : true,
        fixedHeader   : true,
        processing    : true,
        //serverSide    : true,
        "bDestroy"    : true,
        pagingType    : "full_numbers",
        ajax          : {
            url     : '{{ url('admin/wikiBlog/ajax/data') }}',
            dataType: 'json'
        },
        createdRow: function (row, data, dataIndex) {
            $(row).attr('data-id', data.id);
        },
        columns       : [
            {data: 'id', name: 'id', visible: false},
            {data: 'order_by', name: 'order_by', visible: false},
            {data: 'title', name: 'title'},
            {data: 'category', name: 'category'}
        ],
    });
}

datatables();

$('.sort').sortable({
    cursor: 'move',
    axis:   'y',
    update: function(e, ui) {
        var wikiBlog_order_ids = new Array();
        $('.sort tr').each(function(){
            wikiBlog_order_ids.push($(this).data("id"));
        });
        $.ajax({
            type:   'GET',
            url:    '{{ route('admin.wikiBlog.ajax.change_order') }}',
            data: {
                "_token": "{{ csrf_token() }}",
                "ids": wikiBlog_order_ids,
            },
            success: function(msg) {
                //do something with the sorted data
            }
        });
    }
});
</script>


    

@endsection
