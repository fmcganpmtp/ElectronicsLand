@extends('layouts.template')
@section('title', 'List Seller Products') 
@section('content')


<style>
  table.dataTable thead .sorting_asc::after, table.dataTable thead .sorting::after  { position: relative !important;     top: 0px; }
</style>




<sectiion class="seller-page-new no-bg">
   
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  <div class="container">
    <div class="row">
         @if(Session::has('product_message')) <div class="alert alert-success">{{Session::get('product_message') }}</div> @endif  </div> 
  </div>
  <div class="container">
    <div class="row">
        
      <div class="col-lg-12">
        <div class="pr-bottom">
		
		
		
		
		
    
          
<div class="card  tp-30 wow fadeInUp">
<div class="row">
              <div class="col-lg-12 col-12">
                  <?php $no_of_uploads =  $view_composer_profile_menu_visible_criteria['prdts_to_uplod']; ?>
                
                <h3>Active Product list
</h3></div>
            <div class="row srch-top lst-seller">
              <div class="col-lg-3 col-12">
                <input type="search" id="search_key"  placeholder="Search...." class="form-control" >
              </div>
              <div class="col-lg-2 col-12">
                <div class="form-group">
                  <select type="text" id="category_id"  class="form-control" >
                                        <option value="">Filter By Category</option>
                                        @if($categories)
                                            @foreach($categories as $item)
                                                <?php 
                                                 $category_ids = [];
                                                $categorylist=App\Models\Category::where('parent_id',$item->id)->pluck('id')->all();       // all subcategories                   
                                                array_push($category_ids,$item->id);  //parent category to array
                                                foreach ($categorylist as $value){
                                                  if(!in_array($value, $category_ids)) //subctegory id not in $category_id array
                                                    array_push($category_ids,$value); //all category ids as array
                                                  for ($i=0; $i<count($category_ids);$i++){
                                                    $category1=App\Models\Category::where('parent_id',$category_ids[$i])->get();
                                                    foreach ($category1 as  $value2){   
                                                      if(!in_array($value2->id, $category_ids))
                                                        array_push($category_ids,$value2->id);
                                                    }                             
                                                  }                
                                                }
                                                $userId = Auth::guard("user")->user()->id;
                                                if (Auth::guard("user")->user()->seller_type != "Master") 
                                                    $userId = Auth::guard("user")->user()->parent_id;
                                                $productlist=App\Models\SellerProduct::whereIn('category_id',$category_ids)->where('user_id',$userId)->where('status','active')->get();
                                                
                                                if(count($productlist)==0)
                                                  continue;
                                                  ?>
                                                <option value="{{$item->id}}" >{{$item->name ?? ''}}</option>
                                                
                                            @endforeach
                                        @endif
                                    </select>
                </div>
              </div>
              <div class="col-lg-2 col-12">
                <div class="form-group">
                  <select id="stock" class="form-control">
                      <option value="">Filter By Stock</option>
                      <option value="instock" >Instock</option>
                      <option value="outstock" >Out of stock</option>
                      </select>
                </div>
              </div>
              <div class="col-lg-2 col-12">
              <button type="button"  id="btnsearch" class="btn btn-success btn-mrg"><i class="fa fa-search" aria-hidden="true"></i>Search</button>
              
              </div>
                <div class="col-lg-3 col-12">
                 <div class="btn-right">
              
                    <button type="button" onclick="fnbulkdelete('products',seller_products)" class="btn btn-outline-danger">Delete selected</button>
                    @if($no_of_uploads=="Unlimited" || $no_of_uploads>0)
                    <a href="{{ route('add.seller.product')}}" class="btn btn-outline-primary">Add new product</a>
                    @else
                    <a onclick="showerror()" href="JavaScript:void(0);" class="btn btn-outline-primary">Add new product</a>
                    
                    @endif
                    <!--  <a href="seller-Bulk-upload.php" class="btn btn-outline-info">Bulk upload</a> --></div>
              </div>
              
              
              <div class="col-lg-12 col-12 ">
              <div class="err-shw" style="display:none; margin-top:20px;">
                        <div class="alert alert-danger alert-dismissible">
                         
                         <!-- <h6><i class="icon fa fa-ban"></i> Error!</h6>-->
                          
                          Product create limit exceed as per the current package. Would you like to upgrade package? <a href="{{route('package.listing')}}">Upgrade</a>
                               
                      </div>
                    </div>
              
              </div>
              
              
            </div>
            
          </div>
			
			
			
			
            <div class="tableC">
                    <table  id="datatable" class="table table-striped table-bordered datatable jtableNclass" data-page-length='20' cellspacing="0">
                <thead>
                  <tr>
                    <th></th>
                    <th> <div class="custom-control custom-checkbox">
                           <input type="checkbox" class="custom-control-input" id="customCheck1">
                            <label class="custom-control-label" for="customCheck1">Sl no </label>
                            </div></th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Product Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Visibility</th>
                    <th>Time and date</th>
                    <th> Actions</th>
                  </tr>
                </thead>
                
              </table>
            </div>
            
            
          
          </div>
		  
		  
		  
