@extends('admin.master')
@section('title', 'Edit Content')
@section('breadcrumb') Edit Content @endsection
@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            <ul>
                <li>{{ session('success') }}</li>
            </ul>
        </div>
    @endif

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Content Pages</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
              <div class="card-body">
                  
                    <form method="POST" action="{{ route('contentpages.update', $contents->id) }}"  enctype="multipart/form-data">
                    
                     @csrf
                     
                            <div class="form-group">
                                <label>Page</label> <input type="text" name="page" value="{{ $contents->page }}" class="form-control">
                            </div>
                       
                            <div class="form-group">
                                <label>Page Title</label> <input type="text" name="page_title" value="{{ $contents->title }}" class="form-control">
                            </div>
                        
                            <div class="form-group">
                                <label>Banner/Slider</label>
                                <select name="choose" class="form-control" id="choose">
                                    <option value="">Choose</option>
                                    <option value="slider" @if ($contents->banner_type == 'slider'){{ 'selected' }}@endif>Slider</option>
                                    <option value="banner" @if ($contents->banner_type == 'banner'){{ 'selected' }}@endif>Banner</option>
                                </select>
                            </div>
                        
                        <div id="banner_outer" @if ($contents->banner_type != 'banner')style="display:none"@endif>
                            <div class="form-group">
                                @if ($contents->banner != '')
                                    <div id="image-block">
                                        <img src="{{ asset('/assets/uploads/contents/' . $contents->banner) }}" class="img-thumbnail" width="175" />
                                        <a href="javascript:void(0)" onclick="removeImage({{ $contents->id }})" class=""><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>
                                    </div>
                                @endif
                                <label>Banner</label>
                                <input type="file" name="image" id="banner" class="form-control">
                            </div>
                        </div>
                        <div id="slider_outer" @if ($contents->banner_type != 'slider')style="display:none"@endif>
                            <div class="form-group">
                                <label>Slider </label><select name="slider_title" class="form-control" data-dependent="slider" id="slider">
                                    <option value="Null">Select Slider Title</option>
                                    @foreach ($sliders as $slider_data)
                                        <option value='{{ $slider_data->id }}' @if ($contents->slider == $slider_data->id){{ 'selected' }} @endif>{{ $slider_data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="form-group">
                                <label>Content </label> <textarea name="content" id="mytextarea" class="form-control">{{ $contents->page_content }}</textarea>
                            </div>
                        
                            <div class="form-group">
                                <label>SEO Title</label> <input type="text" name="seo_title" value="{{ $contents->seo_title }}" class="form-control">
                            </div>
                        
                            <div class="form-group">
                                <label>SEO Description</label> <textarea name="seo_description" class="form-control">{{ $contents->seo_description }}</textarea>
                            </div>
                        
                            <div class="form-group">
                                <label>SEO Keywords </label> <textarea name="seo_keywords" class="form-control">{{ $contents->seo_keywords }}</textarea>
                            </div>
                        
                            <div class="form-group">
                                <label>Check Page Positions</label>
                                @php
                                    $position_array = [];
                                    $position_array = explode(',', $contents->page_position);
                                @endphp
                                <input type="checkbox" name="page_position[]"  value="Top" @if (in_array('Top', $position_array)) checked @endif>&nbsp; Top &nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="page_position[]" id="chk_position_footer1" value="Footer Information" @if (in_array('Footer Information', $position_array)) checked @endif>&nbsp; Footer Information &nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="page_position[]"  value="Footer Help" @if (in_array('Footer Help', $position_array)) checked @endif>&nbsp; Footer Help &nbsp;&nbsp;&nbsp;

                                <input type="checkbox" name="page_position[]"  value="Middle" @if (in_array('Middle', $position_array)) checked @endif>&nbsp; Middle &nbsp;&nbsp;&nbsp;

                                <input type="checkbox" name="page_position[]"  value="Right" @if (in_array('Right', $position_array)) checked @endif>&nbsp; Right
                                
                            </div>
                        
                           
                        
                        
                        
                        

                        <div class="form-group mb-0">
                            <div class="">
                                <button type="submit" class="bl-btn">
                                    {{ __('Update') }}
                                </button>
                                </div>
                        </div>
                    </form>
                  
                  
                  
                  
                  
                  
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>



  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.2.2/tinymce.min.js"></script>
    <script type="text/javascript">
        $(document).on("change", "#choose", function() { 
            if ($(this).val() == 'slider') {
                $("#banner_outer").hide();
                $("#slider_outer").show();
            } else if ($(this).val() == 'banner') {
                $("#banner_outer").show();
                $("#slider_outer").hide();
            } else {
                $("#banner_outer").hide();
                $("#slider_outer").hide();
            }
        });

        tinymce.init({
            selector: 'textarea#mytextarea',
            menu: {
    file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
    edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
    view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
    insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
    format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
    tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
    table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
    help: { title: 'Help', items: 'help' }
  },
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            imagetools_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: "30s",
            autosave_prefix: "{path}{query}-{id}-",
            autosave_restore_when_empty: false,
            autosave_retention: "2m",
            image_advtab: true,
            content_css: '//www.tiny.cloud/css/codepen.min.css',
            link_list: [{
                    title: 'My page 1',
                    value: 'http://www.tinymce.com'
                },
                {
                    title: 'My page 2',
                    value: 'http://www.moxiecode.com'
                }
            ],
            image_list: [{
                    title: 'My page 1',
                    value: 'http://www.tinymce.com'
                },
                {
                    title: 'My page 2',
                    value: 'http://www.moxiecode.com'
                }
            ],
            image_class_list: [{
                    title: 'None',
                    value: ''
                },
                {
                    title: 'Some class',
                    value: 'class-name'
                }
            ],
            importcss_append: true,
            height: 400,
            file_picker_callback: function(callback, value, meta) {
                /* Provide file and text for the link dialog */
                if (meta.filetype === 'file') {
                    callback('https://www.google.com/logos/google.jpg', {
                        text: 'My text'
                    });
                }

                /* Provide image and alt text for the image dialog */
                if (meta.filetype === 'image') {
                    callback('https://www.google.com/logos/google.jpg', {
                        alt: 'My alt text'
                    });
                }

                /* Provide alternative source and posted for the media dialog */
                if (meta.filetype === 'media') {
                    callback('movie.mp4', {
                        source2: 'alt.ogg',
                        poster: 'https://www.google.com/logos/google.jpg'
                    });
                }
            },
            templates: [{
                    title: 'New Table',
                    description: 'creates a new table',
                    content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
                },
                {
                    title: 'Starting my story',
                    description: 'A cure for writers block',
                    content: 'Once upon a time...'
                },
                {
                    title: 'New list with dates',
                    description: 'New List with dates',
                    content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
                }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 600,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: "mceNonEditable",
            toolbar_mode: 'sliding',
            contextmenu: "link image imagetools table",
            valid_elements: "*[*]",
            images_upload_handler: function(blobInfo, success, failure) {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', "{{ route('content.ajaxtiny') }}");
                xhr.onload = function() {
                    var json;

                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };
                formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            }
        });

        $(document).on('click', '#addmoregallery', function(e) {
            var html = '';
            html += '<div class="row gallery_outer">';
            html += '<div class="col-xs-5 col-sm-5 col-md-5 file_outer" >';
            html += '<label>File</label>';
            html += '<input type="file" name="file_gallery[]" class="form-control" />';
            html += '</div>';
            html += '<div class="col-xs-2 col-sm-2 col-md-2" >';
            html += '<a class=" removemoregallery"  href="javascript:void(0)" > <span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></i></a>';
            html += '</div>';
            html += '</div>';
            $("#gallery_outer").append(html);
        });

        $(document).on('click', '.removemoregallery', function(e) {
            $(this).closest('div.gallery_outer').remove();
        });

        function removeImage(id = null){
            if(confirm('Do you want to remove image?')){
                if(id != null){
                    $.ajax({
                        type:'POST',
                        url:'{{ route("contentpages.removeImage") }}',
                        data:{id: id, '_token':'{{csrf_token()}}'},
                        success:function(response){
                            if(response.result){
                                $('#image-block').parent().prepend('<div class="text-success" id="alert_image">'+(response.message)+'</div>');
                                $('#alert_image').delay(2000).fadeOut();
                                $('#image-block').remove();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                } else {
                    alert('failed. Something went wrong.');
                }
            }
        }
    </script>
    <style>
       .tox-menubar {
    display: flex !important;
}
    </style>

@endsection
