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

<div class="col-lg-4 col-12" id="div_mynetwork{{$data->main_id}}'"><div class=" slr-details nw-proC" id ="{{$data->main_id}}">
    <div class="row"><div class="col-lg-3">
        <div class="pro-logo-out"><div class="nw-prof-img {{$class_verified}}">
					 @if( $view_composer_profile_menu_visible_criteria['general_companyProfile'] == 1)
					 <img src="{{$img_path}}" onclick="fnmovetoprofile({{$data->main_id}})" style="cursor: pointer;">
					 @else
					 <img src="{{$img_path}}" onclick="showerrorprofile()" style="cursor: pointer;">
					 @endif
					 </div>
					  @if($data->varification_status=='varified')
					   <div class="active-tick"><i class="fa fa-check" aria-hidden="true"></i></div> 							  
					  @endif
					  </div></div><div class="col-lg-9 col-12"><ul>
					      @if( $view_composer_profile_menu_visible_criteria['general_companyProfile'] == 1)
					      <li onclick="fnmovetoprofile( {{$data->main_id}})" style="cursor: pointer;" class="nm"><b>{{$data->company_name}}
					  @if($data->company_type!== '')
                    	<span class="c-type"> ( {{$c_types_names}})</span>
                      @endif	
					  </b></li>
					  @else
					     <li onclick="showerrorprofile()" style="cursor: pointer;" class="nm"><b>{{$data->company_name}}
					  @if($data->company_type!== '')
                    	<span class="c-type"> ( {{$c_types_names}})</span>
                      @endif	
					  </b></li>
					  @endif
					  
					  
					  
					  <li class="comp-nm">Name:{{$data->user_name}}</li>
					  @if($data->country_name!== '')
					   <li class="loca">Country:{{$data->country_name}}</li>
                      @endif	
					  </ul>
					  
					  <a href="javascript:void(0)"  id="expandnetwork{{$data->main_id}}" onclick="removefromnetwork({{$data->main_id}})" class="cht-ico add-nt rmv"><i class="fa fa-trash-o" aria-hidden="true"></i>Remove from network</a>
					  @if( $view_composer_profile_menu_visible_criteria['network_chat'] == 1)
					  <a href="javascript:void(0)" class="cht-ico" onclick="EnableChatRequest({{$data->main_id}})"><i class="fa fa-comments" aria-hidden="true"></i>Start chat</a>
					  @else
                    <a onclick="showerror()" href="JavaScript:void(0);" class="cht-ico"><i class="fa fa-comments" aria-hidden="true"></i>Start chat</a>
					  @endif
                            
					  
					  
					  </div></div>
					  

					  </div></div></div>
 @endforeach  
 <div class="pagination_outer">
  
{{ $user_data->links() }}

@if($last_page>1)
<input type="number" name="page_number1" id="pg_number1" onchange="my_net_contact()"  max="{{$last_page}}">
of {{$last_page}} items.
@endif

  </div>
  
  
<script>
const elcontact = document.createElement('div')
elcontact.innerHTML = "Chat option is disabled as per the current package.Would you like to upgrade package? <a href='{{route('package.listing')}}'>Upgrade</a>"
    function showerror(){
  swal({
  icon: "error",
 // title: "Expand Your Network option is disabled as per the current package.",
  content: elcontact,
 // icon: "question"
});
}
</script>  

  