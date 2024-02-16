@extends('layouts.template')
@section('title', 'Edit  Seller')
@section('content')
<?php 
function explodHr($fmcgopentime)
{
  $opentime = explode(":",$opentime);
  return $opentime;
}
?>
<style>
  input[type="time"]::-webkit-calendar-picker-indicator {
  display: none !important;
}
</style>
<sectiion class="seller-page create-seller-blade">
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  <div class="container seller-page-2">
    <div class="row">
      <div class="col-lg-12">
      <div class="pr-bottom">
    
    
    
    
      <div class="card ed-slr"> @if(Session::has('message'))
        <div class="alert alert-success">{{Session::get('message') }}</div>
        @endif
        @if($errors->any())
		<div class="alert alert-danger">Some Error Occured</div>
	    @elseif(Session::has('message_not_match')) <div class="alert alert-danger">{{Session::get('message_not_match') }}</div> 
       @endif
        <h3>Edit seller profile</h3>
        <?php 
if(!empty(Auth::guard('user')->user()->profile_pic)) 
$img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
else  
$img_path = asset('uploads/defaultImages/default_avatar.png'); ?>

<div class="row">
                  <div class="col-lg-4 col-12 null-div">
				  </div>
<div class="col-lg-4 col-12">
    <div class="pro-logo-out">
        <div class="pr-logo {{$user->varification_status=='varified' ? 'active-border' : ''}} "> 
        <img class="prof_img" src="  {{ $img_path }} "> <a href="javascript: void(0)" class="edit" id="upload_image">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
            

            </div>
                       @if($user->varification_status=="varified")  <div class="active-tick"><i class="fa fa-check" aria-hidden="true"></i></div> @endif
           
            </div>
            
        <input type="file" id="imgupload" class="image" name="image" style="display:none" accept="image/*" />
      
      </div>
	  
	  

            
                
                  <div class="col-lg-4 col-12 rt-form">
                      <div class="form-group">
                      <label> User Status</label>
					  <select name="user_status" id="user_status" class="form-control ">
					  <option value="" @if(Auth::guard('user')->user()->user_status== "") selected @endif>Select Status</option>
					  <option value="Online" @if(Auth::guard('user')->user()->user_status== "Online") selected @endif >Online</option>
					  <option value="Away" @if(Auth::guard('user')->user()->user_status== "Away") selected @endif>Away</option>
					  <option value="Offline" @if(Auth::guard('user')->user()->user_status== "Offline") selected @endif>Offline</option>
					  <option value="Busy" @if(Auth::guard('user')->user()->user_status== "Busy") selected @endif>Busy</option>
					  
					  </select>
                      </div>
					  </div>
					  </div>
			  
      
      
      
      
      
      </div>
    
    
      <form action="{{route('update.seller')}}" method="POST" enctype="multipart/form-data" >
        @csrf
        <div class="row">
          <div class="col-lg-12 col-12">
            <div class="card adj-div">
             <div class="card-header">Profile </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label> Name<sup>*</sup></label>
                      <input type="text" class="form-control  {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name', Auth::guard('user')->user()->name) }}" name="name" >
                      @if ($errors->has('name')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('name') }}</strong> </span> @endif </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label> Surname<sup>*</sup></label>
                      <input type="text" class="form-control  {{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname', $user->surname)  }}"  >
                      @if ($errors->has('surname')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('surname') }}</strong> </span> @endif </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>E-mail<sup>*</sup></label>
                      <input type="text" class="form-control  {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', Auth::guard('user')->user()->email)  }}" >
                      @if ($errors->has('email')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>
                  </div>
                 

               <div class="col-lg-4 col-12">
                <div class="form-group pn-no">
                  
                  <label>Phone number<sup>*</sup></label>
                  <input name="full" type="text" value="{{ old('phone') ? old('phone') : Auth::guard('user')->user()->phone ?? '' }}" id="phone" class="form-control pno05 {{ $errors->has('phone') ? ' is-invalid' : '' }} " maxlength="15"/>
                  <span  role="alert">
								 @if($errors->has('phone'))
                                 <strong class="error" style="font-size: .875em; color: #dc3545;">{{ $errors->first('phone') }}</strong>
                                 @endif
								 </span>
                  </div>
              </div>

              <div class="col-lg-4 col-12">
                <div class="form-group">
                  <label>Position<sup>*</sup></label>
                  <input type="text" class="form-control  {{ $errors->has('position') ? ' is-invalid' : '' }}"  name="position" value="{{ old('position', Auth::guard('user')->user()->position ?? '')  }}" >
                  @if ($errors->has('position')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('position') }}</strong> </span> @endif </div>
              </div>
            </div>


            <?php if(Auth::guard('user')->user()->seller_type=='Co-Seller')
              { ?>
            <div class="row">
            
             <div class="col-lg-6 col-12 ck-bx9 cb4-1">
                <div class="form-check form-switch2">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"  name="hide_promo_email" value="Yes" @if( Auth::guard('user')->
                  user()->hide_promo_email=="Yes") checked @endif >
                  <label class="form-check-label" for="flexSwitchCheckChecked">Hide Promotional Emails</label>
                </div>
              </div>
              <div class="col-lg-8 col-12 ck-bx9">
                <div class="form-check form-switch2">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"  name="newsletter_status" value="Yes"  @if( Auth::guard('user')->
                  user()->newsletter_status=="Yes") checked @endif >
                  <label class="form-check-label" for="flexSwitchCheckChecked">Newsletter Emails</label>
                </div>
              </div>
            
          </div>
            
            <div class="row">
            <div class="col-lg-12">
              <button type="submit" class="bl-btn">submit</button>
            </div>
          </div>
      
       <?php } ?>
      
      
      </div></div>
      
       <?php if(Auth::guard('user')->user()->seller_type!='Co-Seller')
        { ?>
      
      
        <div class="card adj-div">
            <div class="card-header">Company infromations</div>
              <div class="card-body">
                <div class="row">

                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label> Company</label>
                      <input type="text" class="form-control  {{ $errors->has('company_name') ? ' is-invalid' : '' }}"  name="company_name" value="{{ old('company_name', $user->BuyerCompany->company_name ?? '')  }}" >
                      @if ($errors->has('company_name')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('company_name') }}</strong> </span> @endif </div>
                  </div>
				  <div class="col-lg-4 col-12">
                <div class="form-group">
                  <label>Company Type</label>
                  
                  <select name="company_type" id="company_type" class="form-control  {{ $errors->has('company_type') ? ' is-invalid' : '' }}" aria-label="Default select example"  >
                    <option value="">Company Type</option>
                  @if(!empty($company_types))
                  @foreach ($company_types as $company_type)
                  
                    <option {{old('company_type',$user->BuyerCompany->company_type ?? '') == $company_type->id ? 'selected' : ''}} value="{{$company_type->id}}">{{$company_type->company_type}}</option>
                    
                  @endforeach 
                  @endif
                  </select>
                  @if ($errors->has('company_type')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('company_type') }}</strong> </span> @endif </div>
              </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Street</label>
                      <input type="text" class="form-control  {{ $errors->has('company_street') ? ' is-invalid' : '' }}"  name="company_street" value="{{ old('company_street', $user->BuyerCompany->company_street ?? '')  }}" >
                      @if ($errors->has('company_street')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('company_street') }}</strong> </span> @endif </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Location</label>
                      <input type="text" class="form-control  {{ $errors->has('company_location') ? ' is-invalid' : '' }}"  name="company_location" value="{{ old('company_location', $user->BuyerCompany->company_location ?? '')  }}" >
                      @if ($errors->has('company_location')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('company_location') }}</strong> </span> @endif </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Zip code</label>
                      <input type="text" class="form-control  {{ $errors->has('company_zip') ? ' is-invalid' : '' }}"  name="company_zip" value="{{ old('company_zip', $user->BuyerCompany->company_zip ?? '')  }}" >
                      @if ($errors->has('company_zip')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('company_zip') }}</strong> </span> @endif </div>
                  </div>
                  
              <div class="col-lg-4 col-12">
                <div class="form-group">
                  <label>Tax Registration No.</label>
                  <input type="text" class="form-control  {{ $errors->has('tax_reg_no') ? ' is-invalid' : '' }}"  name="tax_reg_no" value="{{ old('tax_reg_no', $user->BuyerCompany->tax_reg_no ?? '')  }}">
                  @if ($errors->has('tax_reg_no')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('tax_reg_no') }}</strong> </span> @endif </div>
              </div>
              <div class="col-lg-4 col-12">
                <div class="form-group">
                  <label>Registration No</label>
                  <input type="text" class="form-control  {{ $errors->has('registration_number') ? ' is-invalid' : '' }} "  name="registration_number" value="{{ old('registration_no',$user->BuyerCompany->registration_no ?? '')  }}">
                  @if ($errors->has('registration_number')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('registration_number') }}</strong> </span> @endif </div>
              </div>



              <div class="col-lg-4 col-12">
                <div class="form-group">
                  <label>About Company</label>
                  <textarea name="about_company"  class="form-control {{ $errors->has('about_company') ? ' is-invalid' : '' }}"  >{{ $user->BuyerCompany->about_company?? '' }}</textarea>
                  @if ($errors->has('about_company')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('about_company') }}</strong> </span> @endif </div>
              </div>
              <div class="col-lg-4 col-12">
                <div class="form-group">
                  <label>Country<sup>*</sup></label>
                  
                  <select name="country_id" id="country_id" class="form-control  {{ $errors->has('country_id') ? ' is-invalid' : '' }}" aria-label="Default select example"  >
                    <option value="">Country Name</option>
                    
                  @foreach ($countries as $country)
                  
                    <option {{old('country_id',$user->country_id) == $country->id ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                    
                  @endforeach 
                  
                  </select>
                  @if ($errors->has('country_id')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('country_id') }}</strong> </span> @endif </div>
              </div>

              <div class="col-lg-4 col-12">
                <div class="form-group imgupload">
                  <div class="file-drop-area form-group">
                    <label>Picture upload (companies logo/picture)</label>
                    <div class="uploadcontainer">
                      <div class="row">
                        <div class="col-lg-2">
                          <div  class="uploadedimg">
                             @php 
                                 $company_image =  $user->BuyerCompany->company_image ?? '';
                                 if(($company_image!=""))
                                 $cmp_img = asset('uploads/BuyerCompany/').'/'.$company_image;
                                 else $cmp_img = asset('uploads/defaultImages/seller.jpg');
                                 @endphp
                              <img src="{{ $cmp_img}}" >
                            </div>
                        </div>
                        <div class="col-lg-10">
                          <input class="form-control {{ $errors->has('imgpicture') ? ' is-invalid' : '' }} " type="file" name="imgpicture" id="formFile" accept="image/png, image/gif, image/jpeg" >
                          @if ($errors->has('imgpicture')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('imgpicture') }}</strong> </span> @endif </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

                 </div> 
        
        </div>
      </div>
      
      
      
      <div class="card ">
             <div class="card-header">Payment</div>
              <div class="card-body">
      
      
            <div class="row">
              <div class="col-lg-4 col-12">
                <div class="form-group overflow-hidden1">
                  <label>Accepted payments methods<sup>*</sup></label>
                  </label>
                  <div class="autocomplete form-group sg-list" >
                    <?php 

           $accepted_payments="";
                if(!empty($user->BuyerCompany->accepted_payments) ) 
                    $accepted_payments = $user->BuyerCompany->accepted_payments; 

           if(!empty(old('accepted_payments',$accepted_payments)))
             $accepted_payments=old('accepted_payments',$accepted_payments); 
  
