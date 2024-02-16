@extends('layouts.template_mobile')
@section('title', 'Edit Guest Profile')
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
<sectiion class="">

  
  
<style> 
		.edit_form{ display:none }
		.view_profile{ display:block }
		</style>
   <div id="submitmessage"></div>
@if($errors->any() || Session::has('message_not_match'))
	<style>
		.edit_form{ display:block !important; }
		.view_profile{ display:none !important; }
	</style>
	<div class="alert alert-danger">Some Error Occured</div>
	   @elseif(Session::has('message_not_match')) 
	   <style>
		.edit_form{ display:block !important; }
		.view_profile{ display:none !important; }
	</style>
	   <div class="alert alert-danger">{{Session::get('message_not_match') }}
	   </div> 
       @endif

	 @if(Session::has('message') && (Session::get('message') != "The old password does not match our records." )) <div class="alert alert-success">{{Session::get('message') }}</div> 
          
       @endif
  
<div class="inner-page-C my-profile view_profile seller-pro  view_profile_mobile gust-sec1">
  <div class="container white_bg">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-title">
          <h1>My Profile</h1>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="pro-user-bx">
            <?php if(!empty(Auth::guard('user')->user()->profile_pic)) 
                        $img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
                  else  $img_path = asset('uploads/defaultImages/default_avatar.png'); ?>
          <div class="pro-user-img"><img src="  {{ $img_path }} " /></div>
          
          <div class="user-pro-right">
            
          
            <a href="javascript:void(0)" onClick="editfrm()" class="border-button-1 view_profile_mobile"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit Profile</a>
            </div>
        </div>
      </div>
    </div>
  </div>
   <div class="pro-infor white_bg">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h3>Profile</h3>
        </div>
      </div>
    </div>
    <div class="seller-page">
      <div class="container">
        <div class="row ">
          <div class="col-lg-12">
            <div class="row">
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label> Name<b>:</b> </label>
                  <span class="prof_val">{{$user->name??''}}</</span> </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label> Surname<b>:</b> </label>
                  <span class="prof_val">{{$user->surname??''}}</span> </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>E-mail<b>:</b> </label>
                  <span class="prof_val">{{$user->email??''}}</span> </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group pn-no77">
                  <label>Phone number<b>:</b> </label>
                  <span class="prof_val">{{$user->phone??''}}</span> </div>
              </div>
              
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Address<b>:</b> </label>
                  <span class="prof_val">{{$user->address??''}}</span> </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="form-group">
                  <label>Country<b>:</b> </label>
                  <span class="prof_val"> @foreach ($countries as $country)
                   @if($user->country_id== $country->id ) {{$country->name??''}}@endif
                   @endforeach </span> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>  



