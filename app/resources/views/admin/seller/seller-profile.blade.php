@extends('admin.master')
@section('title', 'Seller Creation')
@section('breadcrumb') Seller Creation @endsection
@section('content')
<style>
  input[type="time"]::-webkit-calendar-picker-indicator {
  display: none !important;
}
</style>

 <?php 
          if(!empty($seller))  
           {      
                      $name=$seller->name;
                      $surname=$seller->surname;
                      $email=$seller->email;
                      $company_name=$seller->BuyerCompany->company_name??'';
                      $company_street=$seller->BuyerCompany->company_street??'';
                      $company_zip=$seller->BuyerCompany->company_zip??'';
                      $gst_no=$seller->BuyerCompany->gst_no??'';
                      $registration_number=$seller->BuyerCompany->registration_no??'';
                      $company_location=$seller->BuyerCompany->company_location??'';
                      $company_type=$seller->BuyerCompany->company_type??'';
                      $about_company=$seller->BuyerCompany->about_company??'';
                      $position=$seller->position;
                      $country_id=$seller->country_id;
                      $default_password=$seller->default_password;
                      $user_id=$seller->id;
                      $opSundayhr=$SunOpen_am_pm =$clSundayhr = $SunClose_am_pm= $chSunday= $opMondayhr= $MonOpen_am_pm =$clMondayhr =$MonClose_am_pm =$chMonday =$opTuesdayhr=  $TueOpen_am_pm =$clTuesdayhr =$TueClose_am_pm=  $chTuesday= $opWednesdayhr =$WedOpen_am_pm= $clWednesdayhr= $WedClose_am_pm =$chWednesday= $opThursdayhr= $ThuOpen_am_pm=$clThursdayhr = $ThuClose_am_pm=  $chThursday= $opFridayhr= $FriOpen_am_pm = $clFridayhr =$FriClose_am_pm = $chFriday = $opSaturdayhr =$SatOpen_am_pm =$SatClose_am_pm= $clSaturdayhr=$chSaturday="";

                $accepted_payments="";
                if(!empty($seller->BuyerCompany->accepted_payments) ) 
                    $accepted_payments = $seller->BuyerCompany->accepted_payments;

                 $languages_speak ="";
                if(!empty($seller->BuyerCompany->languages_speak) ) 
                   $languages_speak = $seller->BuyerCompany->languages_speak; 


                 $deliver_options=""; 
                 
                 if(!empty($seller->BuyerCompany->deliver_options) ) 
                   $deliver_options = $seller->BuyerCompany->deliver_options; 
    

                 foreach($SellerOpeningTimes as $SellerOpeningTime ) {


                if( $SellerOpeningTime->day=="Sunday") {
                
                      $opSundayhr=$SellerOpeningTime->opening_time;
                      $clSundayhr =$SellerOpeningTime->closing_time;
                      $chSunday=$SellerOpeningTime->closed;
                }

                if($SellerOpeningTime->day=="Monday"){ 
                 
                      $opMondayhr=$SellerOpeningTime->opening_time;
                      $clMondayhr =$SellerOpeningTime->closing_time;
                      $chMonday =$SellerOpeningTime->closed;
                    
                  }
                 if($SellerOpeningTime->day=="Tuesday"){
              
                   
                      $opTuesdayhr= $SellerOpeningTime->opening_time;
                      $clTuesdayhr =$SellerOpeningTime->closing_time;
                      $chTuesday=$SellerOpeningTime->closed;

                  
                }
                if($SellerOpeningTime->day=="Wednesday"){
                
                      $opWednesdayhr =$SellerOpeningTime->opening_time;
                      $clWednesdayhr= $SellerOpeningTime->closing_time;
                      $chWednesday=$SellerOpeningTime->closed;
                 
               }
               if($SellerOpeningTime->day=="Thursday")
               {
               
                      $opThursdayhr=$SellerOpeningTime->opening_time;
                      $clThursdayhr =$SellerOpeningTime->closing_time;
                      $chThursday=$SellerOpeningTime->closed;
                
              }
              if($SellerOpeningTime->day=="Friday")
              { 
                
                               
                      $opFridayhr=$SellerOpeningTime->opening_time;
                      $clFridayhr =$SellerOpeningTime->closing_time;
                      $chFriday =$SellerOpeningTime->closed;
                
              }
                if($SellerOpeningTime->day=="Saturday")
                {
                      $opSaturdayhr =$SellerOpeningTime->opening_time;
                      $clSaturdayhr= $SellerOpeningTime->closing_time ;
                      $chSaturday =$SellerOpeningTime->closed;
               }
                  
            }

                      
                      $phone=$seller->phone;
                      $duty = 'Edit' ;
    }
    else
     {           $name="";
                  $surname="";
                  $email="";
                  $company_name=$company_location=$company_type=$about_company="";
                  $company_street="";
                  $company_zip="";
                  $gst_no="";
                  $registration_number="";
                  $position="";
                  $country_id=$default_password="";
                  $accepted_payments="";
                  $opSundayhr=$SunOpen_am_pm =$clSundayhr = $SunClose_am_pm= $chSunday= $opMondayhr= $MonOpen_am_pm =$clMondayhr =$MonClose_am_pm =$chMonday =$opTuesdayhr=  $TueOpen_am_pm =$clTuesdayhr =$TueClose_am_pm=  $chTuesday= $opWednesdayhr =$WedOpen_am_pm= $clWednesdayhr= $WedClose_am_pm =$chWednesday= $opThursdayhr= $ThuOpen_am_pm=$clThursdayhr = $ThuClose_am_pm=  $chThursday= $opFridayhr= $FriOpen_am_pm = $clFridayhr =$FriClose_am_pm = $chFriday = $opSaturdayhr =$SatOpen_am_pm =$SatClose_am_pm= $clSaturdayhr=$chSaturday= "";
                   $deliver_options =$languages_speak="";
                    $phone="";
                 $user_id="";      
                  $duty = 'Creation' ;
      }?>
