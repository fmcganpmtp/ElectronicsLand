@extends('layouts.template_mobile')
<?php  $usertype = Auth::guard('user')->user()->usertype;  ?>
@if($usertype=='seller')
@section('title', 'List Co-Sellers')
@else
@section('title', 'List Co-Buyers')
@endif
@section('content')

<sectiion class="seller-page1 seller-page-2 cc-sel bg_white inner-page-C ">
  
  



<!-- Modal -->
<div class="modal fade" id="exampleModa5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
        <?php   echo "Create co-user profile"; ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ">
      
      
      
<div class="card seller-page co-seller-pop">
       
            <div class="row">
             
              <div class="col-lg-3 col-12">
                <div class="pr-logo"><img src="{{asset('/uploads/defaultImages/images_not_avaailable.png')}}" class="prof_img" id="prof_img" > <a href="javascript: void(0)" id="upload_image" class="edit"> <i class="fa fa-camera" aria-hidden="true"></i></a> </div>

              </div>
              
              <div class="col-lg-9 col-12"> 
               <form id="divcosellers"> 
               <div class="row">
                   <input type="file" id="imgupload" class="image" name="imgupload" accept=".jfif,.jpg,.jpeg,.png,.gif" style="display:none"     onchange="loadFile(event)"/> 
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label> Name</label>
                      <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required value="{{ old('name') }}" >
                      <span id="txtname"></span>
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required value="{{ old('email') }}">
                      <span id="txtemail"></span>
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Phone</label>
                      <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="full"  required maxlength="15" value="{{ old('phone') }}">

                        <span id="txtphone"></span>
                     
                    </div>
                  </div>
                  <!--<div class="col-lg-4 col-12">-->
                  <!--  <div class="form-group">-->
                  <!--    <label>User name</label>-->
                  <!--    <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" required value="{{ old('username') }}">-->
                  <!--    <span id="txtusername"></span>-->
                       
                  <!--  </div>-->
                  <!--</div>-->
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Password</label>
                     
                      <input  autocomplete="off" id="password_c"  type="password" class="pr-password  form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required value="{{ old('password') }}">
                      <span id="txtpassword"></span>
                    </div>
                  </div>

                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label> Confirm Password</label>
                      <input  autocomplete="off" type="password" id="password-confirm" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation"  value="{{ old('password_confirmation') }}">
                       <span id="txtpassword_confirmation"></span>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn-right">submit</button>   
                </form>  
              </div>
             
            </div>
           
          </div>      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

  
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
                              @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif 

        <div class="pr-bottom">
          <div class="card">

             
                    <h3>Manage Co-Users </h3>
           

            <!-- <div class="co-sell-right"><span>Profiles Available :{{$noofacnt}}</span> <span>Profiles Remaining : {{($noofacnt-count($user))}}</span> </div>-->
            <div class="row">
            <div class="col-lg-12">
            <a  class="btn btn-outline-info ad-n-co" data-bs-toggle="modal" data-bs-target="#exampleModa5"><i class="fa fa-plus-square-o" aria-hidden="true"></i>
          
             Add new Co-User
            </a>
            </div></div>
           <?php  

            
           //if(($view_composer_co_users_criteria == true) && $view_composer_co_users_criteria['no_of_package']>count($user) && Auth::guard('user')->user()->seller_type=='Master'){?> 
            
          <?php //}?>
            <div class="tableC subcrib-dt co-sell-table  ">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <!--<th>Username</th>-->
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($user as $data)
                  <tr>
                    <td><div class="co-sl st"><img src="{{$data->profile_pic ? asset('uploads/userImages/').'/'.$data->profile_pic : asset('/uploads/defaultImages/images_not_avaailable.png')}}"></div><h5>{{$data->name}}</h5></td>
                    <td>{{$data->email}} </td>
                    <td>{{$data->phone}}</td>
                    <!--<td>{{$data->username}}</td>-->
                    <td>
                    @if(Auth::guard('user')->user()->seller_type=='Master')
                    <div class="float-end">
                    @if($data->status!="Active")  
                    <button type="button" title="Activate This Profile" onclick="fnstatusupdator({{$data->id}},'Active')" class="btn btn-outline-success"> <i class="fa fa-check" aria-hidden="true"></i>Active</button>
                    @endif
                    @if($data->status!="Blocked") 
                    <button type="button" title="Block This Profile" onclick="fnstatusupdator({{$data->id}},'Blocked')" class="btn btn-outline-warning"><i class="fa fa-ban" aria-hidden="true"></i>Block</button>
                    @endif
                    <form action="JavaScript:void(0);" onclick="deleteconfirm({{$data->id}})" method="get" style="display:inline;">
                    <button type="submit" class="btn btn-outline-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>Remove</button>
                    </form></div>
                    @endif
                    </td>
                    
                  </tr>
                  
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</sectiion>
<link rel="stylesheet" href="{{ asset('/css/intlTelInput.css')}}">
<script src="{{ asset('js/intlTelInput.min.js')}}"></script>               
<link rel="stylesheet" href="{{ asset('/css/jquery.passwordRequirements.css')}}">
<script src="{{ asset('js/jquery.passwordRequirements.js')}}"></script>
<script src="{{ asset('js/jquery.passwordRequirements.min.js')}}"></script>
<script>
       $fmcg( "#password_c" ).focus(function() {
                      $fmcg(".pr-password").passwordRequirements({

                      });
           
       }); 


        
    </script>