<div class="card wow fadeInUp">
              @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
            <div class="slr-details">
              <div class="row ">
              <div class="col-lg-12 col-12">
                <h3>Bulk upload 
</h3></div>
              
              </div>
			  
			  
              <div class="row dwd-exel">
            	<div class="col-lg-6 col-12">
                
                	<div class="brd">
               <h4 class="h4-title">Download sample Excel</h4>
                
                <ul>
                <li>Easy to use and predefined fields.</li>
                <li>Fill the same with your product details.</li>
                <li>Provide the images as corresponding image links.</li>
                <li>Make sure the format is same as in the sample file.</li>
                <li>Make sure to have no empty rows in between product rows</li>
                <li>Make sure the products are updated in your profile with pending status.</li>
                <li>Wait for the Admin approval for displaying products in the platform.</li>
                <li>Upload the excel file.</li>
             </ul>
             
             <a href="{{ asset('/excel/').'/'.'sample.csv' }}" class="default-btn"> <i class="fa fa-file-excel-o" aria-hidden="true"></i>Download</a>
                </div>
                </div>
                <div class="col-lg-6 col-12 c22">
                
                <div class="fupd">
                <h4 class="h4-title">Upload your products</h4>
                
               
                <form method="post" action="{{ route('import-exl') }}"  enctype="multipart/form-data" >
                  @csrf
                  @if (count($errors) > 0)
                <?php  //dd($errors); ?>
                      <div class="alert alert-danger alert-dismissible">
                         
                          <h6><i class="icon fa fa-ban"></i> Error!</h6>
                          @foreach($errors->all() as $error)
                          @if($error=='Product Limit Exceeds')
                          Product create limit exceed as per the current package. Would you like to upgrade package? <a href="{{route('package.listing')}}">Upgrade</a>
                          @else
                          {{ $error }}
                          @endif
                           <br>
                          @endforeach      
                      </div>
                    
                @endif
  
                @if (Session::has('success') && count($errors) == 0)
                    
                        <div class="alert alert-success alert-dismissible">
                           
                            <h6>{!! Session::get('success') !!}</h6>   
                        </div>
                      
                @endif
                <div class="upload-c">
                <div class="file-drop-area form-group">
                      <input type="file"  name="file" class="file-input form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required >
                       @if($no_of_uploads=="Unlimited" || $no_of_uploads>1)
                              <button class="default-btn"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                       @else
                              <button class="default-btn" onclick="showerror1()"> <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                       @endif

Upload</button>
                    </div>
                
                
                </div>
				</form>
			
				
				
				
				
				
				</div>
             
                </div>
            </div>
            
            
  <div class="col-lg-12 col-12 ">
              <div class="err-shw1" style="display:none; margin-top:20px;">
                        <div class="alert alert-danger alert-dismissible">
                         Product create limit exceed as per the current package. Would you like to upgrade package? <a href="{{route('package.listing')}}">Upgrade</a>
                       </div>
               </div>
  </div>          
            
            
            </div>
          </div>
          
       
          
		  
			  <div class="card  tp-30 wow fadeInUp">