?>
                    <input  type="text" class="multitags form-control {{ $errors->has('accepted_payments') ? ' is-invalid' : ''}}" name="accepted_payments" id="accepted_payments" value="{{$accepted_payments}}" style="width:100%;">
                    @if ($errors->has('accepted_payments')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('accepted_payments') }}</strong> </span> @endif </div>
                </div>
              </div>

              <div class="col-lg-4 col-12">
                <div class="form-group overflow-hidden1">
                  <label>Language</label>
                  <div class="autocomplete form-group sg-list" >
                    <?php 

               $languages_speak="";
               if(!empty($user->BuyerCompany->languages_speak) ) 
                        $languages_speak = $user->BuyerCompany->languages_speak; 

               if(!empty(old('languages_speak',$languages_speak)))
                 $languages_speak=old('languages_speak',$languages_speak); 

             
              ?>
                    <input  type="text"  class="multitags form-control" id='languages_speak' name="languages_speak" value="{{ $languages_speak}}" style="width:100%;">
                    @if ($errors->has('languages_speak')) <span class="invalid-feedback" role="alert"> <strong>{{ $errors->first('languages_speak') }}</strong> </span> @endif </div>
                </div>
              </div>
              <div class="col-lg-4 col-12">
                <div class="form-group overflow-hidden1">
                  <label>Delivery option</label>
                  <div class="autocomplete form-group sg-list" >
                    <?php

           $deliver_options="";
           if(!empty($user->BuyerCompany->deliver_options) ) 
                    $deliver_options = $user->BuyerCompany->deliver_options; 

           if(!empty(old('deliver_options',$deliver_options)))
             $deliver_options=old('deliver_options',$deliver_options); 


 
         ?>
                    <input  type="text" class="multitags form-control" name="deliver_options" value="{{ $deliver_options}}" style="width:100%;">
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-12 ck-bx9 cb4-1">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"  name="hide_promo_email" value="No" @if( Auth::guard('user')->
                  user()->hide_promo_email=="No") checked @endif >
                  <label class="form-check-label" for="flexSwitchCheckChecked">Promotional Emails</label>
                </div>
              </div>
              <div class="col-lg-8 col-12 ck-bx9">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"  name="newsletter_status" value="Yes"  @if( Auth::guard('user')->
                  user()->newsletter_status=="Yes") checked @endif >
                  <label class="form-check-label" for="flexSwitchCheckChecked">Newsletter Emails</label>
                </div>
              </div>
            
              
            </div>
           
            
          </div>
          
        </div><!--3rd-card-->
    
    
    
    
    
    
    <div class="card adj-div ">
         <div class="card-header">Opening hours</div>
              <div class="card-body">
      
      
      
            <?php $openingTimes = $user->SellerOpeningTime ;  
              $SunClosed = $MonClosed =  $TuesClosed = $WedClosed = $ThursClosed = $FriClosed = $SatClosed ="";
              $SunOpenhr=$SunCloseHr=$MonOpenhr=$MonCloseHr=$TuesOpenhr=$TuesCloseHr=$WedOpenhr=$WedCloseHr=$ThursOpenhr=$ThursCloseHr=$FriOpenhr=$FriCloseHr=$SatOpenhr=$SatCloseHr="";
            ?>
            @foreach($openingTimes as $openingTime )
            <?php  
                  // $OpenHr= explodHr($openingTime->opening_time);
                  // $CloseHr= explodHr($openingTime->closing_time);  
                  $day =  $openingTime->day; 
                  ?>
            @if($day=="Sunday")
            <?php  
                if(!empty($openingTime->opening_time)) {
                  $SunOpenhr = $openingTime->opening_time; 
                  $SunCloseHr = $openingTime->closing_time; }
                  $SunClosed = $openingTime->closed; 
                 ?>
            @endif
            @if($openingTime->day=="Monday")
            <?php 
                if(!empty($openingTime->opening_time)) {
                  $MonOpenhr = $openingTime->opening_time; 
                  $MonCloseHr = $openingTime->closing_time; }
                  $MonClosed = $openingTime->closed;
                  ?>
            @endif
            @if($openingTime->day=="Tuesday")
            <?php  
                if(!empty($openingTime->opening_time)) { 
                  $TuesOpenhr = $openingTime->opening_time; 
                  $TuesCloseHr = $openingTime->closing_time; } 
                 $TuesClosed = $openingTime->closed;
                  ?>

            @endif
            @if($openingTime->day=="Wednesday")
            <?php
                if(!empty($openingTime->opening_time)) {
                  $WedOpenhr = $openingTime->opening_time; 
                  $WedCloseHr = $openingTime->closing_time; }
                   $WedClosed = $openingTime->closed;
                  ?> 
            @endif
            @if($openingTime->day=="Thursday")
            <?php
                if(!empty($openingTime->opening_time)) {
                  $ThursOpenhr = $openingTime->opening_time; 
                  $ThursCloseHr = $openingTime->closing_time; }
                  $ThursClosed = $openingTime->closed;
                  ?>
            @endif
            @if($openingTime->day=="Friday")
            <?php 
                if(!empty($openingTime->opening_time)) {
                  $FriOpenhr = $openingTime->opening_time; 
                  $FriCloseHr = $openingTime->closing_time; }
                  $FriClosed = $openingTime->closed;
                  ?> 
            @endif
            @if($openingTime->day=="Saturday")
            <?php  
                if(!empty($openingTime->opening_time)) {
                  $SatOpenhr = $openingTime->opening_time;
                  $SatCloseHr = $openingTime->closing_time; }
                  $SatClosed = $openingTime->closed;
                  ?> 
            @endif  
            
            @endforeach
            <div class="row">
              <!--<div class="col-lg-12 col-12">
                <div class="form-group overflow-hidden timimg-s">
                  <label>Opening hours</label>
                </div>
              </div>-->
              <div class="row tm-sec">
                <div class="col-lg-3 col-12">
                  <div class="time-bx">
                    <h4>Sunday</h4>
                    <div class="row">
                      <div class="col-lg-5 col-12">
                        <h5>Opening time</h5>
                        <input type="text" value="{{old('opSundayhr',$SunOpenhr) ?? '00:00' }}" name="opSundayhr" class="form-control timepicker" placeholder="Closing time">
                      </div>
                      <div class="col-lg-5 col-12">
                        <h5>Closing time</h5>
                        <input type="text" value="{{old('clSundayhr',$SunCloseHr) ?? '00:00' }}" name="clSundayhr"  class="form-control timepicker" placeholder="Closing time">
                      </div>
                      <div class="col-lg-2 col-12">
                        <h5>Closed </h5>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="chSunday"   @if(old('chSunday',$SunClosed)=="Yes") checked @endif   value="Yes">
                          <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-12">
                  <div class="time-bx">
                    <h4>Monday</h4>
                    <div class="row">
                      <div class="col-lg-5 col-12">
                        <h5>Opening time</h5>
                        <input type="text" value="{{old('opMondayhr',$MonOpenhr) ?? '00:00' }}" name="opMondayhr"  class="form-control timepicker" placeholder="Opening time">
                      </div>
                      <div class="col-lg-5 col-12">
                        <h5>Closing time</h5>
                        <input type="text" value="{{old('clMondayhr',$MonCloseHr) ?? '00:00' }}" name="clMondayhr"  class="form-control timepicker" placeholder="Closing time">
                      </div>
                      <div class="col-lg-2 col-12">
                        <h5>Closed </h5>
                        <div class="form-check form-switch">
                          <input class="form-check-input" name="chMonday" type="checkbox" @if(old('chMonday',$MonClosed)=="Yes") checked @endif value="Yes" role="switch" id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-12">
                  <div class="time-bx">
                    <h4>Tuesday</h4>
                    <div class="row">
                      <div class="col-lg-5 col-12">
                        <h5>Opening time</h5>
                        <input type="text" value="{{old('opTuesdayhr',$TuesOpenhr )?? '00:00' }}" name="opTuesdayhr"  class="form-control timepicker" placeholder="Opening time">
                      </div>
                      <div class="col-lg-5 col-12">
                        <h5>Closing time</h5>
                        <input type="text" value="{{old('clTuesdayhr',$TuesCloseHr) ?? '00:00' }}" name="clTuesdayhr"  class="form-control timepicker" placeholder="Closing time">
                      </div>
                      <div class="col-lg-2 col-12">
                        <h5>Closed </h5>
                        <div class="form-check form-switch">
                          <input class="form-check-input" name="chTuesday" type="checkbox" value="Yes"  @if(old('chTuesday',$TuesClosed)=="Yes")  checked  @endif role="switch"  id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-12">
                  <div class="time-bx">
                    <h4>Wednesday</h4>
                    <div class="row">
                      <div class="col-lg-5 col-12">
                        <h5>Opening time</h5>
                        <input type="text" value="{{old('opWednesdayhr',$WedOpenhr) ?? '00:00' }}" name="opWednesdayhr"  class="form-control timepicker" placeholder="Opening time">
                      </div>
                      <div class="col-lg-5 col-12">
                        <h5>Closing time</h5>
                        <input type="text" value="{{old('clWednesdayhr',$WedCloseHr) ?? '00:00' }}" name="clWednesdayhr"  class="form-control timepicker" placeholder="Closing time">
                      </div>
                      <div class="col-lg-2 col-12">
                        <h5>Closed </h5>
                        <div class="form-check form-switch">
                          <input class="form-check-input" name="chWednesday" type="checkbox" @if(old('chWednesday',$WedClosed)=="Yes")  checked  @endif value="Yes" role="switch" id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-12">
                  <div class="time-bx">
                    <h4>Thursday</h4>
                    <div class="row">
                      <div class="col-lg-5 col-12">
                        <h5>Opening time</h5>
                        <input type="text" value="{{old('opThursdayhr',$ThursOpenhr) ?? '00:00' }}" name="opThursdayhr" class="form-control timepicker" placeholder="Opening time">
                      </div>
                      <div class="col-lg-5 col-12">
                        <h5>Closing time</h5>
                        <input type="text" value="{{old('clThursdayhr',$ThursCloseHr) ?? '00:00' }}" name="clThursdayhr"  class="form-control timepicker" placeholder="Closing time">
                      </div>
                      <div class="col-lg-2 col-12">
                        <h5>Closed </h5>
                        <div class="form-check form-switch">
                          <input class="form-check-input" name="chThursday" type="checkbox" value="Yes"   @if(old('chThursday',$ThursClosed)=="Yes") checked @endif role="switch" id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-12">
                  <div class="time-bx">
                    <h4>Friday</h4>
                    <div class="row">
                      <div class="col-lg-5 col-12">
                        <h5>Opening time</h5>
                        <input type="text" value="{{old('opFridayhr',$FriOpenhr) ?? '00:00' }}" name="opFridayhr"  class="form-control timepicker" placeholder="Opening time">
                      </div>
                      <div class="col-lg-5 col-12">
                        <h5>Closing time</h5>
                        <input type="text" value="{{old('clFridayhr',$FriCloseHr) ?? '00:00' }}" name="clFridayhr"  class="form-control timepicker" placeholder="Closing time">
                      </div>
                      <div class="col-lg-2 col-12">
                        <h5>Closed </h5>
                        <div class="form-check form-switch">
                          <input class="form-check-input" name="chFriday" type="checkbox" value="Yes"   @if(old('chFriday',$FriClosed)=="Yes") checked @endif role="switch" id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-12">
                  <div class="time-bx">
                    <h4>Saturday</h4>
                    <div class="row">
                      <div class="col-lg-5 col-12">
                        <h5>Opening time</h5>
                        <input type="text" value="{{old('opSaturdayhr',$SatOpenhr) ?? '00:00' }}" name="opSaturdayhr" class="form-control timepicker" placeholder="Opening time">
                      </div>
                      <div class="col-lg-5 col-12">
                        <h5>Closing time</h5>
                        <input type="text" value="{{old('clSaturdayhr',$SatCloseHr) ?? '00:00' }}" name="clSaturdayhr"  class="form-control timepicker" placeholder="Closing time">
                      </div>
                      <div class="col-lg-2 col-12">
                        <h5>Closed </h5>
                        <div class="form-check form-switch">
                          <input class="form-check-input" name="chSaturday" type="checkbox"  @if(old('chSaturday',$SatClosed)=="Yes") checked @endif value="Yes" role="switch" id="flexSwitchCheckDefault">
                          <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
     
      
      <div class="row">
            <div class="col-lg-12">
              <button type="submit" class="bl-btn">submit</button>
            </div>
          </div>
      
       <?php } ?>
      </div>
    
      </div>
    
    
	
	
	
	
	
	
	
	
	
	
    
        </div><!--12-->
        </div><!--row-->
      </form>
    
    
    <div class="card answer">
            <div class="row">
            
            <div class="col-lg-12"><h3>Change Password </h3></div>
            </div>
           @if(Session::has('message_not_match')) <div class="alert alert-danger">{{Session::get('message_not_match') }}</div> @endif
			<form action="{{ route('user.pw.reset') }}" method="POST">
                @csrf
            <div class="row">
            
            
            
				  <input type="hidden" name="email" value="{{ Auth::guard('user')->user()->email}}">
                 <div class="col-lg-4 col-12">
                      <div class="form-group">
                        <label>Current password:</label>
                      <input name="oldPassword" type="password" class="form-control {{ $errors->has('oldPassword') ? ' is-invalid' : '' }}"  placeholder="" value="{{ old('oldPassword') }}">
                     @if ($errors->has('oldPassword'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('oldPassword') }}</strong>
                            </span>
							
                        @endif
                      </div>
                    </div>
                    <div class="col-lg-4 col-12">
                      <div class="form-group">
                       <label>New password:</label>
                      <input value="{{ old('password') }}" id="password" type="password" class="pr-password  form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    
                      <div class="col-lg-4 col-12">
                      <div class="form-group">
                         <label>Confirm new password:</label>
                      <input value="{{ old('password_confirmation') }}" id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                      </div>
                    </div>
                   
                   
                    <div class="col-lg-4 col-12">
                      <div class="form-group">
                   <button type="submit" class="bl-btn">update password</button>
                   </div>
                    </div>
                   
                 
                    
                  </div>
                  
                  
                  </form>
                  
                  
                  
              
            </div> 
    </div>
  </div>
  </div>
  </div>
  
  
  
