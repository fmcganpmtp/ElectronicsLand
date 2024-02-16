@extends('admin.master')
@section('title', 'List Product Request')
@section('breadcrumb') Product Request @endsection
@section('content')
  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Product Request</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                  
                  
                  
                  
                  
                  
                <div class="card-header">
                <div class="row">
<div class="succes-outer">
                   @if(Session::has('product_message')) <div class="alert alert-success">{{Session::get('product_message') }}</div> @endif     
                   @if(Session::has('req_message')) <div class="alert alert-success">{{Session::get('req_message') }}</div> @endif  
                  
                   @if(Session::has('req_failed')) <div class="alert alert-danger">{{Session::get('req_failed') }}</div> @endif  </div>
                     <div class="col-lg-3 col-12">
                      <label> Keyword search:</label>
                      <input type="search" id="search_key"  placeholder="Search...." class="form-control" value={{$search_key}}>
                     
                     </div>
                    
                    <div class="col-lg-2 col-12">
                     <div class="form-group">
                      <label>Category:</label>
                      <select type="text" id="category_id"  class="form-control" >
                                        <option value="">None</option>
                                        @if($prd_req_cat_ids)
                                            @foreach($prd_req_cat_ids as $item)
                                                <?php $dash=''; ?>
                                                <option value="{{$item->id}}" >{{$item->name}}</option>
                                                @if(count($item->subcategory))
                                                @include('admin/products/subCategory',['subcategories' => $item->subcategory])
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    
                                  
                                    
                                    
                    </div>
                     </div>
                    
                    <div class="col-lg-2 col-12">
                     <div class="form-group">
                      <label>Company</label>
                      <input type="text" id="company_name" name="company_name" class="form-control">
                    </div>
                    </div>
                    <div class="col-lg-3 col-12">
                     <div class="autocomplete form-group sg-list" >
                         <label>Country</label>
                      <!--<select type="text" placeholder="Select Country" name="available_countries[]" id='available_countries' class="form-control{{ $errors->has('product_dimension') ? ' is-invalid' : '' }}" multiple="multiple">
                      </select>-->
                      <select name="country_id" id="available_countries" class="form-control " aria-label="Default select example"  >
                    <option value="">Select</option>
                    @foreach ($countries as $country)
                    <option  value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach 
                  </select>
                      <input type="hidden" id="selected_country_id">
            </div>
                    </div>
                   <div class="col-lg-2 col-12">
                      <div class="float-btn top-mrg">
                        <button type="button" id="btnsubmit" value="search" class="bl-btn flt-right"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                        </div>
                    </div>
                    </div>
                  
                  
                  
              </div>
                <div class="card-body">
                  <div class="tableC ad-rl-list">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th></th>
                          <th> Sl No</th>
                          <th>Product Description</th>
                          <th>Company</th>
                          <th>Category</th>
						  <!-- <th>Country</th>
						<!--  <th>Quantity</th>
                          <th>Language </th>-->
                          <th>Img</th>
                          <th>Req Date</th>
                          <th>End date</th> 
                          <th>Action</th> 
                          
                        </tr>
                      </thead>
                    </table>
                  </div>
                  @include('admin.UserButtons')
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
<link rel="stylesheet" href="{{ asset('/css/select2.min.css')}}">
<script src="{{ asset('js/select2.min.js')}}"></script>
<script>


 
var $ = jQuery;
  (function($) {
  $(document).ready( function () {
cur_ids=[];
var straction='';
      var id = '12';
      
      $('#btnsubmit').click(function(){
    dataTable.draw();
    });
    $("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
      
      
	 var viewurl="{{route('view.productrequest', ':id')}}";
	 //var selected_country_id=$("#selected_country_id").val();  
   var dataTable =  $('#datatable').DataTable({
         "processing": true,
        "language": {
        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
        'searching': false,
         "lengthChange": false,
          "order": [ 0,'desc'],
         'ajax': {
       'url':"{{ url('getProductRequest') }}",
       'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
          data.category_id =$("#category_id").val();
          data.country_id =$("#selected_country_id").val();
          data.company_name =$("#company_name").val();
          
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
           return ''+(meta.row + meta.settings._iDisplayStart + 1)+'';
      }
    },
     {
       "targets":3, 
       "orderable": false,
    },
     {
       "targets":4 ,
       "orderable": false,
    },
   /*  {
       "targets":5, 
       "orderable": false,
    },*/
     {
       "targets":5, 
       "orderable": false,
    },
      {
       "targets":6, 
       "orderable": false,
    },
    {
       "targets":8, 
       "orderable": false,
      "render": function(data,type,full,meta)
      { 
       viewurl = viewurl.replace(':id', '');   
       var straction='';
       if(full.expiry_status!='Expired')
       {
       
       straction+="<div class='icon-bx'> <a class='extnd-req' href='JavaScript:void(0);' onclick='ExtendRequest("+full.id+")'>Extend</a> </div>";
       }
       straction+="<a href='JavaScript:void(0);' onclick='deleteProductReq("+full.id+")'><i class='icon cil-trash'></i></a>";
       straction+='<a href="'+viewurl+full.id+'"><i class="fa fa-eye" aria-hidden="true"></i></a>';
       return  straction;
   }
      
      
      
      
    }
   /* {
       "targets":9, 
       "orderable": false,
    },
    {
       "targets":10, 
       "orderable": false,
    },*/
    
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'product_description' }, 
          { data: 'req_by' },
		  { data: 'category_name' },
		//  { data: 'country_name' },
        //  { data: 'quantity' }  ,
        //  { data: 'language' } ,
          { data: 'prod_img' } ,
          { data: 'req_date' }, 
          { data: 'req_end_date' } 
         ]
      });
  });
})(jQuery);



	function ExtendRequest(data){
    var id=data;           
    var extendurl="{{route('AdminExtendRequest', ':id')}}".replace(':id', '')+data;

  swal({
    title:"Do you really want to extend request? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: false,
  })
  .then((willDelete) => {
    if (willDelete) {
      window.location=extendurl;
    } 
    else {
          
        }
    });  
}


function deleteProductReq(data){
    var id=data;           
    var deleteurl="{{route('AdmindeleteProductRequest', ':id')}}".replace(':id', '')+data;

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


     $('#available_countries').on("change", function (e) {
    $("#selected_country_id").val($(this).val());
    });



     
     

   
</script>
<style>
    a.extnd-req {
    background: green;
    color: #fff;
    font-weight: bold;
    padding: 5px;
}
</style>
@endsection