<div class="row">
              <div class="col-lg-12 col-12">
                <h3>Pending Product list
</h3></div>
            <div class="row srch-top">
              <div class="col-lg-4 col-12">
                <input type="search" id="search_key_pend"  placeholder="Search...." class="form-control" >
              </div>
              <div class="col-lg-2 col-12">
                 <div class="form-group">
                      <select type="text" id="category_id_pend"  class="form-control" >
                                        <option value="">Filter By Category</option>
                                        @if($categories)
                                            @foreach($categories as $item)
                                                <?php 
                                                $userId = Auth::guard("user")->user()->id;
                                                if (Auth::guard("user")->user()->seller_type != "Master") 
                                                    $userId = Auth::guard("user")->user()->parent_id;
                                                 $productlist=App\Models\SellerProductTemp::where(DB::raw('UPPER(categories)'), "LIKE",  strtoupper($item->name)."%")->where('user_id',$userId)->get();
                                                if(count($productlist)==0)
                                                  continue;
                                                
                                                ?>
                                                <option value="{{$item->name}}" >{{$item->name}}</option>
                                                
                                            @endforeach
                                        @endif
                                    </select> 
                    </div>
             
              </div>
              <div class="col-lg-2 col-12">
                <div class="form-group">
                  <select id="stock_pend" class="form-control">
                      <option value="">Filter by Stock</option>
                      <option value="instock" >Instock</option>
                      <option value="outstock" >Out of stock</option>
                      </select>
                </div>
              </div>
              
              <div class="col-lg-4 col-12">
              <button type="button"  id="btnsearch_pend" class="btn btn-success btn-mrg"><i class="fa fa-search" aria-hidden="true"></i>Search</button>

              <button type="button" onclick="fnbulkdelete('tempproducts',seller_products1)" class="btn btn-outline-danger">Delete selected</button>
              
              </div>
              
             
            </div>
            
          </div>
			
			
			
			
            <div class="tableC">
                    <table  id="datatable_pend" class="table table-striped table-bordered jtableNclass " data-page-length='20' cellspacing="0">
                <thead>
                  <tr>
                    <th></th>
                    <th><div class="custom-control custom-checkbox">
                           <input type="checkbox" class="custom-control-input" id="customCheck2">
                            <label class="custom-control-label" for="customCheck2"> Sl no</label>
                            </div></th>
                    <th>Category</th>
                    <th>Product Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Status</th>
					<th>Time and date</th>
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
</sectiion>


    

