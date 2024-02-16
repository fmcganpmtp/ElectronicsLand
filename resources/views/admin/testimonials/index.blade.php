@extends('admin.master')
@section('title', 'Testimonials')
@section('breadcrumb') Testimonials @endsection
@section('content')



 
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Testimonials</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                	<div class="search-area wth-btn">
                    
                  <div class="form-group">
                     
                     <input type="search" id="search_key"  placeholder="Search...." class="form-control" >
                     
                   </div>
                    </div>
                    <button type="button" class="bl-btn pd01 " id="btnsearch" style="margin-left: 10px;"><i class="fa fa-search" aria-hidden="true"></i> Search</button> 
                      
                    <a href="{{ route('testimonials.create')}}" class="bl-btn flt-right">Create Testimonials</a>
                    
                    
                </div>
                <div class="card-body">
                  <div class="tableC">
				  
                
                    <table class="table table-bordered" id="datatable">
                      <thead>
                        <tr>
                                <th></th>
                                <th>Sl no</th>
                                <th>Profile pic</th>
                                <th>Name</th>
                                <th>Company Name</th>
                                <th>title</th>
                                <th>Comments</th>
                                <th class="action-icon">Action</th>
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
    var id = '12';
   
    var editurl="{{route('testimonials.edit', ':id')}}";

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
          'url':"{{ url('gettestimoniallist') }}",
          'data': function(data){
              _token="{{csrf_token()}}";
              data.search_key = $("#search_key").val();
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
      },{
       "targets":2, 
       "orderable": false,
       "data":"pic",
       "render": function(data,type,full,meta)
      {
        return '<div class="table-prof"><img style=" width:60px !important;" class="pr_img" src="'+data+'"></div>';
      }
    }
     ,
      {
       "targets":7, 
       "orderable": false,
       "data":"id",
       "render": function(data,type,full,meta)
      {
        editurl = editurl.replace(':id', '');
        return '<div class="icon-bx"><a href="'+editurl+data+'"><i class="icon  cil-pencil"></i></a> <a href="JavaScript:void(0);" onclick="deleteconfirm('+data+')"><i class="icon cil-trash"></i></a> </div>';
      }

    }
  ],
         columns: [
         { data: 'id' },
          { data: 'id' },
          { data: 'profile_pic' },
          { data: 'name' },
          { data: 'company_name' } ,  
          { data: 'title' },
          { data: 'comments' },
        
         
         ]
      });

    
     
  });
})(jQuery);

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('testimonials.destroy', ':id')}}".replace(':id', '')+data;

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