<div class="inner-page-C seller-page my-profile seller-pro  edit_form prof-width-adj edt-gust">
    
    <div class="row">
      <div class="col-lg-12" style=" padding: 0 5% !important;">
          <form method="post" action="{{route('update.guest')}}" enctype='multipart/form-data' >
              @csrf
          <div class="pr-bottom">
              <div class="card">
                  <h3>Edit profile</h3>
                  
              <div class="row">
              <div class="col-lg-3 col-12">
              <?php if(!empty(Auth::guard('user')->user()->profile_pic)) 
                   $img_path = asset('/uploads/userImages/').'/'.Auth::guard('user')->user()->profile_pic;
                   else  $img_path = asset('uploads/defaultImages/default_avatar.png'); ?>
                <div class="pr-logo">
            <img class="prof_img" src="  {{ $img_path }} "> <a href="javascript: void(0)" class="edit" id="upload_image"> <i class="fa fa-camera" aria-hidden="true"></i></a> 
			</div>
                <input type="file" id="imgupload" class="image" name="image" style="display:none" accept="image/*" /> 
            <span id="txtimage"></span> 
            </div>
            </div>
            
            </div>     
       
       
          
        
  
    <div class="pro-infor">
     <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h3>Edit Guest Profile</h3>
        </div>
      </div>
    </div>
    <div class="seller-page">
      <div class="container">
          
         <div class="row">
              
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> Name <sup>*</sup><b>:</b> </label>
                      <span class="prof_val"><input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $user->name)  }}">
                       @if ($errors->has('name'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('name') }}</strong>
                                 </span>
                             @endif
                      </span>
                      
                     
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> Surname <sup>*</sup><b>:</b> </label>
                      <span class="prof_val"><input type="text" class="form-control {{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname', $user->surname)  }}">
                       @if ($errors->has('surname'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('surname') }}</strong>
                                 </span>
                             @endif
                      </span>
                     
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label>Email <sup>*</sup><b>:</b> </label>
                      <span class="prof_val"><input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"  name="email" value="{{ old('email', $user->email)  }}">
                      @if ($errors->has('email'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('email') }}</strong>
                                 </span>
                             @endif
                      </span>
                      
                    </div>
                  </div>
                 
                  <div class="col-lg-6 col-12">
                  <div class="form-group pn-no">
                  <label>Phone number<sup>*</sup><b>:</b> </label>
                  <span class="prof_val"><input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" maxlength="15" name="full" value="{{ old('phone',$user->phone) }}" >
                                 <span  role="alert">
								 @if($errors->has('phone'))
                                 <strong class="error" style="font-size: .875em; color: #dc3545;">{{ $errors->first('phone') }}</strong>
                                 @endif
								 </span>
								 </span>
                </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> About<b>:</b> </label>
                     <span class="prof_val"> <input type="text" class="form-control "  name="about" value="{{ old('about', $user->about)  }}" ></span>
                    </div>
                  </div>
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> Address <sup>*</sup><b>:</b> </label>
                     <span class="prof_val"> <input type="text" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"  name="address" value="{{ old('address', $user->address)  }}" >
                      @if ($errors->has('address'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('address') }}</strong>
                                 </span>
                             @endif
                             </span>
                    </div>
                  </div>


                  <div class="col-lg-6 col-12">
                  <div class="form-group">
                  <label>Country<sup>*</sup><b>:</b> </label>
                  </label> 
                 <span class="prof_val"> <select name="country_id" id="country_id" class="form-control  {{ $errors->has('country_id') ? ' is-invalid' : '' }}" aria-label="Country"   >
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
                </span>
                </div>
                  </div>
                  
                  
                  
                  <div class="col-lg-6 col-12">
                    <div class="form-group">
                      <label> Company Name <b>:</b> </label>
                       
                       <span class="prof_val"><input type="text" class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}"  name="company_name" value="{{ old('company_name', $user->BuyerCompany->company_name)  }}" >
                      @if ($errors->has('company_name'))
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $errors->first('company_name') }}</strong>
                                 </span>
                             @endif
                             </span>
                    </div>
                  </div>
                  
                  
                  
                  
                  

                  
                </div>
              
              </div>
            </div>
          
     <div class="answer">
                          <div class="row">
                              <div class="col-lg-12">
                                    
                           <button type="submit">submit</button>
                       </div>
                     </div>
                    </div>

         </div>
         </div> 
        
		
		   
		 </div> 
		  
	</div>
	</form>
    </div>	
		  
	</div>	
	
	 <form></form>
 <div class="pro-infor adj-div change-pwd-mb-sec">
  
  <div class="container"  style=" padding: 0 2% !important;">
      <div class="row">
        <div class="col-lg-12">
          <h3>Change Password </h3>
        </div>
      </div>
      
      
      
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





  <style>
      .close span {
    color: #fff !important;
}
  </style>

  <div class="modal fade" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="MymodalLabel">Request for Account Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="fnclosepopup1()"> <span aria-hidden="true" style="color:#000 !important;">&times;</span> </button>
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
  <div class="col-lg-12"> 
   @if($delete_request_active==false)
    <a href="javascript:void(0)"  onClick="deleteProfileaccount({{Auth::guard('user')->user()->id}})"  class="default-btn ctr mr-pro">Request for Account Delete</a> 
    @else
    <a href="javascript:void(0)"  class="default-btn ctr mr-pro gray-btn45" style="background:#ccc !important;" >Already a Request for Account Delete Sent to Admin</a>
    @endif
  </div>
</sectiion>
 



