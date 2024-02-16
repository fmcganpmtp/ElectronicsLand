@extends('admin.master')
@section('title', 'Guest User View')
@section('breadcrumb') Guest User View @endsection
@section('content')

@if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Guest User View</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">Profile details</div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 col-12">
                      <div class="subscrip-detail-top">
                        <div class="table-prof"><img src="{{$seller->profile_pic==''?asset('/uploads/defaultImages/default_avatar.png'):asset('/uploads/userImages/').'/'.$seller->profile_pic}}"></div>
                        <label><b>Name</b> : <span class="ad-rol-name">{{$seller->name}}</span></label>
                        <label><b>Email</b> : <span class="ad-rol-name">{{$seller->email}}</span></label>
                        <label><b>Phone </b>: <span class="ad-rol-name">{{$seller->phone}}</span></label>
                        <label><b>Address </b>: <span class="ad-rol-name">{{$seller->address}}</span></label>



                      </div>
                                  
                    </div>
                    <div class="col-lg-6 col-12">

                    <div class="subscrip-detail-top subscrip-detail-right ">
                    <h6>Compnay details</h6>

                      
                    <label><b>Street</b> : <span class="ad-rol-name">{{$seller->BuyerCompany->company_street  ?? ""}}</span></label>
                    <label><b>Zip Code </b> : <span class="ad-rol-name">{{$seller->BuyerCompany->company_zip  ?? ""}}</span></label>

                    <label><b>Location </b>: <span class="ad-rol-name">{{$seller->BuyerCompany->company_location  ?? ""}}</span></label>
                    <label><b>Land</b>: <span class="ad-rol-name">{{$seller->BuyerCompany->company_land  ?? ""}}</span></label>
                    
                      <label><b>Opening hours</b> : <table class="table table-bordered table-2">
                          @foreach ($seller->SellerOpeningTime as $Spening)
                               <tr style="line-height: 10px;">
                                    <td>{!! $Spening->day !!}</td>
                                    @if($Spening->closed=='No')
                                    <td style="text-align:center;">{!! $Spening->opening_time !!} {!! $Spening->Open_am_pm !!}</td>
                                    <td style="text-align:center;">{!! $Spening->closing_time  !!}  {!! $Spening->Close_am_pm !!}</td>
                                    @else
                                    <td colspan="2" style="text-align:center;">Closed</td>
                                    @endif
                                </tr>
    
                         @endforeach
</table></label>
                    <label><b>Picture upload</b>: <span class="ad-rol-name up-im"><div class="up-pic"><img src="{{!empty($seller->BuyerCompany) ?($seller->BuyerCompany->company_image?asset('/uploads/BuyerCompany/').'/'.$seller->BuyerCompany->company_image:asset('uploads/defaultImages/seller.jpg')):asset('uploads/defaultImages/seller.jpg')}}"></div></span></label>


                    <label><b> Accepted payments for its products </b> : <span class="ad-rol-name">{{$seller->BuyerCompany->accepted_payments  ?? ""}}</span></label>





                    <label><b>Delivery options </b>: <span class="ad-rol-name">{{$seller->BuyerCompany->deliver_options  ?? ""}}</span></label>
                    <label><b>The languages that he/she speaks for communication purposes</b>: <span class="ad-rol-name">{{$seller->BuyerCompany->languages_speak  ?? ""}}</span></label>
                      
                      
                    </div>
                  </div>
                  </div>
                </div>
              </div>                                   
              <div class="card mar-10">
                <div class="card-header">Kyc Uploads</div>
                <div class="card-header">
                    
                    
                    
                    
                                         
                <div class="row">

<div class="col-lg-6 col-12">
    <div class="search-area width-100-p">
     <div class="form-group">
                      <input id="search_key" type="text" class="form-control" placeholder="Search Docs..">
                        <button id="search_button"><i class="icon cil-search"></i></button>
                     </div></div>
</div>