@endsection
@section('footer_script')
<script src="{{asset('/js/datatable.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
 var seller_products=seller_products1=[];
 
    var id = '12';
    var editurl="{{route('edit.sellerProduct', ':id')}}";
    var deleteurl="{{route('delete.Sproduct', ':id')}}";
    var viewurl="{{route('view.Sproduct', ':id')}}";
    
     $fmcg("#customCheck1").click(function(){
      
       if($fmcg("#customCheck1").is(':checked'))
        {  
         seller_products=[]; 
         $fmcg(".clsallcheck").prop('checked',true);
         $fmcg(".clsallcheck").each(function() {
            seller_products.push(parseInt($fmcg(this).attr('id')));
          });
       }
      else
        {
          $fmcg(".clsallcheck").prop('checked',false);
           seller_products=[];
        }
      
    });
    
    $fmcg("#customCheck2").click(function(){
      
       if($fmcg("#customCheck2").is(':checked'))
        {  
         seller_products1=[]; 
         $fmcg(".clsallcheck2").prop('checked',true);
         $fmcg(".clsallcheck2").each(function() {
            seller_products1.push(parseInt($fmcg(this).attr('id')));
          });
       }
      else
        {
          $fmcg(".clsallcheck2").prop('checked',false);
           seller_products1=[];
        }
      
    });

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
          'url':"{{ url('getSellerproductlistFrontEnd') }}",
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
       "targets":6, 
       "orderable": false,
      
    },
      {
       "targets":1, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
          return '<div class="custom-control custom-checkbox"><input id="'+full.id+'" type="checkbox" class="custom-control-input clsallcheck"  onclick="fnPushId('+full.id+')"></div>' +(meta.row + meta.settings._iDisplayStart + 1);
      }
    },
     {
       "targets":7, 
       "render": function(data,type,full,meta)
      {

        if(full.product_visibility=="Yes")
          checked="checked";
        else
          checked="";

        if(full.product_visibility=="No")
          unchecked="checked";
        else
          unchecked="";
         return  '<div class="switch-field"><input type="radio" id="radio_visi-'+full.id+'" name="switch_visi-'+full.id+'" value="Yes" '+checked+' onchange="fnproduct_visbility('+full.id+')" /><label for="radio_visi-'+full.id+'">On</label><input type="radio" id="radio_visi-1'+full.id+'" name="switch_visi-'+full.id+'"  value="No" '+unchecked+' onchange="fnproduct_visbility('+full.id+')"/> <label for="radio_visi-1'+full.id+'">Off</label></div>';
      }
    },
    {
       "targets":9, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        var str="'Do you really want to delete?'";
        editurl = editurl.replace(':id', '');
        deleteurl = deleteurl.replace(':id', '');
        viewurl = viewurl.replace(':id', '');
        return '<div class="icon-bx"> <a href="'+viewurl+full.id+'" class="view-ic"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="'+editurl+full.id+'" class="edit-ic"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a href="'+deleteurl+full.id+'" onclick="return confirm('+str+')" class="dlt-ic"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>';     
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'cat_name' },
          { data: 'cat_name1' } ,  
          { data: 'name' },
          { data: 'product_price',className: "text-right" },
          { data: 'strimg' },
          { data: 'product_visibility' },
          { data: 'created_at' },
          { data: '' },
           
         ]
      });