<div class="body flex-grow-1 px-3">
    
    <div class="container-lg">
      <div class="card-out mb-4 inner-form create-prof-new">
        <h2>{{$duty}} seller profile</h2>
        <div class="card-body">


        <form id="formsubmit">
           
   
        
        <div class="row">
            <div class="col-lg-12 col-12">
            <div class="card">
                 <div class="card-header">Personal information</div>
              <div class="card-body">
          <div class="row">
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <input type="hidden" name="user_id" value="{{$user_id}}">
                  <label> Name<sup>*</sup></label>
                  <input type="text" class="form-control  {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name',$name) }}" name="name" >
                  <span id="txtname"></span>

                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label> Surname<sup>*</sup></label>
                  <input type="text" class="form-control  {{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname',$surname) }}" >
                 <span id="txtsurname"></span>

                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>E-mail<sup>*</sup></label>
                  <input type="text" class="form-control  {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email',$email) }}" >
                  <span id="txtemail"></span>

                </div>
              </div>
               <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Position<sup>*</sup></label> 
                  
                  <input type="text" class="form-control  {{ $errors->has('position') ? ' is-invalid' : '' }}"  name="position" value="{{ old('position',$position) }}" >
                  <span id="postin"></span>
                </div>
              </div>
              <div class="col-lg-6 col-12">
                      <div class="form-group">
                        <label>Picture upload</label>
                    <div class="file-drop-area form-group">
                      
                      <input type="file"  name="profile_pic" id="imgpicture" class="file-input form-control" accept=".jfif,.jpg,.jpeg,.png,.gif">
                    </div>
                    @php if($duty == 'Edit'){
                      $user_image =  $seller->profile_pic ?? '';
                       if(($user_image!=""))
                      $user_image = asset('uploads/userImages/').'/'.$user_image;
                      else
                           $user_image = asset('uploads/defaultImages/').'/default_avatar.png';
                      @endphp
                     <img src="{{ $user_image}}" style="width:120px;">
                   @php  } @endphp
                   <span id="txtprofile_pic"></span>

                  </div>
                    </div>
                    
                    
                   <div class="col-lg-4 col-12">
                <div class="form-group">
                  <label>Select the categories your company is active in (multiple selection possible)<sup>*</sup></label>
               
        <div class="autocomplete form-group" >
     
              <select type="text" multiple placeholder="Select" name="offline_categories[]" id='offline_categories' class="form-control" multiple="multiple">
            
                <?php $off_cats =[];  ?>
              @foreach ($categories as $category)
                      @if(!empty($seller_OflnCats->category_id))
                       <?php $off_cats = explode(",",$seller_OflnCats->category_id);?>
                       @endif
                       @if(in_array($category->id, $off_cats))
                       <option value="{{ $category->id }}" selected="true" />{{ $category->name }}</option>
                       @else
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                       @endif 
     @endforeach 
              
              
              
              </select>
              
              <span id="offline_categories1"></span>
              
            </div>
        </div>
              </div>
                    
                    
                    
                  </div>
              </div>
              </div>
              </div>  
              </div>  
              <div class="card">
                 <div class="card-header">Company information </div>
              <div class="card-body">  
               <div class="row">    
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label> Company<sup>*</sup></label>
                  
                  <input type="text" class="form-control  {{ $errors->has('company_name') ? ' is-invalid' : '' }}"  name="company_name" value="{{ old('company_name',$company_name) }}" >
                 <span id="txtcompany"></span>

                </div>
              </div>

               <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Company Type<sup>*</sup></label>
                  
                  <div class="autocomplete form-group cmp-type" >
     
              <select type="text" multiple placeholder="Select" name="company_type[]" id='company_type' class="form-control{{ $errors->has('product_dimension') ? ' is-invalid' : '' }}" multiple="multiple">
              <?php $ctypes =[]; ?>
              @foreach ($company_types as $company_type)
                      @if(!empty($seller->BuyerCompany->company_type))
                       <?php $ctypes = explode(",",$seller->BuyerCompany->company_type);?>
                       @endif
                       @if(in_array($company_type->id, $ctypes))
                       <option value="{{ $company_type->id }}" selected="true" />{{ $company_type->company_type }}</option>
                       @else
                        <option value="{{ $company_type->id }}">{{ $company_type->company_type }}</option>
                       @endif 
             @endforeach 
              </select>
              
              
            </div>
                  
                  <span id="txtcompanytype"></span> </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Street<sup>*</sup></label>
                  
                  <input type="text" class="form-control  {{ $errors->has('company_street') ? ' is-invalid' : '' }}"  name="company_street" value="{{ old('company_street',$company_street) }}" >
                 <span id="txtcompanystreet"></span>

                </div>
              </div>
              <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label>Location<sup>*</sup></label>
                      <input type="text" class="form-control  {{ $errors->has('company_location') ? ' is-invalid' : '' }}"  name="company_location" value="{{ old('company_location', $company_location)  }}" >
                      <span id="txtcompanylocation"></span></div>
                  </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Zip code<sup>*</sup></label>  
                  
                  <input type="text" class="form-control  {{ $errors->has('company_zip') ? ' is-invalid' : '' }}"  name="company_zip" value="{{ old('company_zip',$company_zip) }}" >
                  <span id="txtcompanyzipcode"></span>

                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Tax Registration No.</label>
                  <input type="text" class="form-control"  name="gst_no" value="{{ old('gst_no',$gst_no) }}">
   
                </div> 
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Registration No</label>
                  <input type="text" class="form-control "  name="registration_number" value="{{ old('registration_number',$registration_number) }}"> 
                 
                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Country<sup>*</sup></label>
                  
                  <select class="form-control  {{ $errors->has('country_id') ? ' is-invalid' : '' }}" aria-label="Default select example"    name="country_id" id="country_id" >
                  <option value="">Country Name</option>
                  @foreach ($countries as $val)
                  <option value="{{$val->id}}"   @if($val->id==old('country_id',$country_id))) selected="selected"  @endif >{{$val->name}}</option>
                  @endforeach 
                  </select>
                  <span id="txtcnryid"></span>

                </div>
              </div>
               
             
            </div>
            <div class="row">
              
              @if($duty != 'Edit')
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Password<sup>*</sup></label> 
                  
                   <input type="password" class="form-control  {{ $errors->has('default_password') ? ' is-invalid' : '' }}"  name="default_password" value="{{ old('default_password') }}" >
                  <span id="txtdefault_password"></span>

                </div>
              </div>
               @endif

               <div class="col-lg-8 col-12">
                <div class="form-group">
                  <label>About Company<sup>*</sup></label>
                  <textarea name="about_company"  class="form-control {{ $errors->has('about_company') ? ' is-invalid' : '' }}"  >{{ $about_company}}</textarea>
                 <span id="abt_cmpny"></span>
                  </div>
              </div>
              <div class="col-lg-12 col-12">
                <div class="form-group overflow-hidden1">
                  <label>Accepted payments methods<sup>*</sup></label>
                  </label>
                  <div class="float-end1">