<div class="col-lg-6 col-12">
                 

  
                    @if($varification_status!='varified')
                     <button id="new_kyc" onclick="showkycmodel({{$id}})" class="bl-btn flt-right">New Kyc Docs</button>
                     
                    @endif
                    </div>



</div><!--row-->


</div><!--card-header-->


              
                     
                    
                    
                    
                    
                    
                    
                    
                    
                    
   
       
                <div class="card-body">   
                <div class="tableC">
                <table id="datatable" data-page-length='20' class="table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Sl No</th>
                          <th>Document name</th>
                          <th>Document type</th>
                          <th>Reason</th>
                          <th> Actions</th>
                        </tr>
                      </thead>
                      
                    </table>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                </div>
              </div>
              <?php 

                   $package_data = DB::table('subscriptions')
                      ->leftJoin('order_details', 'subscriptions.order_id', '=', 'order_details.id')
                      ->leftJoin('packages', 'packages.id', '=', 'order_details.package_id')
                      ->where('subscriptions.user_id', '=',$id)
                      ->where('subscriptions.status','active')
                      ->orderBy('subscriptions.id','DESC')
                      ->first();

                  
                  if(!empty($package_data)){        
                  if( $package_data->subscription_type=='Extended'){?> 

              <div class="card mar-10">
                <div class="card-header">Co-Buyers List</div>
                <div class="card-header">
                  <div class="search-area">
                    
                     <div class="form-group">
                      <input id="search_coseller" type="text" class="form-control" placeholder="Search Co-Buyers..">
                       <button id="search_coseller_button"><i class="icon cil-search"></i></button>
                     </div>
                    
                    </div>
                </div>
                <div class="card-body">   
                <div class="tableC">
                <table id="cosellers" data-page-length='20' class="table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Sl No</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>User Name</th>
                          <th> Actions</th>
                        </tr>
                      </thead>
                      
                    </table>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                </div>
              </div>
              <?php }}?>
              
            
              
        <!-- Modal -->
<div class="modal fade" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MymodalLabel">Enter reason</h5>
        <button type="button" class="close" onclick="fnclosepopup1()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
     <div class="form-group">
      <input type="hidden" id="hdnkycid">
  <textarea class="form-control" placeholder="" id="txtreason"> </textarea>                    </div>
  
  <button type="submit" onclick="rejectdocs()" class="bl-btn">SUBMIT</button>
        
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>  


