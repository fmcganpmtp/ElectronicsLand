@extends('layouts.template')
@section('title', 'Add Product Request')
@section('content')
<sectiion class="seller-page no-bg seller-container">



   
   <div class="container add-req">
       
      <div class="row">
         <div class="col-lg-12">
        
               <div class="card prd_diplicate">
                
                  @if(Session::has('product_message')) <div class="alert alert-success">{{Session::get('product_message') }}</div> @endif  
                  <h3>Add Product Request</h3>
                  
			
                  <!--step--->
                  <form id="formsubmit">
                    <div class="card" >
                        <div class="row" >
                            
                            
                             <div class=" col-lg-6  col-12">
                              <div class="form-group">
                                 <label> Category: <sup>*</sup></label>
                                 <select type="text"   id="category" name="category_id" class="form-control" >
                                    <option value="">None</option>
                                    @if($categories)
                                    @foreach($categories as $item)
                                    <option value="{{$item->id}}" {{ old('category_id') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                    @endforeach
                                    @endif
                                 </select>
                                <span id="dldcategory"></span>

                              </div>
                           </div>
                           <div class=" col-lg-6  col-12  sub_cat" @if(old('subcategory_id')) style="display:block;" @else style="display:none;" @endif>
                           <div class="form-group">
                              <label>Sub Category:</label>
                              <select name="subcategory_id" id="subcategory" class="form-control input-sm">
                                 <option value=""></option>
                              </select>
                           </div>
                        </div>
                        <div class=" col-lg-6  col-12 subsub_cat_none" style="display:none;">
                           <div class="form-group">
                              <label>Sub Category:</label>
                              <select name="subsubcategory_id" id="subsubcategory" class="form-control input-sm">
                                 <option value=""></option>
                              </select>
                           </div>
                        </div>
                            
                            
                            <h4 >What product are you looking for?</h4>
                            
                           <div class=" col-lg-6  col-12">
                              <div class="form-group">
                                 <label>Product Description: <sup>*</sup></label>
                                 <input type="text" name="product_description"  class="form-control">
                                 <span id="txtproduct_description"></span>
                              </div>
                           </div>
                           
                 <!--          
                <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Country<sup></sup></label>
                   
                  <div class="autocomplete form-group sg-list" >
      
              <select type="text" placeholder="Which country are you interested to get the product from?" name="country_id[]" id='available_countries' class="form-control{{ $errors->has('product_dimension') ? ' is-invalid' : '' }}" multiple="multiple">
              </select>
              <input type="hidden" id="selected_country_id">
              <input type="hidden" id="start_from" value="0">
              
            </div>
                  <span id="txtcnryid"></span> </div>
              </div> -->
                          
                       
                         <div class=" col-lg-6  col-12">
                              <div class="form-group">
                                 <label>What quantity? <sup></sup></label>
                                 <input type="text" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" name='quantity' value="{{old('quantity')}}"  >
                                 <span id="txtquantity"></span>
                              </div>
                           </div>

                        
                        
                        <div class=" col-lg-6  col-12">
                              <div class="form-group">
                                 <label>Product Language <sup></sup></label>
                                 <input type="text" class="form-control {{ $errors->has('language') ? ' is-invalid' : '' }}" name='language' value="{{old('language')}}"  >
                                 <span id="txtlanguage"></span>
                              </div>
                           </div>
                        
                        
                     
                     </div>
               </div>
               <div class="card">
			   <div class="row">
               
               <div class=" col-lg-6  col-12">
               <div class="form-group">
               <label>Product Image:</label>
               <input type="file" id="input-file-now-custom-3" class="form-control " name="product_gallery"   accept="image/png, image/gif, image/jpeg" multiple>
               @if ($errors->has('product_gallery'))
               <span class="invalid-feedback" role="alert">
               <strong>{{ $errors->first('product_gallery') }}</strong>
               </span>
               @endif
               </div>
               </div>
               
               <div class="col-lg-12">
                   <div class="note-r">* your product request will be visible for 10 business days </div>
               <button type="submit" id="btnsubmit" class="bl-btn">Place Product Request</button>
               <img id="loading-image" src="{{ asset('images/ajax-loder.gif')}}" style="display:none;width:20px;"/>

               </div>
              
               </div>
			   </div>
              		   
               </form>
            </div>
         </div>
      </div>
      <!---step--->
      <!--step-2-->
   </div>
   
</sectiion>
@endsection
@section('footer_script')
<link rel="stylesheet" href="{{ asset('/css/select2.min.css')}}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.2.2/tinymce.min.js"></script>
<script type="text/javascript">
//var $fmcg = $.noConflict();
   
     tinymce.init({ selector:'textarea#mytextarea',
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
     link_list: [
       { title: 'My page 1', value: 'http://www.tinymce.com' },
       { title: 'My page 2', value: 'http://www.moxiecode.com' }
     ],
     image_list: [
       { title: 'My page 1', value: 'http://www.tinymce.com' },
       { title: 'My page 2', value: 'http://www.moxiecode.com' }
     ],
     image_class_list: [
       { title: 'None', value: '' },
       { title: 'Some class', value: 'class-name' }
     ],
     importcss_append: true,
     height: 400,
     file_picker_callback: function (callback, value, meta) {
       /* Provide file and text for the link dialog */
       if (meta.filetype === 'file') {
         callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
       }
   
       /* Provide image and alt text for the image dialog */
       if (meta.filetype === 'image') {
         callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
       }
   
       /* Provide alternative source and posted for the media dialog */
       if (meta.filetype === 'media') {
         callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
       }
     },
     templates: [
           { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
       { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
       { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
     ],
     template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
     template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
     height: 300,
     image_caption: true,
     quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
     noneditable_noneditable_class: "mceNonEditable",
     toolbar_mode: 'sliding',
     contextmenu: "link image imagetools table",
     valid_elements: "*[*]",
     images_upload_handler: function (blobInfo, success, failure) {
     var xhr, formData;
     xhr = new XMLHttpRequest();
     xhr.withCredentials = false;
     xhr.open('POST', "{{route('content.ajaxtiny')}}");
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
   
</script>
<script type="text/javascript">
   $fmcg(document).ready(function () { 
       
     $fmcg("#formsubmit").on('submit', function(e) {
            //$fmcg(".loaderajax").show();
            $fmcg("#btnsubmit").prop('disabled', true);
            $fmcg("#loading-image").show(); 
            e.preventDefault();
            var formData = new FormData($fmcg('#formsubmit')[0]);
            formData.append('_token', "{{ csrf_token() }}"); 
                $fmcg.ajax({
                type: "post",
                url: "{{ route('insertproductrequest') }}",
                data: formData,
                enctype : 'multipart/form-data',
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(data) {
                    //$fmcg(".loaderajax").hide();
                    var url1="{{route('MyProduct.Requests')}}";
                    window.location.href=url1;
                },
                error: function (xhr) {
                   // $fmcg(".loaderajax").hide();
                    $fmcg("#btnsubmit").prop('disabled', false);
                    $fmcg("#loading-image").hide(); 

                    var errors = JSON.parse(xhr.responseText);  
                    if(errors.errors.product_description)
                    $fmcg("#txtproduct_description").empty().append("<p style='color:red'>"+errors.errors.product_description[0]+"</p>");
                    else
                      $fmcg("#txtproduct_description").empty();
                      
                    if(errors.errors.category_id)
                        $fmcg("#dldcategory").empty().append("<p style='color:red'>The category field is required.</p>");
                      else
                        $fmcg("#dldcategory").empty(); 
                    
                  
                    $fmcg(window).scrollTop(0);
                               
                }
            });
          });   
    
       

   
 
   
       
       
       <?php if(old('subcategory_id') ) { ?>
   
               var cat_id = <?=old('category_id')?>;
               var subcat_id = <?=old('subcategory_id')?>;
               var bse_url = "{{URL::to('/')}}";
               $fmcg.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
                   var subcat =  $fmcg('#subcategory').empty();
   				subcat.append('<option value ="">Select</option>');
   				if(data.length>0){
                   $fmcg.each(data,function(create,subcatObj){
   					$fmcg('.sub_cat').show();
                       var option = $fmcg('<option/>', {id:create, value:subcatObj});
                       var selected='';
                       if(subcatObj.id==subcat_id){
                        var selected='selected';   
                       }
                       subcat.append('<option value ="'+subcatObj.id+'" '+selected+ '>'+subcatObj.name+'</option>');
                   });
   				}
   				else{
   					$fmcg('.sub_cat').hide();
   				}
   				
               });
       
   		
   <?php } ?>	
   
   
    <?php if(old('subsubcategory_id') ) { ?>
   
              
          
               var cat_id = <?=old('subcategory_id')?>; 
               var subsubcat_id = <?=old('subsubcategory_id')?>;
               var bse_url = "{{URL::to('/')}}";
               $fmcg.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
                   var subcat =  $fmcg('#subsubcategory').empty();
   				subcat.append('<option value ="">Select</option>');
   				if(data.length>0){
                   $fmcg.each(data,function(create,subcatObj){
   					$fmcg('.subsub_cat').show();
                       var option = $fmcg('<option/>', {id:create, value:subcatObj});
                       var selected='';
                       if(subcatObj.id==subsubcat_id){
                        var selected='selected';   
                       }
                       subcat.append('<option value ="'+subcatObj.id+'" '+selected+ '>'+subcatObj.name+'</option>');
                   });
   				}
   				else{
   					$fmcg('.subsub_cat').hide();
   				}
   				
               });
       
   		
   <?php } ?>
       
       
       
               $fmcg('#category').on('change',function(e){
                   $fmcg('#subsubcategory').empty();
                   $fmcg('.subsub_cat').hide();
               console.log(e);
               var cat_id = e.target.value; 
               var bse_url = "{{URL::to('/')}}";
               $fmcg.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
                   var subcat =  $fmcg('#subcategory').empty();
   				subcat.append('<option value ="">Select</option>');
   				if(data.length>0){
                   $fmcg.each(data,function(create,subcatObj){
   					$fmcg('.sub_cat').show();
                       var option = $fmcg('<option/>', {id:create, value:subcatObj});
                       subcat.append('<option value ="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                   });
   				}
   				else{
   					$fmcg('.sub_cat').hide();
   				}
   				
               });
           });
   		
   		
   		
   		
   		
   		
   		$fmcg('#subcategory').on('change',function(e){
               console.log(e);
               var cat_id = e.target.value; 
               var bse_url = "{{URL::to('/')}}";
               $fmcg.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
                   var subcat =  $fmcg('#subsubcategory').empty();
   				subcat.append('<option value ="">Select</option>');
   				if(data.length>0){
                   $fmcg.each(data,function(create,subcatObj){
   					$fmcg('.subsub_cat').show();
                       var option = $fmcg('<option/>', {id:create, value:subcatObj});
                       subcat.append('<option value ="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                   });
   				}
   				else{
   					$fmcg('.subsub_cat').hide();
   				}
   				
               });
           });
   		
   		
   		
   		
       });
</script>
<script type="text/javascript">
   var route = "{{route('available.countries')}}";
     $fmcg('#available_countries').select2({
        placeholder: 'Which country are you interested to get the product from?',
   
       
       ajax: {
         url: route,
         dataType: 'json',
         delay: 250,
         processResults: function(data) {
           return {
             results: $fmcg.map(data, function(item) {
               return {
                 html:"<span>"+item.name+"</span>",
                 text: item.name,
                 id: item.id
               }
             })
           };
         },
         cache: true,
   
       }
     });
   
   
   
   
   
     var route_varients = "{{route('autocompleteSproductFrontend')}}";
     $fmcg('#variants').select2({
        placeholder: 'Select Varients',
   
      
       ajax: {
         url: route_varients,
         dataType: 'json',
         delay: 250,
         processResults: function(data) {
           return {
             results: $fmcg.map(data, function(item) {
               return {
                 html:"<span>"+item.name+"</span>",
                 text: item.name,
                 id: item.id
               }
             })
           };
         },
         cache: true,
   
       }
     });
</script>
@endsection