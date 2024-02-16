@extends('layouts.template_mobile')
@section('title', 'Edit Product Request')
@section('content')
<sectiion class="seller-page1 no-bg seller-container">
@if($Req!="")  
<?php 
                    $product_description=$Req->product_description; 
                    $country_id=$Req->country_id;
					$quantity=$Req->quantity;
					$language= $Req->language;
					$prd_img = URL::asset("/images/no-image.jpg");
                    if(!empty($Req->prod_img))
                    $prd_img = URL::asset("/uploads/productRequests/".$Req->prod_img);
					$req_date=$Req->req_date;
					$req_end_date=$Req->req_end_date;
					$extend_by_date=$Req->extend_by_date;
					$req_id=$Req->id;
                    $duty = 'Edit' ;
                    $selectedCountries =[];
                    if(!empty($Req->country_id)) 
                      $selectedCountries = explode(",",$Req->country_id);
                  ?>
             @else
                <?php 
                    $product_description= $country_id= $quantity= $language=  $prd_img = $req_date=$req_end_date=$extend_by_date=$req_id="";
                    $duty = 'Create' ;
         ?>
@endif
   
   <div class="container add-req">
       
       
       
<style>
    
     .add-req {margin-top:50px;}
     .add-req  .select2-container {
    background: #f0f0f0!important;
    height: auto;
}

  .add-req h3 {
    font-size: 20px;
    font-weight: bold;
    margin: 0px 0px 0px!important;
    padding-bottom: 10px;
    border-bottom: 1px solid #333;
    position: relative; margin-bottom:25px!important;
}

  .add-req h3::after {
    position: absolute;
    bottom: -2px;
    width: 100px;
    height: 3px;
    background: #000;
    content: '';
    left: 0;
}

  .add-req h4{text-transform:uppercase;margin:10px 0px 15px; font-size:15px;font-weight:700;}
  
   .add-req  .form-control{color:#333;}
 .add-req .form-control{height:40px; margin-bottom:10px;}
   .add-req .autocomplete {
    border-radius: 4px;
    margin-bottom: 10px;    background-color: #f1f1f1;    border: 1px solid #d7d7d7;


}
.select2-container .select2-search--inline .select2-search__field{margin-top:0px!important;}
.add-req .form-control{background-color: #f1f1f1;}
.form-group label{color:#333; font-weight:700;  margin-bottom:4px; font-size:12px;}
  .add-req #btnsubmit{padding:10px 15px; background:#333; border:0px; margin-top:10px; color:#fff;}

 ol, ul {
    margin-top: 0;
    margin-bottom: 11px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    left: auto!important;
    top: -3px!important;
    right: 0;
}
 
</style>
       
      <div class="row">
         <div class="col-lg-12">
            <div class="pr-bottom ad-pro1" >
            
                     
                    
                
                  @if(Session::has('product_message')) <div class="alert alert-success">{{Session::get('product_message') }}</div> @endif  
                  <h3>Edit Product Request</h3>
                  
                  <!--step--->
                  <form id="formsubmit">
                    <div class="card" >
                        <div class="row" >
                            
                            
                         
                         <input type="hidden" value="{{$req_id}}" name="req_id" id="req_id">
                         
                     
                     
                     
                     <?php  $count=0; $executed1='no'; $executed2='no';  $prev_sub=[]; ?>
                @if(!empty($cat_selected)&&($cat_selected->getParentsNames()))  
                @if(!is_null($cat_selected->getParentsNames()))
                @foreach ($cat_selected->getParentsNames()->reverse() as $item_selected)
                <?php if($count==0) {  ?>
                <div class=" col-lg-4  col-12">
                  <div class="form-group">
                    <label> Category: <sup>*</sup></label>
                    <select type="text"   id="category" name="category_id" class="form-control" >
                      
                                      @if($categories)
                                            @foreach($categories as $item)
                                             
                      <option @if(old('category_id',$item_selected->id)== $item->id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                      
                                               
                                            @endforeach
                                        @endif
                                    
                    </select>
                    <span id="dldcategory"></span>
                  </div>
                </div>
                <?php } 
				if($count==1  && old('category_id')=='') { $executed1 ='yes';  ?>
                <div class=" col-lg-4  col-12  sub_cat"  @if(empty($prev_sub->subcategory))   style="display:none;" @endif >
                  <div class="form-group">
                    <label>Sub Category:</label>
                    <select name="subcategory_id" id="subcategory" class="form-control input-sm">
                      
                   
                                           @if(!empty($prev_sub->subcategory)   )
                                           @foreach($prev_sub->subcategory as $item1)
                                           
                      <option @if( $item1->id==$item_selected->id) selected @endif value="{{$item1->id}}">{{$item1->name}}</option>
                      
                                           @endforeach    
                                           @else
                                            
                      <option value=""></option>
                      
                                           @endif
 
                  
                    </select>
                  </div>
                </div>
                <?php  }  $count++; $prev_sub=$item_selected; ?>
                @endforeach  
                @endif
                <?php $second = '';?>
                <?php if($count==1 &&  $executed1 !='yes'  && old('category_id')=='') {  $second = 'yes'; ?>
                <div class=" col-lg-4  col-12  sub_cat"  @if(empty($prev_sub->subcategory))   style="display:none;" @endif >
                  <div class="form-group">
                    <label>Sub Category:</label>
                    <select name="subcategory_id" id="subcategory" class="form-control input-sm">
                      
                                          @if(!empty($prev_sub->subcategory)   )
                                           @foreach($prev_sub->subcategory as $item1)
                                           
                      <option @if( $item1->id==$Req->category_id) selected @endif value="{{$item1->id}}">{{$item1->name}}</option>
                      
                                           @endforeach    
                                           @else
                                            
                      <option value=""></option>
                      
                                           @endif
 
                
                    </select>
                  </div>
                </div>
                <?php } if($count>=1 && $second == 'yes' || $executed1 =='yes') {  ?>
                <div class=" col-lg-4  col-12  subsub_cat_none"     @if( $count>=2 && $second =='yes'  || $executed1 =='yes') style="display:block;" @else style="display:none;" @endif>
                  <div class="form-group">
                    <label>Sub Category:</label>
                    <select name="subsubcategory_id" id="subsubcategory" class="form-control input-sm">
                      
                                           @if(!empty($prev_sub->subcategory)  && old('subsubcategory_id')=='' )
                                           @foreach($prev_sub->subcategory as $item1)
                                           
                      <option @if( $item1->id==$Req->category_id) selected @endif value="{{$item1->id}}">{{$item1->name}}</option>
                      
                                           @endforeach    
 
                                           @else
                                            
                      <option value=""></option>
                      
                                           @endif
                  
                    </select>
                  </div>
                </div>
                <?php } ?>
				
				
				
				<?php if(   old('category_id')!='') {   ?>
                <div class=" col-lg-4  col-12  sub_cat_none" style="display:none;"  >
                  <div class="form-group">
                    <label>Sub Category:</label>
                    <select name="subcategory_id" id="subcategory" class="form-control input-sm">
                      
                                          @if(!empty($prev_sub->subcategory)   )
                                           @foreach($prev_sub->subcategory as $item1)
                                           
                      <option @if( $item1->id==$Req->category_id) selected @endif value="{{$item1->id}}">{{$item1->name}}</option>
                      
                                           @endforeach    
                                           @else
                                            
                      <option value=""></option>
                      
                                           @endif
 
                
                    </select>
                  </div>
                </div>
                <?php } if(old('subcategory_id')!='') {  ?>
                <div class=" col-lg-4  col-12  subsub_cat_none"     style="display:none;">
                  <div class="form-group">
                    <label>Sub Category:</label>
                    <select name="subsubcategory_id" id="subsubcategory" class="form-control input-sm">
                      
                                           @if(!empty($prev_sub->subcategory)  && old('subsubcategory_id')=='' )
                                           @foreach($prev_sub->subcategory as $item1)
                                           
                      <option @if( $item1->id==$Req->category_id) selected @endif value="{{$item1->id}}">{{$item1->name}}</option>
                      
                                           @endforeach    
 
                                           @else
                                            
                      <option value=""></option>
                      
                                           @endif
                  
                    </select>
                  </div>
                </div>
                <?php } ?>
				
				
				
				
                @else
                <div class=" col-lg-4  col-12">
                  <div class="form-group">
                    <label> Category: <sup>*</sup></label>
                    <select type="text"   id="category" name="category_id" class="form-control" >
                      <!--<option value="">None</option>-->
                      
                                        @if($categories)
                                            @foreach($categories as $item)
                                            
                      <?php $dash=''; ?>
                      <option @if( old('category_id',$item->id)==$Req->category_id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                      
                                               


                                            @endforeach
                                        @endif
                                    
                    </select>
                                        <span id="dldcategory"></span>

                  </div>
                </div>
                <div class=" col-lg-4  col-12  sub_cat" style="display:none;">
                  <div class="form-group">
                    <label>Sub Category:</label>
                    <select name="subcategory_id" id="subcategory" class="form-control input-sm">
                      <option value=""></option>
                    </select>
                  </div>
                </div>
                <div class=" col-lg-4  col-12 subsub_cat_none" style="display:none;">
                  <div class="form-group">
                    <label>Sub Category:</label>
                    <?php $selectedCategories = []; ?>
                    <select name="subsubcategory_id" id="subsubcategory" class="form-control input-sm">
                      <option value=""></option>
                    </select>
                  </div>
                </div>
                @endif
                         
                         
                         
                            
                            <h4 >What product are you looking for?</h4>
                            
                           <div class=" col-lg-6  col-12">
                              <div class="form-group">
                                 <label>Product Description: <sup>*</sup></label>
                               
                                 <input type="text" name="product_description"  class="form-control" value="{{ old('product_description',$product_description) }}">
                                 <span id="txtproduct_description"></span>
                              </div>
                           </div>
                           
                           
               <!-- <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Country<sup></sup></label>
                   
                  
                  <select type="text" multiple placeholder="Select Countries" name="country_id[]" id='available_countries' class="form-control ">
                        @foreach ($countries as $country)
                               @if(in_array($country->id, $selectedCountries) && !empty($selectedCountries))
                                <option value="{{ $country->id }}" selected="true" />{{ $country->name }}</option>
                                  @else
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                               @endif 
                        @endforeach 
</select>
                  
                  
                  <span id="txtcnryid"></span> </div>
              </div>
                          
              -->         
                         <div class=" col-lg-6  col-12">
                              <div class="form-group">
                                 <label>What quantity? <sup></sup></label>
                                 <input type="number" class="form-control {{ $errors->has('quantity') ? ' is-invalid' : '' }}" name='quantity' value="{{ old('quantity',$quantity) }}"  >
                                 <span id="txtquantity"></span>
                              </div>
                           </div>

                        
                        
                        <div class=" col-lg-6  col-12">
                              <div class="form-group">
                                 <label>Product Language <sup></sup></label>
                                 <input type="text" class="form-control {{ $errors->has('language') ? ' is-invalid' : '' }}" name='language' value="{{ old('language',$language) }}"  >
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
               <img src="{{$prd_img}}" class="pd-img" style="width:100px;">
               <input type="file" id="input-file-now-custom-3" class="form-control " name="product_gallery"   accept="image/png, image/gif, image/jpeg" multiple>
               
               </div>
               </div>
               
               <div class="col-lg-12">
                   <div class="note-r">* your product request will be visible for 10 business days </div>
               <button type="submit" id="btnsubmit" class="bl-btn">Update Request</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="{{ asset('/css/select2.min.css')}}">
<script src="{{ asset('js/select2.min.js')}}"></script>
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
                url: "{{ route('update.ProductRequest') }}",
                data: formData,
                enctype : 'multipart/form-data',
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(data) {
                    //$fmcg(".loaderajax").hide();
                    var req_id = $fmcg('#req_id').val(); 
                  //  var url1="{{route('edit.productrequest',":req_id")}}".replace(':req_id', req_id);
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
    
       

   
 
   
       
       
<?php if(old('category_id') ) { ?>
 $fmcg("#subcategory option[value='']").attr('selected', true);
 $fmcg("#subsubcategory option[value='']").attr('selected', true);
<?php } if(old('subcategory_id') ) { ?>

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
            console.log(e);
            var cat_id = e.target.value;
            var bse_url = "{{URL::to('/')}}";
            $fmcg.get(bse_url+'/ajax-subcat?cat_id='+ cat_id,function(data){
$fmcg('#subsubcategory').empty();
$fmcg('.subsub_cat').hide();
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
       
       
       
       
   var route = "{{route('available.countries')}}";
  $('#available_countries').select2({
     placeholder: 'Which country are you interested to get the product from?',

    
    ajax: {
      url: route,
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: $.map(data, function(item) {
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