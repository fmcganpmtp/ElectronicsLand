@extends('admin.master')
@section('title', 'Subscription Details')
@section('breadcrumb') Subscription User Details @endsection
@section('content')
<style>
  table tr td:nth-child(7), table tr th:nth-child(7) {
    text-align:center !important;
}
table tr td:nth-child(5) {
    text-align:right !important;
}
  </style>
  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
    
        <h2>Subscription Users Detailed view</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
            
            
            
            <div class="card ">
               <div class="card-header">Profile information</div>
                
                <div class="card-body">
                
                
        
            
            <div class="row">
            	<div class="col-lg-5 col-12">
                    	 <div class="subscrip-detail-top">
                    	     @if(!empty($user->profile_pic))
                         <div class="table-prof"><img src="{{asset('/uploads/userImages/').'/'.$user->profile_pic}}"/></div>
                         @else
                         <div class="" style="width:150px;"><img src="{{asset('uploads/defaultImages/seller.jpg')}}"/></div>
                         @endif
                      <label><b>Name:</b> : <span class="ad-rol-name">{{$user->name ?? ''}}</span></label>
                       <label><b>Email</b> : <span class="ad-rol-name">{{$user->email ?? ''}}</span></label>
                       
                        <label><b>Phone </b>: <span class="ad-rol-name">{{$user->phone ?? ''}}</span></label>
                        <label><b>Account type </b>: <span class="ad-rol-name">{{$user->usertype ?? ''}}</span></label>
                         
                         
                    </div>
                    </div>
                    
                    
                    
                    <div class="col-lg-7 col-12">

                    	 <div class="subscrip-detail-top subscrip-detail-right ">
                    	 <h6>Compnay details</h6>

                         
                      <label><b>Street</b> : <span class="ad-rol-name">{{$user->BuyerCompany->company_street ?? ''}}</span></label>
                       <label><b>Zip Code </b> : <span class="ad-rol-name">{{$user->BuyerCompany->company_zip ?? ''}}</span></label>
                       
                        <label><b>Location </b>: <span class="ad-rol-name">{{$user->BuyerCompany->company_location ?? ''}}</span></label>
                        <label><b>Land</b>: <span class="ad-rol-name">{{$user->BuyerCompany->company_land ?? ''}}</span></label>
                        
                         <label><b>Opening hours</b> : <table class="table table-bordered table-2">
                          @foreach ($user->SellerOpeningTime as $Spening)
        <tr style="line-height: 10px;">
            <td>{!! $Spening->day !!}</td>
            <td>{!! $Spening->opening_time !!}</td>
            <td>{!! $Spening->closing_time	 !!}</td>
        </tr>
    @endforeach
</table></label>

@php
if(!empty($user->BuyerCompany->company_image))
$img_path=asset('/uploads/BuyerCompany/').'/'.$user->BuyerCompany->company_image;
else
$img_path=asset('uploads/defaultImages/default_avatar.png');
@endphp
                        <label><b>Picture upload</b>: <span class="ad-rol-name up-im"><div class="up-pic"><img src="{{$img_path}}"></div></span></label>
                       
                       
                        <label><b> Accepted payments for its products </b> : <span class="ad-rol-name">{{$user->BuyerCompany->accepted_payments ?? ''}}</span></label>
                       
                       
                      
                       
                       
                        <label><b>Delivery options </b>: <span class="ad-rol-name">{{$user->BuyerCompany->deliver_options ?? ''}}</span></label>
                        <label><b>The languages that he/she speaks for communication purposes


</b>: <span class="ad-rol-name">{{$user->BuyerCompany->languages_speak ?? ''}}</span></label>
                         
                         
                    </div>
                    </div>
                    
                    
            </div>
                
                
                
                  
                  
                </div>
              </div>
            
            
              <div class="card pk-dtl">
               <div class="card-header">Package details</div>
               
               <div class="card-header">
                	<div class="search-area">
                    
                     <div class="form-group">
                      <input type="email" id="search_key" class="form-control" placeholder="Search..">
                      <button type="button" id="btnsearch"><i class="icon cil-search"></i></button>
                    </div>
                    
                    </div>
                </div>
                
                <div class="card-body">
                
                
            
                
                
                
                  <div class="tableC  table-scrl">
                      <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Sl No</th>
                          <th>Plan</th>
                          <!--<th>Amount</th> -->
                          <th> Subscriptions date</th>
                          <th> Expiry date</th>
                          <th>Amount paid</th>
                          <!--<th>Payment method</th>
                          <th>Date</th>
                          <th> Price</th>
                          <th>Transaction method</th>
                          <th>Transaction id</th>-->
                       
                          

                        <th>Actions</th>
                        </tr>
                      </thead>
                       
  </table>     
                               
                               


<!-- Modal -->
<div class="modal fade" id="Mymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit subscription expiry date</h5>
        <button type="button" class="close" onclick="fnclosepopup1()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ch-dt">