<!-- Modal -->
<div class="modal fade" id="kycMymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Kyc Docs</h5>
        <button type="button" class="close" onclick="fnclosepopup11()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method="POST" action="{{route('userkycupload')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body ch-dt">
                  <div class="form-group">
                      <input type="hidden" name="hdnuserid" id="hdnuserid">
                      <input type="hidden" name="frompage" value="buyerside">
                      <label>Doc Type:</label>
                       <select name="file_type" class="form-control" required>
               
                      <option value="">Select Type</option>               
                      <option value="Chamber of commerce">  Chamber of commerce</option>
                      <option value="VAT number">VAT number </option>
                      <option value="Identification">Identification (Driving license/ passport/ ID) </option>
                    </select>
                    </div>   

                    <div class="form-group">
                      
                      <label>File:</label>
                       <input type="file" name="file_docupload" class="form-control" required>
                    </div>  
                    
                    <button type="submit"  class="bl-btn">Upload</button>
                      </div>
        </form>
    </div>
  </div>
</div>   

              
              
              
              <!-- <div class="card mar-10">
                <div class="card-header">Products list</div>
                
                <div class="card-header">
                	<div class="search-area wth-btn">
                    
                     <div class="form-group">
                       <input id="product_search_key" type="text" class="form-control" placeholder="Search..">
                      <button id="product_search_button"><i class="icon cil-search"></i></button>
                    </div>
                    
                    </div>
                </div>

                <div class="card-body">
                  
                  <div class="tableC">
                    <table id="productTable" data-page-length='20' class="table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Hidden</th>
                         <th>Sl no</th>
                          <th>Product name</th>
                           <th>Image</th>
                          <th> Actions</th>
                        </tr>
                      </thead>
                 
                    </table>
                  </div>

                </div>
              </div> -->
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{asset('/admin1/js/datatable.js')}}"></script>
  <script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>
var $ = jQuery;
  (function($) {
  $(document).ready( function () {
    var user_id='<?php echo $id?>';
    
     var id = '12';
      var Active='"Active"';
     var Rejected="'Rejected'";
     var  Blocked='"Blocked"';
   var str="";
    var viewurl="{{route('seller.view.product', ':id')}}";
    var editurl="{{route('seller.edit.product', ':id')}}";
    var deleteurl="{{route('seller.delete.product', ':id')}}";
    $("#search_key,#search_button").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
    $("#search_button").click(function (event) { 
        event.preventDefault();
         dataTable.draw();
    });
    $("#product_search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         productTable.draw();
     }
    });


    $("#search_coseller").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         cosellersTable.draw();
     }
    });

    $("#search_coseller_button").click(function (event) { 
         event.preventDefault();
         cosellersTable.draw();
     });

    $("#product_search_button").click(function (event) { 
          event.preventDefault();
         productTable.draw();
     });
    
    var dataTable=$('#datatable').DataTable({
         "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
        'searching': false,
         "lengthChange": false,
          "order": [ 0,'desc'],
         'ajax': {
       'url':"{{ url('getsellerdocslist') }}",
       'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
          data.user_id= user_id;
       },
      
    }, 
   
    "columnDefs":[
    {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      {
       "targets":1, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        return meta.row + meta.settings._iDisplayStart + 1;

      }
    },
     
      {
       "targets":5, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        
       str='<div class="icon-bx">';
       if(full.status1!='Rejected')
       {
        if(full.status1!='Active')
        str+="<button type='button' title='Approve Docs' onclick='fnapprove("+full.id+")' style='border: 0 none;'><i class='fa fa-check-square' aria-hidden='true'></i></button> <button type='button' title='Reject Docs' onclick='fnopenpopup1("+full.id+")' style='border: 0 none;'><i class='fa fa-window-close-o' aria-hidden='true'></i></button>";
        if(full.status1!='In-Active')
          str+=" <button type='button' title='Reject Docs' onclick='fnopenpopup1("+full.id+")' style='border: 0 none;'><i class='fa fa-window-close-o' aria-hidden='true'></i></button>";
        }
         str+='</div>';
         
        return str;
      
   }
    }
  ],
         columns: [
         { data: 'id' },
          { data: 'id' },
          { data: 'file_type' },
          { data: 'file_path' } ,  
          { data: 'reason' } ,  
         ]
      });



    var cosellersTable=$('#cosellers').DataTable({
         "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
        'searching': false,
         "lengthChange": false,
          "order": [ 0,'desc'],
         'ajax': {
       'url':"{{ url('getcosellerslist') }}",
       'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_coseller").val();
          data.user_id={{$id}};
       },
      
    }, 
   
    "columnDefs":[
    {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      {
       "targets":1, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        return meta.row + meta.settings._iDisplayStart + 1;

      }
    },
     
      {
       "targets":6, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        
       str='<div class="icon-bx">';

        
        if(full.status=='Pending')
        str+="<button type='button' title='Activate This Profile' onclick='fnprofileaction("+full.id+","+Active+")' style='border: 0 none;'><i class='fa fa-check-square' aria-hidden='true'></i></button> <button type='button' title='Block This Profile' onclick='fnprofileaction("+full.id+","+Blocked+")' style='border: 0 none;'><i class='fa fa-window-close-o' aria-hidden='true'></i></button>";
        if(full.status=='Blocked')
          str+=" <button type='button' title='Activate This Profile' onclick='fnprofileaction("+full.id+","+Active+")' style='border: 0 none;'><i class='fa fa-check-square' aria-hidden='true'></i></button>";

        if(full.status=='Active')
          str+=" <button type='button' title='Block This Profile' onclick='fnprofileaction("+full.id+","+Blocked+")' style='border: 0 none;'><i class='fa fa-window-close-o' aria-hidden='true'></i></button>";
       
         str+='<a href="JavaScript:void(0);" onclick="fnprofileaction('+full.id+',' + Rejected+')"><i class="icon cil-trash"></i></a></div>';
         
        return str;
      
   } 
    }
  ],
         columns: [
         { data: 'id' },
         { data: 'id' },
          { data: 'name' },
          { data: 'email' },
          { data: 'phone' } ,  
          { data: 'username' } , 
          { data: 'status' } ,  
         ]
      });



     var productTable=$('#productTable').DataTable({
        "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
        'searching': false,
        "lengthChange": false,
        "order": [ 0,'desc'],
        'ajax': {
        'url':"{{ url('getsellerproductslist') }}",
        'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#product_search_key").val();
          data.user_id= user_id;
       },
      
    }, 
   
    "columnDefs":[
    {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      {
       "targets":1, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        return meta.row + meta.settings._iDisplayStart + 1;

      }
    },
    {
       "targets":3, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        return data;

      }
    },
     
      {
       "targets":4, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
       
       var str='"Are you Sure?"';
        viewurl = viewurl.replace(':id', '');
        editurl = editurl.replace(':id', '');
        deleteurl = deleteurl.replace(':id', '');
       return '<div class="icon-bx"><a href="'+viewurl+full.id+'"><i class="fa fa-eye" aria-hidden="true"></i></a><a href="'+editurl+full.id+'"><i class="icon  cil-pencil"></i></a> <a href="'+deleteurl+full.id+'" onclick="return confirm('+str+')"><i class="icon cil-trash"></i></a> </div>';
   }
    }
  ],
         columns: [
         { data: 'id' },
          { data: 'id' },
          { data: 'name' },
          { data: 'file_path' } ,  
         ]
      });


  
     
  });
})(jQuery);

