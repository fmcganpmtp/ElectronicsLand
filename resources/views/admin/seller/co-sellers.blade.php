@extends('layouts.template')
@section('title', 'List Co-Sellers')
@section('content')

<sectiion class="seller-page1 seller-page-2 cc-sel">
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  
  

<!-- Modal -->
<div class="modal fade" id="exampleModa5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create co-seller profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body ">
      
      
      
<div class="card seller-page co-seller-pop">
       
            <div class="row">
             
              <div class="col-lg-3 col-12">
                <div class="pr-logo"><img src="{{asset('/uploads/defaultImages/images_not_avaailable.png')}}"> <a href="javascript: void(0)" id="upload_image" class="edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> </div>

              </div>
              
              <div class="col-lg-9 col-12"> 
               <form action="{{route('add.add_co_seller') }}" method="post" enctype="multipart/form-data"> 
               @csrf           
                <div class="row">
                   <input type="file" id="imgupload" class="image" name="imgupload" accept=".jfif,.jpg,.jpeg,.png,.gif" style="display:none"/> 
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label> Name</label>
                      <input type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required value="{{ old('name') }}" >
                       @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required value="{{ old('email') }}">
                       @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Phone</label>
                      <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="full"  required  value="{{ old('phone') }}">
                       @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>User name</label>
                      <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" required value="{{ old('username') }}">
                       @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required value="{{ old('password') }}">
                       @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                    </div>
                  </div>
                  <div class="col-lg-4 col-12">
                    <div class="form-group">
                      <label> Confirm Password</label>
                      <input type="password" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required value="{{ old('password_confirmation') }}">
                       @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
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
        <div class="pr-bottom">
          <div class="card">
            <h3>Manage Co-sellers</h3>
            
            
            
            <div class="row">
            <div class="col-lg-12">
            <a  class="btn btn-outline-info ad-n-co" data-bs-toggle="modal" data-bs-target="#exampleModa5"><i class="fa fa-plus-square-o" aria-hidden="true"></i>Add new co-seller</a>
            </div></div>
            <div class="tableC subcrib-dt co-sell-table  ">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th>Username</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($user as $data)
                  <tr>
                    <td><div class="co-sl st"><img src="{{$data->profile_pic ? asset('uploads/userImages/').'/'.$data->profile_pic : asset('/uploads/defaultImages/images_not_avaailable.png')}}"></div><h5>{{$data->name}}</h5></td>
                    <td>{{$data->email}} </td>
                    <td>{{$data->phone}}</td>
                    <td>{{$data->username}}</td>
                    <td>
                    
                    <div class="float-end">
                    @if($data->status!="Active")  
                    <button type="button" title="Activate This Profile" onclick="fnstatusupdator({{$data->id}},'Active')" class="btn btn-outline-success"> <i class="fa fa-check" aria-hidden="true"></i>Active</button>
                    @endif
                    @if($data->status!="Blocked") 
                    <button type="button" title="Block This Profile" onclick="fnstatusupdator({{$data->id}},'Blocked')" class="btn btn-outline-warning"><i class="fa fa-ban" aria-hidden="true"></i>Block</button>
                    @endif
                    <form action="{{route('delete-coceller.user',$data->id)}}" onclick=" return confirm('Are you Sure Want to Delete?')" method="get" style="display:inline;">
                    <button type="submit" class="btn btn-outline-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>Remove</button>
                    </form></div>
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

<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/css/intlTelInput.css')}}">
<script src="{{ asset('js/intlTelInput.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  var input = window.intlTelInput(document.querySelector("#phone"), {
  separateDialCode: true,
  preferredCountries:["in"],
  hiddenInput: "phone",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});

$('document').ready(function() {
  var errorcount='<?php echo count($errors);?>';
    if(errorcount>0)
    $('#exampleModa5').modal('show');
});
   

(function(jQuery) {
    jQuery(document).ready( function () {

   


      jQuery('#upload_image').click(function(){  
            jQuery('#imgupload').trigger('click'); 
        });
    jQuery('#register').on( "click", function() {
 var full_number = input.getNumber(intlTelInputUtils.numberFormat.E164);
$("input[name='phone[full]'").val(full_number);

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
          $.ajax({
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
</script>
@endsection