@endsection
@section('footer_script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="{{ asset('/css/jquery.passwordRequirements.css')}}">

<link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-tagsinput.css')}}">
<link rel="stylesheet" href="{{ asset('/admin1/css/tagsbootstrap.min.css')}}">
<script src="{{ asset('/admin1/js/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('/admin1/js/tether.min.js')}}"></script>
<script src="{{ asset('/admin1/js/moment.min.js')}}"></script>
<script src="{{ asset('js/jquery.passwordRequirements.js')}}"></script>
<script src="{{ asset('js/jquery.passwordRequirements.min.js')}}"></script>
<script src="{{ asset('/admin1/js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/admin1/css/bootstrap-datetimepicker.min.css')}}">

<script>
        /* trigger when page is ready */
        var $ = jQuery;
                (function($) {
        $(document).ready( function () {
            $(".pr-password").passwordRequirements({});
             $(".timepicker").datetimepicker({
          format: "HH:mm",
          icons: {
            up: "fa fa-chevron-up",
            down: "fa fa-chevron-down"
          }
        });
        });
      })(jQuery);


        
    </script>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>
  function fnclosepopup1(){
    var $ = jQuery;
                (function($) {
        $(document).ready( function () {
         $("#Mymodal").modal('hide');
        });
      })(jQuery);
}

function fnsubmitdelete(){

  $.ajax({
            url: "{{ url('profile_account_delete') }}",
               type: "get",
               data:{ 
                   _token:'{{ csrf_token() }}',
                   user_id: '<?=Auth::guard('user')->user()->id?>',
                   reason:$("#reason_delete").val() ,
               },
               async:true,
               cache: false,
               dataType: 'json',
               success: function(){ 
                
                var $ = jQuery;
                (function($) {
        $(document).ready( function () {
         $("#Mymodal").modal('hide');
        });
      })(jQuery);
                 swal("Request for removing your account successfully placed. Our admin will remove it very soon", "", "success");
                 location.reload();
             } ,
             error: function(XMLHttpRequest, textStatus, errorThrown) { 
               swal(errorThrown, "", "error");
             }  
   
           })  ;

}
function deleteProfileaccount(userid){

  $("#reason_delete").val('');
  swal({
    title:"Do you really want to delete? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      
         
         var $ = jQuery;
                (function($) {
        $(document).ready( function () {
         $('#Mymodal').modal('show');
        });
      })(jQuery);

    } 
    else {
          
        }
    });  
}

  var $ = jQuery;
  (function($) {
  $(document).ready(function() {
         $('.multitags').tagsinput({
            trimValue: true,
            confirmKeys: [13, 44],
            focusClass: 'my-focus-class'
          });     
});
})(jQuery);

</script>

<link rel="stylesheet" href="{{ asset('/css/intlTelInput.css')}}">
<script src="{{ asset('js/intlTelInput.min.js')}}"></script>


<script>

function editfrm(){


 $(".edit_form").css("display", "block");
 $(".view_profile_mobile").hide();
 // $(".edit_form").show();
}



  var input = window.intlTelInput(document.querySelector("#phone"), {
  separateDialCode: true,
  preferredCountries:["in"],
  hiddenInput: "phone",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});

(function(jQuery) {
    jQuery(document).ready( function () {
    jQuery('#register').on( "click", function() {
 var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
$("input[name='phone[full]'").val(full_number);

});
});
})(jQuery);
        </script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        $('#upload_image').click(function(){  
            $('#imgupload').trigger('click'); 
        });


$(".image").change(function(e) { 
var data = new FormData();
data.append('image', $('input[type=file]')[0].files[0]);
data.append('_token', "{{ csrf_token() }}");
$.ajax({
        url:'{{route('update-image')}}',
        type: 'POST',
        data : data,
        enctype : 'multipart/form-data',
        contentType: false,
        processData: false,
        success: function( data ) { 
            var baseUrl = "{{asset('')}}";
            var imageUrl = baseUrl + data.image_path;
            $('.prof_img').attr('src', imageUrl);
          //  $('#changeimage').html('<img src="'+ imageUrl +'" height="120px" width="150px">');
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