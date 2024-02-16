@extends('layouts.template')
@section('title', 'Edit Buyer Profile')
@section('content')
<?php 
function explodHr($opentime)
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
<sectiion class="seller-page">
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  <div class="container seller-page-2">
    <div class="row"> </div>
  </div>
  <div class="container seller-page-2">
    <div class="row">
      <div class="col-lg-12">
     
     <form method="post" action="{{route('update.buyer')}}" enctype='multipart/form-data' >
     @csrf
        <div class="pr-bottom">
          <div class="card">
         
            <h3>Edit buyer profile</h3>
            @if(Session::has('message') && (Session::get('message') != "The old password does not match our records." )) <div class="alert alert-success">{{Session::get('message') }}</div> 
            @elseif(Session::has('message_not_match')) <div class="alert alert-danger">{{Session::get('message_not_match') }}</div> 
       @endif
         <div class="row">
              <div class="col-lg-3 col-12">
              <?php if(!empty(Auth::guard('user')->user()->profile_pic)) 
$img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
else  $img_path = asset('uploads/defaultImages/default_avatar.png'); ?>
                <div class="pr-logo">
            <img class="prof_img" src="  {{ $img_path }} "> <a href="javascript: void(0)" class="edit" id="upload_image"><i class="fa fa-camera" aria-hidden="true"></i></a> </div>
                <input type="file" id="imgupload" class="image" name="image" style="display:none" accept="image/*" /> 
            
            </div>
              <div class="col-lg-9 col-12">
                <div class="row">
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> Name <sup>*</sup></label>
                      <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $user->name)  }}">
                      
                      @if ($errors->has('name'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('name') }}</strong>
                                 </span>
                             @endif
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> Surname <sup>*</sup></label>
                      <input type="text" class="form-control {{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname', $user->surname)  }}">
                      @if ($errors->has('surname'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('surname') }}</strong>
                                 </span>
                             @endif
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label>Email <sup>*</sup></label>
                      <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"  name="email" value="{{ old('email', $user->email)  }}">
                      @if ($errors->has('email'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('email') }}</strong>
                                 </span>
                             @endif
                    </div>
                  </div>
                 
                  <div class="col-lg-6 col-12">
                  <div class="form-group pn-no">
                  <label>Phone number<sup>*</sup></label>
                  <input name="full" type="text" value="{{  $user->phone ?? ''}}" id="phone" class="form-control pno05 {{ $errors->has('phone') ? ' is-invalid' : '' }} "/>

                  @if ($errors->has('phone'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('phone') }}</strong>
                                 </span>
                             @endif
                </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> About</label>
                      <input type="text" class="form-control "  name="about" value="{{ old('about', $user->about)  }}" >
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> Address <sup>*</sup></label>
                      <input type="text" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"  name="address" value="{{ old('address', $user->address)  }}" >
                      @if ($errors->has('address'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('address') }}</strong>
                                 </span>
                             @endif
                    </div>
                  </div>


                  <div class="col-lg-6 col-12">
                  <div class="form-group">
                  <label>Country<sup>*</sup></label>
                  </label> 
                  <select name="country_id" id="country_id" class="form-control  {{ $errors->has('country_id') ? ' is-invalid' : '' }}" aria-label="Country"   >
                  <option value="">Country Name</option>
                  @foreach ($countries as $country)
                 
                  <option  {{old('country_id',$user->country_id) == $country->id ? 'selected' : ''}}   value="{{$country->id}}">{{$country->name}}</option>
                  @endforeach 
                  </select>
                  @if ($errors->has('country_id'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('country_id') }}</strong>
                                 </span>
                             @endif
                </div>
                  </div>




                  
                </div>
              
              </div>
            </div>
          </div>
          
          
          
          
          
          
          
         
            
            
            <div class="card answer">
            <div class="row">
            
            <div class="col-lg-12"><h3>Company information </h3></div>
            </div>
            
            <div class="row">
            
            
            
           

                    <div class="col-lg-4 col-12">
                      <div class="form-group">
                        <label>   Company name</label>
                        <input class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" type="text" name="company_name"  value="{{ old('company_name', $user->BuyerCompany->company_name ?? '')  }}">
                        @if ($errors->has('company_name'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('company_name') }}</strong>
                                 </span>
                             @endif
                      </div>
                    </div>
                    <div class="col-lg-4 col-12">
                      <div class="form-group">
                        <label>Street</label>
                        <input class="form-control {{ $errors->has('company_street') ? ' is-invalid' : '' }}" type="text" name="company_street" value="{{ old('company_street', $user->BuyerCompany->company_street ?? '')  }}">
                        @if ($errors->has('company_street'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('company_street') }}</strong>
                                 </span>
                             @endif
                      </div>
                    </div>
                    
                      <div class="col-lg-4 col-12">
                      <div class="form-group">
                        <label>Zip Code </label>
                        <input class="form-control {{ $errors->has('company_zip') ? ' is-invalid' : '' }}" type="text"  name="company_zip" value="{{ old('company_zip', $user->BuyerCompany->company_zip ?? '')  }}">
                        @if ($errors->has('company_zip'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('company_zip') }}</strong>
                                 </span>
                             @endif
                      </div>
                    </div>
                   
                   
                   
                   
                             <div class="col-lg-4 col-12">
                      <div class="form-group">
                        <label> Location </label>
                        <input class="form-control {{ $errors->has('company_location') ? ' is-invalid' : '' }}" type="text" name="company_location" value="{{ old('company_location', $user->BuyerCompany->company_location ?? '')  }}">
                        @if ($errors->has('company_location'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('company_location') }}</strong>
                                 </span>
                             @endif
                      </div>
                    </div>
                    
                    <div class="col-lg-4 col-12">
                <div class="form-group">
                  <label>About Company </label>
                  <textarea  name="about_company"  id="exampleFormControlTextarea1"  class="form-control {{ $errors->has('about_company') ? ' is-invalid' : '' }}"  >{{ $user->BuyerCompany->about_company?? '' }}</textarea>
                  @if ($errors->has('about_company'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('about_company') }}</strong>
                                 </span>
                             @endif
                </div>
              </div>


                    <?php 
                  
          $openingTimes = $user->SellerOpeningTime ;  
          $SunOpen_am_pm = $MonOpen_am_pm = $TueOpen_am_pm = $WedOpen_am_pm = $ThusOpen_am_pm = $FriOpen_am_pm = $SatOpen_am_pm = $SunClose_am_pm = $MonClose_am_pm = $TueClose_am_pm = $WedClose_am_pm = $ThuClose_am_pm = $FriClose_am_pm = $ThuOpen_am_pm = $SatClose_am_pm =  $SunClosed = $MonClosed =  $TuesClosed = $WedClosed = $ThursClosed = $FriClosed = $SatClosed ="";
            ?>
                  @foreach($openingTimes as $openingTime ) 
                  <?php  
                  // $OpenHr= explodHr($openingTime->opening_time);
                  // $CloseHr= explodHr($openingTime->closing_time);  
                  $day =  $openingTime->day; 
                  ?>
                 
                 
                <?php  
                if($day=="Sunday") {
               
                if(!empty($openingTime->opening_time)) {
                  $SunOpenhr = $openingTime->opening_time; 
                  $SunCloseHr = $openingTime->closing_time; }
                  $SunClosed = $openingTime->closed; 
                  $SunOpen_am_pm  = $openingTime->Open_am_pm   ;  
                  $SunClose_am_pm    = $openingTime->Close_am_pm  ;
                }
                ?>
              
                  @if($openingTime->day=="Monday") 
                <?php 
                if(!empty($openingTime->opening_time)) {
                  $MonOpenhr = $openingTime->opening_time; 
                  $MonCloseHr = $openingTime->closing_time; }
                  $MonClosed = $openingTime->closed;
                  $MonOpen_am_pm  = $openingTime->Open_am_pm   ; 
                  $MonClose_am_pm    = $openingTime->Close_am_pm  ; 
                  ?>
                  @endif
                  @if($openingTime->day=="Tuesday")
                <?php  
                if(!empty($openingTime->opening_time)) { 
                  $TuesOpenhr = $openingTime->opening_time; 
                  $TuesCloseHr = $openingTime->closing_time; } 
                 $TuesClosed = $openingTime->closed;
                 $TueOpen_am_pm   = $openingTime->Open_am_pm   ; 
                 $TueClose_am_pm  = $openingTime->Close_am_pm  ; 
                  ?>
                  @endif
                  @if($openingTime->day=="Wednesday")
                <?php
                if(!empty($openingTime->opening_time)) {
                  $WedOpenhr = $openingTime->opening_time; 
                  $WedCloseHr = $openingTime->closing_time; }
                   $WedClosed = $openingTime->closed;
                   $WedOpen_am_pm    = $openingTime->Open_am_pm   ; 
                   $WedClose_am_pm   = $openingTime->Close_am_pm  ; 
                  ?>
                  @endif
                  @if($openingTime->day=="Thursday")
                <?php
                if(!empty($openingTime->opening_time)) {
                  $ThursOpenhr = $openingTime->opening_time; 
                  $ThursCloseHr = $openingTime->closing_time; }
                  $ThursClosed = $openingTime->closed;
                  $ThuOpen_am_pm  = $openingTime->Open_am_pm   ; 
                  $ThuClose_am_pm    = $openingTime->Close_am_pm  ; 
                  ?>
                  @endif
                  @if($openingTime->day=="Friday")
                <?php 
                if(!empty($openingTime->opening_time)) {
                  $FriOpenhr = $openingTime->opening_time; 
                  $FriCloseHr = $openingTime->closing_time; }
                  $FriClosed = $openingTime->closed;
                  $FriOpen_am_pm  = $openingTime->Open_am_pm   ; 
                  $FriClose_am_pm    = $openingTime->Close_am_pm  ; 
                  ?>
                  @endif
                  @if($openingTime->day=="Saturday")
                <?php  
                if(!empty($openingTime->opening_time)) {
                  $SatOpenhr = $openingTime->opening_time;
                  $SatCloseHr = $openingTime->closing_time; }
                  $SatClosed = $openingTime->closed;
                  $SatOpen_am_pm  = $openingTime->Open_am_pm   ; 
                  $SatClose_am_pm    = $openingTime->Close_am_pm  ; 
                  ?>
                  @endif  
                  
                  @endforeach
