@extends('admin.master')
@section('title', 'Subscription Users')
@section('breadcrumb') Subscription Users @endsection
@section('content')

<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Subscription Users List</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                 
                <div class="row">
                    <div class="col-lg-3 col-12">
                      <label> Keyword search:</label>
                      <input type="text" id="search_key" class="form-control" >
                    </div>
                    <div class="col-lg-3 col-12">
                      <label>Company Name:</label>
                      <input type="text" id="company_name" class="form-control" >
                    </div>
                    <div class="col-lg-2 col-12">
                      <div class="form-group">
                        <label>User type:</label>
                        <select id="user_type" class="form-control">
                      <option value="">All</option>
                      <option value="Seller">Seller</option>
                      <option value="Buyer">Buyer</option>
                    </select>
                      </div>
                    </div>
                    <div class="col-lg-2 col-12">
                      <div class="form-group">
                        <label>Packages:</label>
                        <select id="Package" class="form-control">
                      <option value="">All</option>
                      <option value="Extended">Extended</option>
                      <option value="Standard">Standard</option>
                    </select>
                      </div>
                    </div>
                    <div class="col-lg-2 col-12">
                      <button type="button" id="btnsearch" class="bl-btn flt-right top-mrg"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tableC  ">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                         <thead>
                        <tr>
                          <th></th>
                          <th>Sl no</th>
                          <th>Name</th>
                          <th>Company</th>
                          <th> Type</th>
                          <th>Package</th>
                          <th>Subscription date</th>
                          <th>Expiry date</th>
                          <th class="sct">Actions</th>
                        </tr>
                      </thead>
                     
                    </table>
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

                    swal(data,'','success');
                    $("input:checkbox").prop('checked',"");
                    subscription_id=[]; 
                    location.reload();
                     } ,
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                 $(".loaderajax").hide();

                  swal(errorThrown,'','error' ); 
                }  
      
              })  ;
           
           
          }
          else {
            swal(errorThrown,'','error'); 
              }
          });  
         
}

var $ = jQuery;
  var Active="'active'";
  var Pending="'rejected'";
  var Suspend="'suspended'";
(function($) {

    
 $(document).ready( function () {
     
 
     

var id = '12';
var viewurl="{{route('admin.subscriptionusersdetails', ':id')}}";


 $('#btnsearch').click(function(){
    dataTable.draw();
  });
  $("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
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
       'url':"{{ url('getsubscriptionuserslist') }}",
       'data': function(data){
          _token="{{csrf_token()}}";
          data.Package = $("#Package").val(); 
          data.user_type = $("#user_type").val();
          data.search_key = $("#search_key").val();
          data.company_name = $("#company_name").val();
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
       "targets":8, 
       "orderable": false,
       "data":"user_id",
       "render": function(data,type,full,meta)
      {
        viewurl = viewurl.replace(':id', '');
        var acti = '' ; var sus = '';
            if(full.sub_status!='Active')
            { 
                acti = ' <button title="Activate" onclick="fnChangStatus('+full.id+','+Active+')"><i class="fa fa-check-circle-o" aria-hidden="true"></i></button> '; 
               }else if(full.sub_status!='Suspended'){ 
                    sus = ' <button title="Suspend" onclick="fnChangStatus('+full.id+','+Suspend+')"><i class="fa fa-window-close-o" aria-hidden="true"></i></button>'; } 
				
        return '<div class="icon-bx"> <a href="'+viewurl+data+'"><i class="fa fa-eye" aria-hidden="true"></i></a>'+acti+sus+'</div>';
 
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'user_name' },
          { data: 'company_name' },
          { data: 'user_type' } ,  
          { data: 'subscription_type' } ,  
          { data: 'date' },
          { data: 'expairy_date' },
          ]
      });

    
     
  });
})(jQuery);
</script>
@endsection