<script>
  var loadFile = function(event) {
    var output = document.getElementById('prof_img');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
  var input = window.intlTelInput(document.querySelector("#phone"), {
  separateDialCode: true,
  preferredCountries:["nl"],
  hiddenInput: "phone",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});

$fmcg('document').ready(function() {
  var errorcount='<?php echo count($errors);?>';
    if(errorcount>0)
    $fmcg('#exampleModa5').modal('show');

  $fmcg("#divcosellers").on('submit', function(e) {
            //$fmcg(".loaderajax").show();
            e.preventDefault();
            var formData = new FormData($fmcg('#divcosellers')[0]);
            formData.append('_token', "{{ csrf_token() }}"); 

                $fmcg.ajax({
                type: "post",
                url: "{{route('add.addCoSeller')}}",
                data: formData,
                enctype : 'multipart/form-data',
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(data) {
                   // $fmcg(".loaderajax").hide(); 
                    swal(data, "", "success");
                    setTimeout(window.location.reload(true),300);
                },
                error: function (xhr) {
                   //$fmcg(".loaderajax").hide(); 
                    var errors = JSON.parse(xhr.responseText);
                         
                    if(errors.errors.name)
                    $fmcg("#txtname").empty().append("<p style='color:red'>"+errors.errors.name[0]+"</p>");
                    else
                      $fmcg("#txtname").empty();
                    if(errors.errors.email)
                    $fmcg("#txtemail").empty().append("<p style='color:red'>"+errors.errors.email[0]+"</p>");
                    else
                      $fmcg("#txtemail").empty();
                    if(errors.errors.username)
                    $fmcg("#txtusername").empty().append("<p style='color:red'>"+errors.errors.username[0]+"</p>");
                    else
                      $fmcg("#txtusername").empty();
                    if(errors.errors.phone)
                    $fmcg("#txtphone").empty().append("<p style='color:red'>"+errors.errors.phone[0]+"</p>");
                    else
                      $fmcg("#txtphone").empty();
                    if(errors.errors.password)
                    $fmcg("#txtpassword").empty().append("<p style='color:red'>"+errors.errors.password[0]+"</p>");
                    else
                      $fmcg("#txtpassword").empty();
                    if(errors.errors.password_confirmation)
                    $fmcg("#txtpassword_confirmation").empty().append("<p style='color:red'>"+errors.errors.password_confirmation[0]+"</p>");
                    else
                      $fmcg("#txtpassword_confirmation").empty();
                       
                               
                }
            });
          }); 
});
   

(function(jQuery) {
    jQuery(document).ready( function () {

      jQuery('#upload_image').click(function(){  
            jQuery('#imgupload').trigger('click'); 
      });
      
      
jQuery('#register').on( "click", function() {
var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
$fmcg("input[name='phone[full]'").val(full_number);

});
});
})(jQuery);

function fnstatusupdator(id,type){
  var msg="";
  if(type=="Blocked")
    msg="Are you Sure you want to Block this profile?";
  else
    msg="Are you Sure you want to Activate this profile?";

    swal({
        title: msg,
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $fmcg.ajax({
         url: "{{ url('usersellersstatusupdates') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status: type,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              swal(data, "", "success");
              location.reload();
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
            swal(errorThrown, "", "error");
          }  

        })  ;
        } else {
          
        }
      });  

  
}

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete-coceller.user',':id')}}".replace(':id', '')+data;

  swal({
    title:"Do you really want to delete? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location=deleteurl;
    } 
    else {
          
        }
    });  

}
</script>
@endsection

