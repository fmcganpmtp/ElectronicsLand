@extends('admin.master')
@section('title', 'Advertisement List')
@section('breadcrumb') Advertisement List @endsection
@section('content')
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
    @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
      <div class="card-out mb-4 inner-form">
        <h2>Advertisement listings</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                <div class="row">

                    <div class="col-lg-4 col-12">
                    <label>Search:</label>
                    <input type="text"  id="search_key" class="form-control" placeholder="Search..">
                   

                    </div>

<div class="col-lg-3 col-12">
<div class="form-group">
 <label>Position:</label> 
                    <select name="position" id="position" class="form-control">
                        <option value="">ALL</option>
                        <option value="Top"> Top</option>
                        <option value="Bottom">Bottom</option>
                        <option value="Right"> Right</option>
                        <option value="Middle">Middle</option>
                      </select>
</div>
</div>


<div class="col-lg-2 col-12">
<button type="button" id="btnsearch" value="search" class="bl-btn flt-right top-mrg"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
</div>
<div class="col-lg-2 col-12">
<a href="{{route('advertisement.create')}}" class="bl-btn flt-right top-mrg"> <i class="fa fa-pencil" aria-hidden="true"></i> Create</a>   
</div>
</div>
              </div>
                <div class="card-body testi-list">
                  <div class="tableC">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                     <thead>
                        <tr>
                          <th></th>
                          <th>Sl no</th>
                          <th>Page</th>
                          <th>Advertisement Title</th>
                          <th>Media</th>
                          <th>Position</th>
                          <th> Start date</th>
                          <th> End date</th>
                          <th> Action</th>
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
	  var editurl="{{route('advertisement.edit', ':id')}}";
    var Blocked="'Blocked'";
    var Active="'Active'";
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
        'searching': false,
        "lengthChange": false,
        "order": [ 0,'desc'],
        'ajax': {
        'url':"{{ url('getadvertisementlist') }}",
        'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
          data.position= $("#position").val();
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
       "targets":8, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        editurl = editurl.replace(':id', '');    
        return '<div class="icon-bx"> <a href="'+editurl+full.adv_id+'"><i class="icon  cil-pencil" aria-hidden="true"></i></a><a href="JavaScript:void(0);" onclick="deleteconfirm('+full.adv_id+')"><i class="icon cil-trash"></i></a></div>';
      }
    }
  ],
         columns: [
          { data: 'adv_id' },
          { data: 'adv_id' },
          { data: 'page' },
          { data: 'title' },
          { data: 'media_type' } ,  
          { data: 'position' } ,
          { data: 'start_date' } ,
          { data: 'end_date' },         
         ]
      });

  
     
  });
})(jQuery);


function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('advertisement.destroy', ':id')}}".replace(':id', '')+data;

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



function fnstatusupdator(id,type){
  if(confirm("Are you Sure?"))  
  { $(".loaderajax").show();
       $.ajax({
         url: "{{ url('sellersstatusupdates') }}",
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
            alert("Error: " + errorThrown,'','error'); 
          }  

        })  ;
    
  }
}

</script>
@endsection