function fnviewdocs(path){
   window.open(path, "_blank");
}
function fnclosepopup11(){
    $("#kycMymodal").modal('hide');
}


function showkycmodel(user_id){
   $('#kycMymodal').modal('show');
   $("#hdnuserid").val(user_id);

}
function fnprofileaction(id,action){

  if(action=='Active')
      var msg="Are you Sure you want to Activate this Profile?";
  else if(action=='Blocked')
      var msg="Are you Sure you want to Block this Profile?";
  else if(action=='Rejected')
      var msg="Are you Sure you want to Delete this Profile?"
  swal({
        title:msg ,
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
        $.ajax({
         url: "{{ url('adminusersellersstatusupdates') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status:action
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              
                swal('Successfully Updated'); 
                location.reload(); 
             
          }  
        })  ;

      } else {
          
        }
      });  

}
function fnapprove(id){
  swal({
        title: "Are you Sure you want to Approve this Docs?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
   
        $.ajax({
         url: "{{ url('approveuserdocs') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              
             if(data==1)
              {
                swal('Successfully Updated'); 
                location.reload(); 
              }
             else 
             swal('Updation Failed');
          }  
        })  ;

      } else {
          
        }
      });  
}


 function rejectdocs(id){

 

        $.ajax({
         url: "{{ url('rejectdocs') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  reason: $("#txtreason").val(),
                  id: $("#hdnkycid").val(),
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              
             if(data==1)
              {
                swal('Status Updated');
                $("#txtreason").val('');
                $("#Mymodal").modal('hide');
                location.reload(); 
              }
             else 
             swal('Updation Failed');
          }  
        })  ;
      
    }




function fnopenpopup1(id){
  swal({
        title: "Are you Sure you want to Rejects this Docs?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
    $('#Mymodal').modal('show');
    $("#hdnkycid").val(id);
  } else {
          
        }
      });  
}
function fnclosepopup1(){
    $("#Mymodal").modal('hide');
}

    </script>
@endsection