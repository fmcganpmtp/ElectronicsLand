@extends('admin.master')
@section('title', 'Request For Delete')
@section('breadcrumb') Request For Delete @endsection
@section('content')

  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Request For Delete List</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                 <form action="{{route('admin.sellerslist')}}" method="GET">
                  
     
                <div class="row">
                            <div class="col-lg-4 col-12">
                    
                    <div class="search-area wth-btn width_100">
                    
                     <div class="form-group">
                     <input type="search" name="search_key" id="search_key"  placeholder="Search...." class="form-control">
                   </div>
                    
                    </div>
                     </div>
                    
                    <div class="col-lg-2 col-12">
                    
                    
                    
                        	<div class="form-group">
                   
                      
                          <select name="status" id="status" class="form-control">
                                  <option value="">All</option>
                                  <option value="New">New</option>
                                  <option value="Rejected">Rejected</option>
                                  <option value="Deleted">Deleted</option>
                                  
                          </select>
                    </div>
                        </div>
                        <div class="col-lg-6 col-12">
                        <button type="button" class="bl-btn pd01 " id="btnsearch"><i class="fa fa-search" aria-hidden="true"></i> Search</button> 

                        </div>
                    </div>
                </div>
              </form>
                <div class="card-body">
                  <div class="tableC ad-rl-list">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th></th>
                          <th>Sl no</th>
                          <th>Name</th>
                          <th>Company name</th>
                          <th>Email</th>
                          <th> Reason</th>
                          <th>Created at</th>
                          <th>Action</th>
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
var $ = jQuery;
  (function($) {
  $(document).ready( function () {

   
    $("#status").val('New');
    var Rejected="'Rejected'";
    var Deleted="'Deleted'";
    var str='';
   
    var dataTable=$('#datatable').DataTable({
         "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
        'searching': false,
        "lengthChange": false,
        "order": [ 0,'desc'],
        'ajax': {
        'url':"{{ url('getrequestdeletelist') }}",
        'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
          data.status= $("#status").val();
       },
      
    },"columnDefs":[
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
       "targets":7, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        
        
         str='<div class="icon-bx">';
        if(full.reqstatus=='New')
        {
          str+='<button type="button" title="Reject This Request" onclick="fnstatusupdator('+full.id+','+Rejected+')" style="border: 0 none;"><i class="fa fa-ban" aria-hidden="true"></i></button>';
                str+='<button type="button" title="Delete The Profile" onclick="fnstatusupdator('+full.id+','+Deleted+')" style="border: 0 none;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></button>';
        }
         str+='</div>';
         
        return str;
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'name' },
          { data: 'company_name' } ,  
          { data: 'email' } ,
          { data: 'reason' } ,
          { data: 'created_at' } ,
          ]
      });

   $('#btnsearch').click(function(){
    dataTable.draw();
    });
    $("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
     
  });
})(jQuery);



function fnstatusupdator(id,type){
  var msg="";
  if(type=="Rejected")
    msg="Are you Sure you want to Reject this Request?";
  else
    msg="Are you Sure you want to Delete this profile?";

    swal({
        title: msg,
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
            $(".loaderajax").show();
          $.ajax({
         url: "{{ url('adminprofiledelete') }}",
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
                 $(".loaderajax").hide();
              swal(data, "", "success");
              location.reload();
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               $(".loaderajax").hide();
            swal(errorThrown, "", "error");
          }  

        })  ;
        } else {
          
        }
      });  
}

</script>

@endsection