@if($view_composer_profile_menu_visible_criteria['network_expand'] != 1)
<div class="col-lg-12 col-12 ">
              <div class="err-shw" style="display:none; margin-top:20px;">
                        <div class="alert alert-danger alert-dismissible">
                         Expand Your Network option is disabled as per the current package. Would you like to upgrade package? <a href="{{route('package.listing')}}">Upgrade</a>
                       </div>
                    </div>
              
              </div>
@else


@foreach ($user_data as $data) 
<?php
$class_verified = '';
			if($data->varification_status=='varified')
			$class_verified = 'active-border';            
			$company_image =    $data->company_image;
			if(($company_image!=''))
$img_path = asset('uploads/BuyerCompany/').'/'.$company_image;
elseif(!empty($data->profile_pic))
$img_path = asset('/uploads/userImages/').'/'.$data->profile_pic;
else  
$img_path = asset('uploads/defaultImages/default_avatar.png');        
$network_exist=false;

$c_types = $c_types_names =[]; 
		if($data->cmp_type) { 
				  
				   foreach ($company_types as $company_type)
				   {
							  $c_types = explode(",",$data->cmp_type);
							   if(in_array($company_type->id, $c_types))
							 $c_types_names[] = $company_type->company_type ;
				  } 
		}
				  $c_types_names =  implode( ', ', $c_types_names );

?>

<div class="col-lg-4 col-12"><div class=" slr-details nw-proC">
    <div class="row"><div class="col-lg-3">
        <div class="pro-logo-out"><div class="nw-prof-img {{$class_verified}}">
					 <img src="{{$img_path}}" onclick="fnmovetoprofile({{$data->main_id}})" style="cursor: pointer;"></div>
					  @if($data->varification_status=='varified')
					   <div class="active-tick"><i class="fa fa-check" aria-hidden="true"></i></div> 							  
					  @endif
					  </div></div><div class="col-lg-9 col-12"><ul><li onclick="fnmovetoprofile( {{$data->main_id}})" style="cursor: pointer;" class="nm"><b>{{$data->company_name}}
					  @if($data->company_type!== '')
                    	<span class="c-type"> ( {{$c_types_names}})</span>
                      @endif	
					  </b></li>
					  <li class="comp-nm">Name:{{$data->user_name}}</li>
					  @if($data->country_name!== '')
					   <li class="loca">Country:{{$data->country_name}}</li>
                      @endif	
					  </ul>
                            @if($data->network_exist==false)
                          
                            <a href="javascript:void(0)" onclick="addtonetwork( {{$data->main_id}})" class="cht-ico add-nt clsa{{$data->main_id}}"><i class="fa fa-plus-square-o" aria-hidden="true"></i>Add to network</a>
                          
                          @else
                             <a href="javascript:void(0)"  id="expandnetwork'+{{$data->main_id}}+'" onclick="removefromnetwork({{$data->main_id}})" class="cht-ico add-nt rmv"><i class="fa fa-trash-o" aria-hidden="true"></i>Remove from network</a>
                           @endif
                    @if( $view_composer_profile_menu_visible_criteria['network_chat'] == 1)
                     <a href="javascript:void(0)" class="cht-ico start-chat" onclick="EnableChatRequest({{$data->main_id}})" ><i class="fa fa-comments" aria-hidden="true"></i>Start chat</a>
                     @else
                    <a onclick="showerror()" href="JavaScript:void(0);" class="cht-ico"><i class="fa fa-comments" aria-hidden="true"></i>Start chat</a>
					  @endif
                    </div></div></div></div></div>
 @endforeach  
 <div class="seller-pagi">
  
{{ $user_data->links() }} 

@if($last_page>1)
<input type="text" name="page_number" id="pg_number" onchange="my_sellers_search()" max="{{$last_page}}">
of {{$last_page}} items.
@endif

</div>
@endif
<script>
const el = document.createElement('div')
el.innerHTML = "Chat option is disabled as per the current package.Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerror(){
  swal({
  icon: "error",
  content: el,
});
}
</script>  