<?php 

if(!empty(old('accepted_payments',$accepted_payments))){
 $accepted_payments=old('accepted_payments',$accepted_payments); 
}?>
                  <input  type="text"  class="multitags accepted_payments form-control{{ $errors->has('accepted_payments') ? ' is-invalid' : ''}}"  name="accepted_payments" style="width:100%;" value="{{$accepted_payments}}">
                    </select>
                 <span id="txtacceptedpay"></span>
                </div>
                </div>
              </div>
            </div>
            <div class="row">

              <div class="col-lg-12 col-12">
                <div class="form-group overflow-hidden1">
                  <label>Delivery option</label>
                  
                  <div class="float-end1">
                  <?php 
                  
                  if(!empty(old('deliver_options',$deliver_options))){
                  $deliver_options=old('deliver_options',$deliver_options); 
                  }?>


                  <input  type="text"  class="multitags deliver_options form-control  {{ $errors->has('deliver_options') ? ' is-invalid' : ''}}"  name="deliver_options"  style="width:100%;" value="{{$deliver_options}}">
                   </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-12">
                <div class="form-group overflow-hidden1">
                  <label>Language</label>
                  
                 

                  <div class="float-end1">
                  <?php 
                  if(!empty(old('languages_speak',$languages_speak))){
                  $languages_speak=old('languages_speak',$languages_speak); 
                  }?>
                  <input   type="text"  class="multitags languages_speak form-control {{ $errors->has('languages_speak') ? ' is-invalid' : ''}}"  name="languages_speak"  style="width:100%;" value="{{$languages_speak}}" >
                    </div>
                </div>
              </div>
            </div>

            <div class="row label-full-wth">
              <div class="col-lg-6 col-12">
                <div class="form-group pn-no">
                  <label>Phone number<sup>*</sup></label>
                  <input name="full" type="text" value="{{ old('phone',$phone) }}" id="phone" class="form-control pno05 {{ $errors->has('phone') ? ' is-invalid' : '' }} " maxlength="15"/>

                    <span id="txtphone"></span>

                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <div class="file-drop-area form-group">
                    <label>Picture upload (companies logo/picture)</label>
                    <input class="form-control" type="file" id="company_image" name="company_image" id="formFile" accept="image/*">
                    <span id="prf_img"></span>
                  </div>

                   @php if($duty == 'Edit'){
                      $company_image =  $seller->BuyerCompany->company_image ?? '';
                       if(($company_image!=""))
                      $cmp_img = asset('uploads/BuyerCompany/').'/'.$company_image;
                      else
                           $cmp_img = asset('uploads/defaultImages/').'/seller.jpg';
                       
                      @endphp
                     <img src="{{ $cmp_img}}" style="width:120px;">
                   @php  } @endphp
                </div>
              </div>
            </div>
            <div class="row seller-pr25">
              <div class="col-lg-10 col-12">
                <div class="form-group overflow-hidden1 timimg-s">
                  <label>Opening hours</label>
                  <div class="row hrs-tiitle">
                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <label> Week days</label>
                      </div>
                    </div>
                    <div class="col-lg-4 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <label>Opening time</label>
                      </div>
                    </div>
                    <div class="col-lg-4 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <label> Closing time</label>
                      </div>
                    </div>
                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <label> Closed</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-2 col-12">
                      <div class="dys">Sunday</div>
                    </div>
                    <div class="col-lg-4 col-12">
                      
                       <input type="text"   value="{{old('opSundayhr',$opSundayhr) }}" name="opSundayhr" class="form-control timepicker" placeholder="Opening time">
                   
                   </div>
                    <div class="col-lg-4 col-12">


                      <input type="text"  value="{{old('clSundayhr',$clSundayhr) }}" name="clSundayhr" min="01" max="59" class="form-control timepicker" placeholder="Closing time">
                    
            </div>  
                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <div class="form-check">
                          <input class="form-check-input" name="chSunday" {{ old('chSunday',$chSunday)=='Yes'?'checked':'' }} type="checkbox" value="Yes" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckChecked"> Closed </label>
                        </div>
                      </div>
                    </div>
                  </div> 
                  <div class="row">
                    <div class="col-lg-2 col-12">
                      <div class="dys">Monday</div>
                    </div>
                    <div class="col-lg-4 col-12">
                     <input type="text" value="{{old('opMondayhr',$opMondayhr) }}" name="opMondayhr" min="01" max="24" class="form-control timepicker" placeholder="Opening time">
                     
          
           </div>
                    <div class="col-lg-4 col-12">
                     <input type="text" value="{{old('clMondayhr',$clMondayhr) }}" name="clMondayhr" min="01" max="59" class="form-control timepicker" placeholder="Closing time">
                     
           </div>

                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <div class="form-check">
                          <input class="form-check-input" {{ old('chMonday',$chMonday)=='Yes'?'checked':'' }} name="chMonday" type="checkbox" value="Yes" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckChecked"> Closed </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-2 col-12">
                      <div class="dys">Tuesday</div>
                    </div>

                    <div class="col-lg-4 col-12">
                      <input type="text" value="{{old('opTuesdayhr',$opTuesdayhr) }}" name="opTuesdayhr" min="01" max="24" class="form-control timepicker" placeholder="Opening time">
                      
                    </div>
                    <div class="col-lg-4 col-12">
                      <input type="text" value="{{old('clTuesdayhr',$clTuesdayhr) }}" name="clTuesdayhr" min="01" max="59" class="form-control timepicker" placeholder="Closing time">
                     
           </div>
                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <div class="form-check">
                          <input class="form-check-input" {{ old('chTuesday',$chTuesday)=='Yes'?'checked':'' }} name="chTuesday" type="checkbox" value="Yes" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckChecked"> Closed </label>
                        </div>
                      </div>
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-lg-2 col-12">
                      <div class="dys">Wednesday</div>
                    </div>
                    <div class="col-lg-4 col-12">
                      <input type="text" value="{{old('opWednesdayhr',$opWednesdayhr) }}" name="opWednesdayhr" min="01" max="24" class="form-control timepicker" placeholder="Opening time">
 
           </div>
                    <div class="col-lg-4 col-12">
                      <input type="text" value="{{old('clWednesdayhr',$clWednesdayhr) }}" name="clWednesdayhr" min="01" max="59" class="form-control timepicker" placeholder="Closing time">
                      
            </div>
                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <div class="form-check">
                          <input class="form-check-input" {{ old('chWednesday',$chWednesday)=='Yes'?'checked':'' }} name="chWednesday" type="checkbox" value="Yes" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckChecked"> Closed </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-2 col-12">
                      <div class="dys">Thursday</div>
                    </div>

                 

                    <div class="col-lg-4 col-12">
                      <input type="text" value="{{old('opThursdayhr',$opThursdayhr) }}" name="opThursdayhr" min="01" max="24" class="form-control timepicker" placeholder="Opening time">
                      
           </div>
                    <div class="col-lg-4 col-12">
                     <input type="text" value="{{old('clThursdayhr',$clThursdayhr) }}" name="clThursdayhr" min="01" max="59" class="form-control timepicker" placeholder="Closing time">
                    
           </div>
                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <div class="form-check">
                          <input class="form-check-input" {{ old('chThursday',$chThursday)=='Yes'?'checked':'' }} name="chThursday" type="checkbox" value="Yes" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckChecked"> Closed </label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-2 col-12">
                      <div class="dys">Friday</div>
                    </div>
                    <div class="col-lg-4 col-12">
                      <input type="text" value="{{old('opFridayhr',$opFridayhr) }}" name="opFridayhr" min="01" max="24" class="form-control timepicker" placeholder="Opening time">
                      
            </div>
                    <div class="col-lg-4 col-12">
                       <input type="text" value="{{old('clFridayhr',$clFridayhr) }}" name="clFridayhr" min="01" max="59" class="form-control timepicker" placeholder="Closing time">
                      
            </div>
                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <div class="form-check">
                          <input class="form-check-input" {{ old('chFriday',$chFriday)=='Yes'?'checked':'' }} name="chFriday" type="checkbox" value="Yes" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckChecked"> Closed </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-2 col-12">
                      <div class="dys">Saturday</div>

                    </div>
                    <div class="col-lg-4 col-12">
                     <input type="text" value="{{old('opSaturdayhr',$opSaturdayhr) }}" name="opSaturdayhr" min="01" max="24" class="form-control timepicker" placeholder="Opening time">
                       
           </div>
                    <div class="col-lg-4 col-12">
                      <input type="text" value="{{old('clSaturdayhr',$clSaturdayhr) }}" name="clSaturdayhr" min="01" max="59" class="form-control timepicker" placeholder="Closing time">
                     
           </div>
                    <div class="col-lg-2 col-12">
                      <div class="form-group overflow-hidden1 timimg-s">
                        <div class="form-check">
                          <input class="form-check-input" {{ old('chSaturday',$chSaturday)=='Yes'?'checked':'' }} name="chSaturday" type="checkbox" value="Yes" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckChecked"> Closed </label>
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
                              
             </div>
        
            </div>
           
            
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-datetimepicker.min.css')}}">
<script src="{{ asset('/admin1/js/datepickerjquery.min.js')}}"></script>
<script src="{{ asset('/admin1/js/moment.min.js')}}"></script>
<script src="{{ asset('/admin1/js/twitter-bootstrapbootstrap.min.js')}}"></script>
<script src="{{ asset('/admin1/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/admin1/css/intlTelInput.css')}}">
<script src="{{ asset('/admin1/js/intlTelInput.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/css/select2.min.css')}}">
<script src="{{ asset('js/select2.min.js')}}"></script>

