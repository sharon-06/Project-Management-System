@extends('admin.layouts.popup')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" id="error_message">
            @include('message.alert')
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="card-title">Create Task</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.task.store') }}" method="post" id="popup-form" >
                        @csrf
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="title" class="form-control" required autocomplete="title" autofocus maxlength="60">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="description" name="description" class="form-control" required maxlength="5" autofocus></textarea>  
                        </div>
                        
                        <!-- <div class="form-group">
                            <input type="text" name="users[]" data-options= '{{json_encode($users)}}'
                              data-selected='[]' 
                              data-field-title="<i class='fa fa-folder-open'></i> Available Fields" 
                              data-selected-title="<i class='fa fa-star'></i> Selected Fields" 
                              class="dragableMultiselect">
                        </div> -->

                        <div class="form-group">
                            <label>Users</label>
                            <select class="form-control select2" id="user_id" name="user_id[]" required autocomplete="user_id" multiple>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
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
        $('#popup-form').validate(
        {
            rules:{
              
            }
        }); //valdate end
    }); //function end

    //CKEDITOR for description
    CKEDITOR.replace('description', {
      extraPlugins: 'uploadimage,image',
      height: 300,

      // Upload images to a CKFinder connector (note that the response type is set to JSON).
      uploadUrl: '{{ asset('/') }}/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',

      // Configure your file manager integration. This example uses CKFinder 3 for PHP.
      filebrowserBrowseUrl: '{{ asset('/') }}plugins/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl: '{{ asset('/') }}plugins/ckfinder/ckfinder.html?type=Images',
      filebrowserUploadUrl: '{{ asset('/') }}/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
      filebrowserImageUploadUrl: '{{ asset('/') }}/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

      // The following options are not necessary and are used here for presentation purposes only.
      // They configure the Styles drop-down list and widgets to use classes.

      stylesSet: [{
          name: 'Narrow image',
          type: 'widget',
          widget: 'image',
          attributes: {
            'class': 'image-narrow'
          }
        },
        {
          name: 'Wide image',
          type: 'widget',
          widget: 'image',
          attributes: {
            'class': 'image-wide'
          }
        }
      ],

      // Load the default contents.css file plus customizations for this sample.
      contentsCss: [
        'http://cdn.ckeditor.com/4.16.2/full-all/contents.css',
        'https://ckeditor.com/docs/ckeditor4/4.16.2/examples/assets/css/widgetstyles.css'
      ],

      // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
      // resizer (because image size is controlled by widget styles or the image takes maximum
      // 100% of the editor width).
      image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
      image2_disableResizer: true,
      removeButtons: 'PasteFromWord'
    });

    $('#user_id').select2({
    placeholder: 'Select a user'
    }).on("select2:select", function (evt) {
            var id = evt.params.data.id;

            var element = $(this).children("option[value="+id+"]");

            moveElementToEndOfParent(element);

            $(this).trigger("change");
        });
    var ele=$("#user_id").parent().find("ul.select2-selection__rendered");
    ele.sortable({
        containment: 'parent',
        update: function() {
            orderSortedValues();
            console.log(""+$("#user_id").val())
        }
    });

    orderSortedValues = function() {
    var value = ''
        $("#user_id").parent().find("ul.select2-selection__rendered").children("li[title]").each(function(i, obj){

            var element = $("#user_id").children('option').filter(function () { return $(this).html() == obj.title });
            moveElementToEndOfParent(element)
        });
    };

    moveElementToEndOfParent = function(element) {
        var parent = element.parent();

        element.detach();

        parent.append(element);
    };


</script>


@endsection