</script>
<script type="text/javascript">
 
 var id = '12';
    var editurl1="{{route('edit.pending.sellerProduct', ':id')}}";
    var deleteurl1="{{route('delete.PendingSproduct', ':id')}}";
    var viewurl1="{{route('view.pending.Sproduct', ':id')}}";


    $fmcg('#btnsearch_pend').click(function(){
    datatable_pend.draw();
    });
    $fmcg("#search_key_pend").keydown(function (event) { 
     if (event.which == 13) { 
         event.preventDefault();
         datatable_pend.draw();
     }
    });
   var datatable_pend = $fmcg('#datatable_pend').DataTable({
          "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
         'searching': false,
         "lengthChange": false,
         "order": [ 0,'desc'],
         'ajax': {
          'url':"{{ url('getSPendingProductlist') }}",
          'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key_pend = $fmcg("#search_key_pend").val();
          data.category_id_pend =$fmcg("#category_id_pend").val();  
          data.stock_pend = $fmcg("#stock_pend").val(); 
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
          return '<div class="custom-control custom-checkbox"><input id="'+full.id+'" type="checkbox" class="custom-control-input clsallcheck2"  onclick="fnPush2Id('+full.id+')"></div>' +(meta.row + meta.settings._iDisplayStart + 1);
     
      }
    },
    {
       "targets":[5], 
       "orderable": false,
      
    },
	  {
       "targets":8, 
       "orderable": false,
       "render": function(data,type,full,meta)
      {
        var str="'Do you really want to delete?'";
        editurl1 = editurl1.replace(':id', '');
        deleteurl1 = deleteurl1.replace(':id', '');
        viewurl1 = viewurl1.replace(':id', '');
        return '<div class="icon-bx"> <a href="'+viewurl1+full.id+'" class="view-ic"><i class="fa fa-eye" aria-hidden="true"></i></a> <a href="'+editurl1+full.id+'" class="edit-ic"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a href="'+deleteurl1+full.id+'" onclick="return confirm('+str+')" class="dlt-ic"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div>';     
      }
    },
     
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'categories' } ,  
          { data: 'name' },
          { data: 'product_price',className: "text-right" },
          { data: 'strimg' },
          { data: 'status' },
          { data: 'created_at' },
		  { data: '' },
           
         ]
      }); 
 function fnbulkdelete(action_type,products){

   
  if(products.length>0)
    {
  swal({
    title:"Do you  want to Delete Product?",
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
         //$fmcg(".loaderajax").show();
          $fmcg.ajax({
              url: "{{ url('userproductbulkdelete') }}",
                  type: "post",
                  data:{ 
                      _token:'{{ csrf_token() }}',
                        seller_products: products,
                        action_type:action_type
                      },
                  async:true,
                  cache: false,
                  dataType: 'json',
                  success: function(data){
                        //$fmcg(".loaderajax").hide();
                        swal(data, "", "success");
                        $fmcg("input:checkbox").prop('checked',"");
                        seller_products=[]; 
                        seller_products1=[]; 
                        location.reload();
                } ,
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    // $fmcg(".loaderajax").hide();
                    swal("Error On Submission", "", "error");
 
                  
                }  
      
              })  ;
      }
          
    }); 
  } 
  else
    swal("Please Select At Least One Item",'','error'); 
   


}
 function fnPushId(id){
  var exists = seller_products.includes(id)
  if (exists) 
    seller_products= seller_products.filter((c) => { return c !== id })
   else
    seller_products.push(id)
}
 function fnPush2Id(id){
  var exists = seller_products1.includes(id)
  if (exists) 
    seller_products1= seller_products1.filter((c) => { return c !== id })
   else
    seller_products1.push(id)
}
  function fnproduct_visbility(id){

    var status=$fmcg("input[name='switch_visi-"+id+"']:checked").val();
    swal({
    title:"Do you want to continue ? " ,
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      // $fmcg(".loaderajax").show();
      $fmcg.ajax({
         url: "{{url('updateuserproductvisibility')}}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  status: status,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
                // $fmcg(".loaderajax").hide();
              if(data)
                  swal("Product Visibility Changed", "", "success");
              else
                  swal("Error On Submission", "", "error");
                
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
              // $fmcg(".loaderajax").hide();
            swal(errorThrown, "", "error");
          }  

        })
    } 
    else {
        
           var cur_status=$fmcg("input[name='switch_visi-"+id+"']:checked").val();
           if(cur_status=='Yes')
              {
                $fmcg("#radio_visi-"+id).prop('checked',false);
                $fmcg("#radio_visi-1"+id).prop('checked',true);
              }
           else
              {
                $fmcg("#radio_visi-"+id).prop('checked',true);
                $fmcg("#radio_visi-1"+id).prop('checked',false);
              }
          
        }
    });  

  }
function showerror(){
    $fmcg('.err-shw').css('display','block');
}
function showerror1(){
    $fmcg('.err-shw1').css('display','block');
}
</script>
@if(Session::has('message_delete'))
<script type="text/javascript"> 
// swal.fire('Product Deleted');
 swal("Product Deleted",'','success'); 
</script>
@endif


@if(Session::has('message_pending_deleted'))
<script type="text/javascript"> 
 swal("Product Deleted",'','success'); 
</script>
@endif
<style>
    .text-right {
    text-align: right;
}
 

.switch-field {
  display: flex;
  overflow: hidden;
}

.switch-field input {
  position: absolute !important;
  clip: rect(0, 0, 0, 0);
  height: 1px;
  width: 1px;
  border: 0;
  overflow: hidden;
}

.switch-field label {
  background-color: #e4e4e4;
  color: rgba(0, 0, 0, 0.6);
  font-size: 14px;
  line-height: 1;
  text-align: center;
  padding: 8px 16px;
  margin-right: -1px;
  border: 1px solid rgba(0, 0, 0, 0.2);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
  transition: all 0.1s ease-in-out;
}

.switch-field label:hover {
  cursor: pointer;
}

.switch-field input:checked + label {
  background-color: #0b6eb1; color:#fff;
  box-shadow: none;
}
.switch-field label{box-shadow:none;background-color: #fff;}
.switch-field label:first-of-type {
  border-radius: 4px 0 0 4px;
}

.switch-field label:last-of-type {
  border-radius: 0 4px 4px 0;
}

</style>
@endsection