<div class="form-group">
  <input type="hidden" id="hdnorderid">
                      <label>Change date:</label>
                       <input type="date" class="form-control" id="txtdate">
                    </div>    
                    
                    <button type="button" id="btnchangeexpiry" class="bl-btn">Save</button>
                      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>

                          
                  </div>
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


<script src="{{asset('/admin1/js/datatable.js')}}"></script>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>



function fnChangStatus(subscription_id,status){
      var msg="";
  if(status=='active')
    msg="Do you  want to Activate?";
  else
    msg="Do you  want to suspend?";
  swal({
    title:msg,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $(".loaderajax").show();

      $.ajax({
               url: "{{ url('subscriptionstatuschange') }}",
                  type: "post",
                  data:{ 
                      _token:'{{ csrf_token() }}',
                        subscription_id: subscription_id,
                        status:status
                  },
                  async:true,
                  cache: false,
                  dataType: 'json',
                  success: function(data){
                    $(".loaderajax").hide();
                    swal(data, "", "success");
                    $("input:checkbox").prop('checked',"");
                    subscription_id=[]; 
                    location.reload();
                     } ,
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                 $(".loaderajax").hide();
                  swal(errorThrown,'',"error" ); 
                }  
      
              })  ;
           
           
          }
          else {
            //swal("Error Occured!",'',"error" ); 
              }
          });  
         
}

var $ = jQuery;
  var Active="'active'";
  var Pending="'rejected'";
  var Suspend="'suspended'";

  (function($) {
 $(document).ready( function () {

var user_id='<?php echo $user->id;?>';

 $('#btnsearch').click(function(){
    dataTable.draw();
  });
  $("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });


  $('#btnchangeexpiry').click(function(){
    //txtdate
    if($("#txtdate").val()!='')
    {
      $.ajax({
         url: "{{ url('ajaxextendexpirydate') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  Extend_date: $("#txtdate").val(),
                  order_id: $("#hdnorderid").val(),
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              
             if(data==1)
              {
                swal("Status Updated", "", "success");
                $("#txtdate").val('');
                fnclosepopup1();
                location.reload();    
              }
             else 
               swal("Updation Failed", "", "error");

          }  ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               swal(errorThrown, "", "error");
          } 
        })  ;
      }
  });


    var dataTable=$('#datatable').DataTable({
        "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
          "lengthChange": false,
          'searching': false,
          "order": [ 0,'desc'],
         'ajax': {
       'url':"{{ url('getuserssubscriptionlist') }}",
       'data': function(data){
          _token="{{csrf_token()}}";
          data.user_id = user_id; 
          data.search_key =$("#search_key").val();
       },
      
    }, 
   
    "columnDefs":[
    {
       "targets":0, 
       "orderable": true,
       "visible":false
      },
      
      {
       "targets":[1], 
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
        if(full.last_package==full.id){ var acti = '' ; var sus = '';
            if(full.sub_status!='Active')
            { 
                acti = '<button class="green_sus_outer" title="Activate" onclick="fnChangStatus('+full.subs_id+','+Active+')"><span class="green_sus">Activate</span></button>'; 
               }else if(full.sub_status!='Suspended'){ 
                    sus = '<button  class="red_sus_outer" title="Suspend" onclick="fnChangStatus('+full.subs_id+','+Suspend+')"><span class="red_sus">Suspend</span></button>'; } 
				var send_rem ='<button onclick="sendmessage('+full.subs_id+')"  class="change-dt gray_btn_4" style="border:0 none !important;"><i class="fa fa-paper-plane-o" aria-hidden="true"></i>Send reminder</button>';	
                var edit_exp ='<button onclick="fnchangeexpirydate('+full.subs_id+')"  class="change-dt  gray_btn_4" style="border:0 none !important;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Edit subscription expiry date</button>';
	  return  acti+sus+send_rem+edit_exp;
        }
      else
        return '';
 
      }
    }
  ],
         columns: [
         { data: 'id' },
          { data: 'id' },
          { data: 'package_name' },
         // { data: 'package_basic_price' } ,  
          { data: 'created_date' },
          { data: 'expairy_date' },
        //  { data: 'id' },
         // { data: 'id' },
        //  { data: 'id' },
         // { data: 'id' },
          //{ data: 'id' },
          { data: 'ord_total' },
          { data: 'id' }
         ]
      });

    
     
  });
})(jQuery);

function sendmessage(data){

  var remainderurl="{{route('admin.senderRemainder', ':id')}}".replace(':id', '')+data;

  swal({
    title:"Do you want to Send Message?" ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location=remainderurl;
    } 
    else {
          
        }
    });  
}

function fnchangeexpirydate(id){
  $("#Mymodal").modal('show');
  $("#hdnorderid").val(id);
}
function fnclosepopup1(){
    $("#Mymodal").modal('hide');
}
</script>
@endsection

