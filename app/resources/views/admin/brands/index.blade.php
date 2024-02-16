@extends('admin.master')
@section('title', 'Brands')
@section('breadcrumb') Brands @endsection
@section('content')
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Brands</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">

                                <div class="row">
                  <div class="col-lg-6 col-12">
                   <input type="search" id="search_key"  placeholder="Search...." class="form-control">

                  </div>

                   <div class="col-lg-2 col-12">
                   
                   <button type="button" class="bl-btn pd01 " id="btnsearch"><i class="fa fa-search" aria-hidden="true"></i> Search</button> 

                  </div>

                    <div class="col-lg-4 col-12">
                  
                   <a href="{{ route('brands.create')}}" class="bl-btn flt-right">Create Brands</a>

                  </div>
                </div>
                	
                </div>
                <div class="card-body">
                  <div class="tableC">
				  
                
                    <table class="table table-bordered" id="datatable">
                      <thead>
                        <tr>
                          <th></th>
                         <th>Sl no</th>
                           <th>Image</th>
                          <th> Name</th>
                          <th> Actions</th>
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
    var editurl="{{route('brands.edit', ':id')}}";


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
          'url':"{{ url('getproductBrandlist') }}",
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
      },
      {
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
       "targets":4, 
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
          { data: 'image' },
          { data: 'name' } ,  
         ]
      });

    
     
  });
})(jQuery);

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('brands.destroy', ':id')}}".replace(':id', '')+data;

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