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
                    <h3 class="card-title">Edit Task</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.task.update', ['task' => $task->id]) }}" method="put"  id="popup-form" >
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="title" value="{{$task->title}}" class="form-control" required autocomplete="title" autofocus maxlength="60">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="description" name="description" class="form-control" required maxlength="5" autofocus>{{$task->description}}</textarea>  
                        </div>

                        <div class="form-group">
                            <label>Users &nbsp;</label><input type="checkbox" id="checkbox_user" > &nbsp;Select All
                            <select class="form-control select2" id="user_id" name="user_id[]" required autocomplete="user_id" multiple>
                                @foreach ($users as $key => $value)
                                    <option value="{{ $value }}" @if(in_array($value, $taskUsers)) selected="selected" @endif >{{ $key }}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="" class="btn btn-secondary"  data-dismiss="modal">Close</a>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
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

    // jQuery Validation
    $(function(){
        $('#popup-form').validate(
        {
            rules:{
              
            }
        }); //valdate end
    }); //function end

    $("#user_id").select2();
    $("ul.select2-selection__rendered").sortable({
      containment: 'parent'
    });
</script>
@endsection