@extends('layouts.template')
@section('title', 'Pending Product list')
@section('content')



<sectiion class="seller-page no-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">
		
		
		
		

           
      
	  
	  <div class="card  tp-30 wow fadeInUp">
<div class="row">
              <div class="col-lg-12 col-12">
                <h3>Pending Product list
</h3></div>
            <div class="row srch-top">
              <div class="col-lg-4 col-12">
                <input type="search" id="search_key"  placeholder="Search...." class="form-control" >
              </div>
              <div class="col-lg-2 col-12">
                <div class="form-group">
                  <select type="text" id="category_id"  class="form-control" >
                                        <option value="">Filter By Category</option>
                                        @if($categories)
                                            @foreach($categories as $item)
                                                <?php $dash=''; ?>
                                                <option value="{{$item->id}}" >{{$item->name ?? ''}}</option>
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
                  <select id="stock" class="form-control">
                      <option value="">Filter by Status</option>
                      <option value="instock" >Instock</option>
                      <option value="outstock" >Out of stock</option>
                      </select>
                </div>
              </div>
              
              <div class="col-lg-4 col-12">
              <button type="button"  id="btnsearch" class="btn btn-success btn-mrg"><i class="fa fa-search" aria-hidden="true"></i>Search</button>

            
              </div>
              
              <div class="col-lg-3 col-12">
               
              </div>
            </div>
            
          </div>
			
			
			
			
            <div class="tableC">
                    <table  id="datatable" class="table table-striped table-bordered datatable" data-page-length='20' cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th></th>
                    <th>Sl no</th>
                    <th>Category</th>
                    <th>Sub category</th>
                    <th>Product name</th>
                    <th>Image</th>
                    <th>Status</th>
                  </tr>
                </thead>
                
              </table>
            </div>
            
            
          
          </div>
	  
	  
	  
	  
	  
	  
	  
      </div>
    </div>
  </div>
  </div>
</sectiion>

   

@endsection
@section('footer_script')

<script src="{{asset('/js/datatable.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
//var $fmcg = $.noConflict();
 var id = '12';
    var editurl="{{route('edit.sellerProduct', ':id')}}";
    var deleteurl="{{route('delete.Sproduct', ':id')}}";
    var viewurl="{{route('view.Sproduct', ':id')}}";

    $fmcg('#btnsearch').click(function(){
    dataTable.draw();
    });
    $fmcg("#search_key").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         dataTable.draw();
     }
    });
   var dataTable = $fmcg('#datatable').DataTable({
          "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
         'searching': false,
         "lengthChange": false,
         "order": [ 0,'desc'],
         'ajax': {
          'url':"{{ url('getSellerPendingProductlist') }}",
          'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $fmcg("#search_key").val();
          data.category_id =$fmcg("#category_id").val();  
          data.stock = $fmcg("#stock").val(); 
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
       "targets":[2,3,5], 
       "orderable": false,
      
    },
     
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'strcat_parent' },
          { data: 'strcat' } ,  
          { data: 'name' },
          { data: 'strimg' },
          { data: 'status' },
           
         ]
      }); 
</script>


@endsection