<!--  
  </div>
  </div>
  -->
  
  
  <div class="modal fade" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="MymodalLabel">Request for Account Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="fnclosepopup1()"> <span aria-hidden="true">&times;</span> </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Reason:</label>
            <br>
            <textarea id="reason_delete" class="form-control" required></textarea>
          </div>
          <button  type="submit"  class="bl-btn mar-10" onClick="fnsubmitdelete()">SUBMIT</button>
        </div>
        <div class="modal-footer"> </div>
      </div>
    </div>
  </div>
  <div class="col-lg-12"> <a href="javascript:void(0)" 
   @if($delete_request_active==false)
     onClick="deleteProfileaccount({{Auth::guard('user')->user()->id}})" 
     class="default-btn ctr mr-pro"
   @else
     class="default-btn ctr mr-pro gray-btn45"
      title="Already a Request for Account Delete Sent to Admin"
   @endif 
    >Request for Account Delete</a> </div>
</sectiion>
@endsection
@section('footer_script')
<link rel="stylesheet" href="{{ asset('/css/jquery.passwordRequirements.css')}}">
<script src="{{ asset('js/jquery.passwordRequirements.js')}}"></script>
<script src="{{ asset('js/jquery.passwordRequirements.min.js')}}"></script>
<script>
     //var $fmcg = $.noConflict();   
        $fmcg(document).ready( function () {
            $fmcg(".pr-password").passwordRequirements({

});
        });


        
    </script>
