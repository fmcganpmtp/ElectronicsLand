@extends('admin.master')
@section('title', 'General Settings')
@section('breadcrumb') General Settings @endsection
@section('content')

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>{{ _('messages.Whoops') }}!</strong>
            {{ _('messages.There were some problems with your input') }}.<br><br>
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
        <h2>General Settings</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
              <div class="card-body">
                  
                    <form class="form-horizontal" method="POST" action="{{ route('admin.settings') }}" enctype="multipart/form-data">
                    @foreach ($settings as $row)
                     @csrf
					  @if ($row->item == 'company_logo')
                     <div class="form-group">
                            <label for="name" >{{ $row->display_name }}  <span class="color_red">*</span></label>

                            <div >
                            @if($row->value != '')
                                                <div class="image-block">
                                                    <img src="{{ asset('assets/uploads/logo/') }}/{{ $row->value }}" alt="{{ $row->value }}" width="200px" />
                                                    <a href="javascript:void(0)" onclick="removeImage({{ $row->id }}, this)" class=""><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>
                                                   </div>
                            @endif
							<input type="file" name="{{ $row->item }}" class="form-control">
                            </div>
                        </div>
                    
					
					@elseif($row->item == 'footer_logo')
					
					<div class="form-group">
                            <label for="name" >{{ $row->display_name }}  <span class="color_red">*</span></label>

                            <div >
                            @if($row->value != '')
                                                <div class="image-block">
                                                    <img src="{{ asset('assets/uploads/logo/') }}/{{ $row->value }}" alt="{{ $row->value }}" width="200px" />
                                                   <a href="javascript:void(0)" onclick="removeImage({{ $row->id }}, this)" class=""><span class="red_round remove-input-field"><i class="fa fa-minus-circle" aria-hidden="true"></i></span></a>
                                                </div>
                                            @endif
                                            <input type="file" name="{{ $row->item }}" class="form-control">
							</div>
                        </div>
                        
						@elseif($row->item =='company_address')
						
						
						<div class="form-group">
                                        <strong>{{ $row->display_name }}:</strong>
                                        <textarea id="{{ $row->item }}" placeholder="{{ $row->display_name }}" class="form-control" name="{{ $row->item }}">{{ $row->value }}</textarea>
                                    </div>
						
						
						
						@else
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ $row->display_name }}:</strong>
                                        <input id="{{ $row->item }}" type="text" placeholder="{{ $row->display_name }}" class="form-control" name="{{ $row->item }}" value="{{ (old($row->item) != '' ? old($row->item) : $row->value) }}">
                                    </div>
                                </div>
                            @endif
                        @endforeach
						
                        
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
      
      <div class="card top-10">
          <div class="card-header">Stripe Status</div>
           <div class="card-body">
            <div class="row chk-bxs">
            <div class="col-lg-8 col-12">
    <div class="row form-group">
      <div class="col-lg-2">  
      <label><b>Stripe Status :</b></label>
      </div>
      <div class="col-lg-4"> 
       <select name="stripe_status" id="stripe_status" class="form-control ">
                                 <option value="Enabled" @if($stripe_status=="Enabled") selected @endif >Enabled</option>
                                 <option value="Disabled" @if($stripe_status=="Disabled") selected @endif >Disabled</option>
                                 </select>
      </div>
    </div>

          </div>
        </div>
     </div>
   </div>
      
      
      <div class="card top-10" style="margin-top:20px;">
                <div class="card-header">Choose search criteria's</div>
                <div class="card-body">
            <form action="{{route('admin.storesearchcriteria')}}" method="post">
                @csrf
            <div class="row chk-bxs">
            <div class="col-lg-12 col-12">
            <label class="control control--checkbox">Keywords
              <input type="checkbox"  @if( in_array('Keywords',$SearchCondition)) checked @endif name="searchcriteria[]" value="Keywords">
              <div class="control__indicator"></div>
            </label>

            <label class="control control--checkbox">Category 
              <input type="checkbox"  @if( in_array('Category',$SearchCondition)) checked @endif name="searchcriteria[]" value="Category">
              <div class="control__indicator"></div>
            </label> 
                <label class="control control--checkbox">Price filter
              <input type="checkbox" name="searchcriteria[]"  @if( in_array('Price filter',$SearchCondition)) checked @endif value="Price filter">
              <div class="control__indicator"></div>
            </label>

            <label class="control control--checkbox">Brand
              <input type="checkbox" name="searchcriteria[]"  @if( in_array('Brand',$SearchCondition)) checked @endif value="Brand">
              <div class="control__indicator"></div>
            </label>  
                <label class="control control--checkbox">Country
              <input type="checkbox" name="searchcriteria[]"   @if( in_array('Country',$SearchCondition)) checked @endif value="Country">
              <div class="control__indicator"></div>
            </label>

            <label class="control control--checkbox">Product status
              <input type="checkbox" name="searchcriteria[]"  @if( in_array('Product status',$SearchCondition)) checked @endif  value="Product status">
              <div class="control__indicator"></div>
            </label>
            </div>
        </div>
                           
        <button type="submit" class="bl-btn">Save</button>  </div>
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

  




@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script type="text/javascript">
   var $ = jQuery;
   (function($) {
   $(document).ready( function () {
   $('#stripe_status').on('change', function() {
   var status = this.value ;
   $.ajax({
   url: "{{ url('stripe_status') }}",
   type: "get",
   data:{ 
      _token:'{{ csrf_token() }}',
      stripe_status:status ,
   },
   async:true,
   cache: false,
   dataType: 'json',
   success: function(){ 
    swal("Status Updated..", "", "success");
    //location.reload();
   } ,
   error: function(XMLHttpRequest, textStatus, errorThrown) { 
   swal(errorThrown, "", "error");
   }  
   
   })  ;
   
   
   });
   });
   })(jQuery);
</script>
<script type="text/javascript">
        function removeImage(id = null, elm){
            if(confirm('Do you want to remove image?')){
                if(id != null){
                    var imgBlock = elm.closest('.image-block');
                    $.ajax({
                        type:'POST',
                        url:'{{ route("admin.settings.removeImage") }}',
                        data:{id: id, '_token':'{{csrf_token()}}'},
                        success:function(response){
                            if(response.result){
                                $(imgBlock).parent().prepend('<span class="text-success" id="alert_image">Image removed successfully. You can add new.</span>');
                                $('#alert_image').delay(3000).fadeOut();
                                imgBlock.remove();
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