<?php
if(empty($SunOpenhr) || $SunOpenhr==":") $SunOpenhr="00:00" ; 
if(empty($SunCloseHr) || $SunCloseHr==":") $SunCloseHr="00:00" ; 
if(empty($MonOpenhr) || $MonOpenhr==":") $MonOpenhr="00:00" ; 
if(empty($MonCloseHr) || $MonCloseHr==":") $MonCloseHr="00:00" ; 
if(empty($TuesOpenhr) || $TuesOpenhr==":") $TuesOpenhr="00:00" ; 
if(empty($TuesCloseHr) || $TuesCloseHr==":") $TuesCloseHr="00:00" ;
if(empty($WedOpenhr) || $WedOpenhr==":") $WedOpenhr="00:00" ;
if(empty($WedCloseHr) || $WedCloseHr==":") $WedCloseHr="00:00" ; 
if(empty($ThursOpenhr) || $ThursOpenhr==":") $ThursOpenhr="00:00" ; 
if(empty($ThursCloseHr) || $ThursCloseHr==":") $ThursCloseHr="00:00" ; 
if(empty($FriOpenhr) || $FriOpenhr==":") $FriOpenhr="00:00" ; 
if(empty($FriCloseHr) || $FriCloseHr==":") $FriCloseHr="00:00" ; 
if(empty($SatOpenhr) || $SatOpenhr==":") $SatOpenhr="00:00" ; 
if(empty($SatCloseHr) || $SatCloseHr==":") $SatCloseHr="00:00" ; 
?>
           
                    
                      <div class="col-lg-4 col-12">
                          
                          
                      <div class="form-group">
                    <div class="file-drop-area form-group">
                      <label>Picture upload</label>
                      
                      <div class="row">
                          
                          <div class="col-lg-2">
                      
           @php 
           $company_image =  $user->BuyerCompany->company_image ?? '';
           if(($company_image!=""))
          $cmp_img = asset('uploads/BuyerCompany/').'/'.$company_image;
          else $cmp_img = asset('uploads/defaultImages/seller.jpg');
          @endphp
          
          <div class="uploadedimg">

             <img src="{{ $cmp_img}}" ></div>
         @php  @endphp
                      
                    </div>
                  
                    
                    <div class="col-lg-10 col-12">
                      <div class="form-group">
                    <div class="file-drop-area form-group">
                    
                      <input type="file" class="file-input form-control {{ $errors->has('company_image') ? ' is-invalid' : '' }} " name="company_image" accept="image/*" >
                      @if ($errors->has('company_image'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('company_image') }}</strong>
                                 </span>
                             @endif
                    </div>
                  </div>
                    </div>
                    
                    </div></div></div></div>
                    
                    
                    

               <div class="row">
                    <div class="col-lg-6 col-12">
                <div class="form-group overflow-hidden1">
                  <label>Accepted payments methods<sup>*</sup></label>
                  
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
                    
                      
                    
                    
                      <div class="col-lg-6 col-12">
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
                    
                    
                  </div>
                  
                  </div>
                  
                 
              
            </div> 

            <div class="card answer">
            <div class="row">
            
            <div class="col-lg-12"><h3>Opening Hours </h3></div>
            </div>
            
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
                           
                  
                  <button type="submit">submit</button>
                    
                    </div>
                  </div>
         </div>
          </form>
          
          
          <div class="card answer">
            <div class="row">
            
            <div class="col-lg-12"><h3>Change Password </h3></div>
            </div>
            @if((Session::get('message') == "The old password does not match our records." )) <div class="alert alert-danger">{{Session::get('message') }}</div> @endif
            <form action="{{ route('user.pw.reset') }}" method="POST">
                @csrf
            <div class="row">
            
            
            
              <input type="hidden" name="email" value="{{ Auth::guard('user')->user()->email}}">
                 <div class="col-lg-4 col-12">
                      <div class="form-group">
                        <label>Current password:</label>
                      <input name="oldPassword" type="password" class="form-control {{ $errors->has('oldPassword') ? ' is-invalid' : '' }}"  placeholder="" value="">
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
                      <input value="" id="password" type="password" class="pr-password  form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

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
                      <input  id="password-confirm" type="password" class="form-control" name="password_confirmation" >
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
  </div>

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
<link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-tagsinput.css')}}">
<script src="{{ asset('/admin1/js/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('/admin1/js/tether.min.js')}}"></script>
<script src="{{ asset('/admin1/js/moment.min.js')}}"></script>
<script src="{{ asset('js/jquery.passwordRequirements.js')}}"></script>
<script src="{{ asset('js/jquery.passwordRequirements.min.js')}}"></script>
<script src="{{ asset('/admin1/js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-datetimepicker.min.css')}}">

<script>
       
        $fmcg(document).ready( function () {
            $fmcg(".pr-password").passwordRequirements({});
             $fmcg(".timepicker").datetimepicker({
          format: "HH:mm",
          icons: {
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down"
          }
        });
        });
</script>
<script>
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


<script>
  var input = window.intlTelInput(document.querySelector("#phone"), {
  separateDialCode: true,
  preferredCountries:["in"],
  hiddenInput: "phone",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});


    $fmcg(document).ready( function () {
    $fmcg('#register').on( "click", function() {
 var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
$fmcg("input[name='phone[full]'").val(full_number);

});
});
        </script>
<script type="text/javascript">
    $fmcg( document ).ready(function() {
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
            alert('Upload Failed');
        }
   });   
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