<script>
 
  $fmcg(document).ready(function() {
         $fmcg('.multitags').tagsinput({
            trimValue: true,
            confirmKeys: [13, 44],
            focusClass: 'my-focus-class'
          });     
});

</script>
<link rel="stylesheet" href="{{ asset('/css/intlTelInput.css')}}">
<script src="{{ asset('js/intlTelInput.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-tagsinput.css')}}">

<!--<link rel="stylesheet" href="{{ asset('/admin1/css/tagsbootstrap.min.css')}}">-->

<script src="{{ asset('/admin1/js/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('/admin1/js/tether.min.js')}}"></script>
<script src="{{ asset('/admin1/js/moment.min.js')}}"></script>
<script src="{{ asset('/admin1/js/bootstrap-datetimepicker.min.js')}}"></script>
<!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css'> -->
<!-- <link rel="stylesheet" href="{{ asset('/admin1/css/font-awesome5.8.1cssall.min.css')}}">  
 -->
<link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-datetimepicker.min.css')}}">

<script src="https://use.fontawesome.com/6b4068de03.js"></script>

<script>
  var input = window.intlTelInput(document.querySelector("#phone"), {
  separateDialCode: true,
  preferredCountries:["nl"],
  hiddenInput: "phone",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});


  

function fnclosepopup1(){
    $fmcg("#Mymodal").modal('hide');
}