<script>

 var route = "<?=route('offline.categories.admin')?>";
   var route1 = "{{route('company.types')}}";
   $('#company_type').select2({
     placeholder: 'Select',

    
    ajax: {
      url: route1,
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: $.map(data, function(item) {
            return {
              html:"<span>"+item.company_type+"</span>",
              text: item.company_type,
              id: item.id
            }
          })
        };
      },
      cache: true,

    }
  });
   $('#offline_categories').select2({
     placeholder: 'Select',

    
    ajax: {
      url: route,
      dataType: 'json',
      delay: 250,
      <?php if($user_id!="")  { ?>
 data: {
    user_id: <?=$user_id?>
},
<?php } ?>
     
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

  var input = window.intlTelInput(document.querySelector("#phone"), {
  separateDialCode: true,
  preferredCountries:["in"],
  hiddenInput: "phone",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});

(function(jQuery) {
    jQuery(document).ready( function () {

  jQuery("#formsubmit").on('submit', function(e) {
            $(".loaderajax").show();
            e.preventDefault();
            var action_type="<?php echo $duty;?>";
            if(action_type=="Creation")
              var url1="{{route('sellerprofile.store')}}";
            else
              var url1="{{route('sellerprofile.update')}} ";  
            var formData = new FormData($('#formsubmit')[0]);
            formData.append('_token', "{{ csrf_token() }}"); 

                $.ajax({
                type: "post",
                url: url1,
                data: formData,
                enctype : 'multipart/form-data',
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(data) {
                     $(".loaderajax").hide();
                    swal(data, "", "success");
                    $(window).scrollTop(0);
                    var url1="{{route('admin.sellerslist')}}";
                    setTimeout(window.location.href=url1,300);
                },
                error: function (xhr) {
                    $(".loaderajax").hide();
                    var errors = JSON.parse(xhr.responseText);
                    if(errors.errors.name)
                    $("#txtname").empty().append("<p style='color:red'>"+errors.errors.name[0]+"</p>");
                    else
                      $("#txtname").empty();
                    if(errors.errors.email)
                    $("#txtemail").empty().append("<p style='color:red'>"+errors.errors.email[0]+"</p>");
                    else
                      $("#txtemail").empty();
                    if(errors.errors.surname)
                    $("#txtsurname").empty().append("<p style='color:red'>"+errors.errors.surname[0]+"</p>");
                    else
                      $("#txtsurname").empty();
                    if(errors.errors.phone)
                    $("#txtphone").empty().append("<p style='color:red'>"+errors.errors.phone[0]+"</p>");
                    else
                      $("#txtphone").empty();
                    if(errors.errors.address)
                    $("#txtaddress").empty().append("<p style='color:red'>"+errors.errors.address[0]+"</p>");
                    else
                      $("#txtaddress").empty();
                      
                      if(errors.errors.about_company)
                    $("#abt_cmpny").empty().append("<p style='color:red'>"+errors.errors.about_company[0]+"</p>");
                    else
                      $("#abt_cmpny").empty();
                      if(errors.errors.position)
                    $("#postin").empty().append("<p style='color:red'>"+errors.errors.position[0]+"</p>");
                    else
                      $("#postin").empty();
                      if(errors.errors.company_image)
                    $("#prf_img").empty().append("<p style='color:red'>"+errors.errors.company_image[0]+"</p>");
                    else
                      $("#prf_img").empty();
                      
                    if(errors.errors.default_password)
                    $("#txtdefault_password").empty().append("<p style='color:red'>"+errors.errors.default_password[0]+"</p>");
                    else
                      $("#txtdefault_password").empty();
                    if(errors.errors.company_name)
                    $("#txtcompany").empty().append("<p style='color:red'>"+errors.errors.company_name[0]+"</p>");
                    else
                      $("#txtcompany").empty();

                     if(errors.errors.profile_pic)
                    $("#txtprofile_pic").empty().append("<p style='color:red'>"+errors.errors.profile_pic[0]+"</p>");
                    else
                      $("#txtprofile_pic").empty();

                   if(errors.errors.offline_categories)
                   { $("#offline_categories1").empty().append("<p style='color:red'>Select the categories your company is active in</p>");
                       
                     //  alert(errors.errors.offline_categories[0]);
                   }
                    else
                      $("#offline_categories1").empty();
                     if(errors.errors.imgpicture)
                    $("#txtimgpicture").empty().append("<p style='color:red'>"+errors.errors.imgpicture[0]+"</p>");
                    else
                      $("#txtimgpicture").empty();
                    if(errors.errors.company_type)
                    $("#txtcompanytype").empty().append("<p style='color:red'>"+errors.errors.company_type[0]+"</p>");
                    else
                      $("#txtcompanytype").empty();
                     
                    if(errors.errors.company_location)
                    $("#txtcompanylocation").empty().append("<p style='color:red'>"+errors.errors.company_location[0]+"</p>");
                    else
                      $("#txtcompanylocation").empty();
                    if(errors.errors.company_street)
                    $("#txtcompanystreet").empty().append("<p style='color:red'>"+errors.errors.company_street[0]+"</p>");
                    else
                      $("#txtcompanystreet").empty();
                    if(errors.errors.company_zip)
                    $("#txtcompanyzipcode").empty().append("<p style='color:red'>"+errors.errors.company_zip[0]+"</p>");
                    else
                      $("#txtcompanyzipcode").empty();
                    if(errors.errors.country_id)
                    $("#txtcnryid").empty().append("<p style='color:red'>"+errors.errors.country_id[0]+"</p>");
                    else
                      $("#txtcnryid").empty();
                    if(errors.errors.accepted_payments)
                    $("#txtacceptedpay").empty().append("<p style='color:red'>"+errors.errors.accepted_payments[0]+"</p>");
                    else
                      $("#txtacceptedpay").empty();
                    $(window).scrollTop(0);
                               
                }
            });
          });   
      

  jQuery('.multitags').tagsinput({
    trimValue: true,
    confirmKeys: [13, 44],
    focusClass: 'my-focus-class'
  });
      
 jQuery(".timepicker").datetimepicker({
          format: "HH:mm",
          icons: {
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down"
          }
        });
     
      
    jQuery('#register').on( "click", function() {
      var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
      jQuery("input[name='phone[full]'").val(full_number);
    });
});
})(jQuery);
        </script>

<link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{ asset('/admin1/css/tagsbootstrap.min.css')}}">
<script src="{{ asset('/admin1/js/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('/admin1/js/tether.min.js')}}"></script>


<script src="{{ asset('/admin1/js/moment.min.js')}}"></script>
<script src="{{ asset('/admin1/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('/admin1/js/select2.min.js')}}"></script>  
<link rel="stylesheet" href="{{ asset('/css/select2.min.css')}}">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css'>
<!-- <link rel="stylesheet" href="{{ asset('/admin1/css/font-awesome5.8.1cssall.min.css')}}">  
 -->
 <link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-datetimepicker.min.css')}}">


<style type="text/css">
  
  .label-info{
    background-color: dimgray !important;
    border-radius: 5px;
    padding: 2px;
  }
  .bootstrap-tagsinput
  {
    width: 100%;
  }
</style>

  @endsection