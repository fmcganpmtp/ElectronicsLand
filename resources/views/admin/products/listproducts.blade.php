@extends('admin.master')
@section('title', 'Products List')
@section('breadcrumb') Products @endsection
@section('content')

<div class="body flex-grow-1 px-3">

    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
     
	 <div class="row"><div class="col-lg-8 col-12">
        <h2>Products</h2> </div>
                <div class="col-lg-4 col-12"><a href="{{route('create.product')}}" class="bl-btn flt-right">Create Products</a></div>
				
				</div>
        @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <div class="card-body">
                    
                    </div>
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                
                
                <div class="row">

                     <div class="col-lg-3 col-12">
                      <label> Keyword search:</label>
                      <input type="search" id="search_key"  placeholder="Search...." class="form-control" value={{$search_key}}>
                     
                     </div>
                    
                    <div class="col-lg-4 col-12">
                     <div class="form-group">
                      <label>Category:</label>
                      <select type="text" id="category_id"  class="form-control" >
                                        <option value="">None</option>
                                        @if($categories)
                                            @foreach($categories as $item)
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
                      <label>Stock</label>
                      <select id="stock" class="form-control">
                      <option value="">None</option>
                      <option value="instock" <?php if($stock=='instock') echo "selected";?>>Instock</option>
                      <option value="outstock" <?php if($stock=='outstock') echo "selected";?>>Out of stock</option>
                      </select>
                    </div>
                    </div>
                   <div class="col-lg-3 col-12">
                      <div class="float-btn top-mrg">
                        <button type="button" id="btnsubmit" value="search" class="bl-btn flt-right"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                        <a href="{{route('product.csvfileupload')}}" class="bl-btn flt-right"><i class="fa fa-cloud-upload" aria-hidden="true"></i>Upload csv</a> </div>
                    </div>
                    </div>
  
					</div>
					

                <div class="card-body">
                  <div class="tableC">
                  
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th> Sl No</th>
                          <th>Product Description</th>
                          <th>Company Name</th>
                          <th>Stock Location</th>
                          <th>Price</th>
                          <th>Category</th>
                          <th>Image</th>
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
    var editurl="{{route('edit.product', ':id')}}";
    var deleteurl="";
    var viewurl="{{route('view.product', ':id')}}";

    $('#btnsubmit').click(function(){
    dataTable.draw();
    });
    $("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
   var dataTable = $('#datatable').DataTable({
         "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
         'searching': false,
         "lengthChange": false,
         "order": [ 0,'desc'],
         'ajax': {
          'url':"{{ url('getproductlist') }}",
          'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
          data.category_id =$("#category_id").val();  
          data.stock = $("#stock").val(); 
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
         return data;
      }
    },
     
      {
       "targets":8, 
       "orderable": false,
       "data":"id",
       "render": function(data,type,full,meta)
      {
        editurl = editurl.replace(':id', '');
        viewurl = viewurl.replace(':id', '');
        return '<div class="icon-bx"> <a href="'+viewurl+data+'"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="'+editurl+data+'"><i class="icon  cil-pencil"></i></a> <a href="JavaScript:void(0);" onclick="deleteconfirm('+data+')"><i class="icon cil-trash"></i></a> </div>';     
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'name' },
          { data: 'company_name' } ,  
          { data: 'location' },
          { data: 'product_price' },
          { data: 'strcat' },
          { data: 'strimg' },
           
         ]
      });     
  });
})(jQuery);

function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.product', ':id')}}".replace(':id', '')+data;

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