function fnsubmitdelete(){

  $fmcg.ajax({
            url: "{{ url('profile_account_delete') }}",
               type: "get",
               data:{ 
                   _token:'{{ csrf_token() }}',
                   user_id: '<?=Auth::guard('user')->user()->id?>',
                   reason:$fmcg("#reason_delete").val() ,
               },
               async:true,
               cache: false,
               dataType: 'json',
               success: function(){ 
                $fmcg("#Mymodal").modal('hide');
                 swal("Request for removing your account successfully placed. Our admin will remove it very soon", "", "success");
                 location.reload();
             } ,
             error: function(XMLHttpRequest, textStatus, errorThrown) { 
               swal(errorThrown, "", "error");
             }  
   
           })  ;

}
function deleteProfileaccount(userid){

  $fmcg("#reason_delete").val('');
  swal({
    title:"Do you really want to delete? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      
         $fmcg('#Mymodal').modal('show');

    } 
    else {
          
        }
    });  
}
$fmcg(document).ready( function () {

      $fmcg(".timepicker").datetimepicker({
          format: "HH:mm",
          icons: {
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down"
          }
        });
    $fmcg('#register').on( "click", function() {
       var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
      $fmcg("input[name='phone[full]'").val(full_number);

      });
    });
        </script>
<script type="text/javascript">
    $fmcg( document ).ready(function() {

       
      $fmcg(".coupon_question").click(function() {
    if($fmcg(this).is(":checked")) {
        $fmcg(".answer").show();
        $fmcg(".bb1").hide();
    } else {
        $fmcg(".answer").hide();
        $fmcg(".bb1").show();
    }
    
});



        $fmcg('#upload_image').click(function(){  
            $fmcg('#imgupload').trigger('click'); 
        });


$fmcg(".image").change(function(e) {  
var data = new FormData();
data.append('image', $fmcg('input[type=file]')[0].files[0]);
data.append('_token', "{{ csrf_token() }}"); 
$fmcg.ajax({
        url:'{{route('update-image')}}',
        type: 'POST',
        data : data,
        enctype : 'multipart/form-data',
        contentType: false,
        processData: false,
        success: function( data ) { 
            var baseUrl = "{{asset('')}}";
            var imageUrl = baseUrl + data.image_path;
            $fmcg('.prof_img').attr('src', imageUrl);
          //  $fmcg('#changeimage').html('<img src="'+ imageUrl +'" height="120px" width="150px">');
        },
        error: function() {
          //  alert('Upload Failed');
        }
       });   
    });
});
</script>
 <script type="text/javascript">
                
        $fmcg(document).ready( function () {
           $fmcg('#user_status').on('change', function() {
  var status = this.value ;
 
    $.ajax({
            url: "{{ url('user_status') }}",
               type: "get",
               data:{ 
                   _token:'{{ csrf_token() }}',
                   user_id: '<?=Auth::guard('user')->user()->id?>',
                   user_status:status ,
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
    </script>
<style type="text/css">
  
  @media (min-width: 1300px) {
.container {width: 1290px!important;}

.cb4-1{    flex: 0 0 18%;
    max-width: 25%;}
    
    .cb4-2{    flex: 0 0 14%;
    max-width: 25%;}
}
</style>
@endsection