@extends('admin.master')
@section('title', 'List Currencies')
@section('breadcrumb') Currencies @endsection
@section('content')
  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Currencies</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-9 col-12">
              <div class="card ">
                <div class="card-header">
                <div class="search-area wth-btn">
                    <div class="form-group">
                     <!-- <input type="email" class="form-control" value="Search..">
                     <button type="submit"><i class="icon cil-search"></i></button> -->
                   </div>
                   </div> 
                   <button type="button" onclick="currencymerge(cur_ids)" class="btn btn-outline-danger">Merge Currencies</button>
                   <a href="{{ route('create.currency')}}" class="bl-btn flt-right">Create Currency</a>
              </div>
                <div class="card-body">
                  <div class="tableC ad-rl-list">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th></th>
                          <th><div class="custom-control custom-checkbox">
                           <input type="checkbox" class="custom-control-input" id="customCheck">
                            <label class="custom-control-label" for="customCheck"> Sl No</label>
                            </div></th>
                            <th>ID</th>
                          <th>Currency</th>
						  <th>Shortcode</th>
						  <th>Symbol</th>
                          <th>Created Date</th>
                          <th> Actions</th>
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
  
  
  <div class="modal fade" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MymodalLabel">Assign Currency</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="fnclosepopup1()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form>
      <div class="modal-body">
      
     <div class="form-group">
          <label>Merge with</label>
          <select class="form-control" id="currency_id" required>
            <option value="">Select</option>
          @if($Currencies)
                      @foreach($Currencies as $currency)
                      <?php 
                      $shortcode = $name = '';
                      if(!empty($currency->name))
                      $name = " (".$currency->name.") ";
                      if(!empty($currency->shortcode))
                      $shortcode = " (".$currency->shortcode.") "; ?>
                          <option value="{{$currency->id}}"><?php echo "(".$currency->id.")&nbsp;".$currency->symbol.$shortcode.$name; ?></option>
                      @endforeach
            @endif
           </select>
          
     </div>
     <br>
          <button id='btn'  type="submit" onclick="fninitupdate()" class="bl-btn mar-10">SUBMIT</button>
     
      </div>
      </form>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div> 
  
  
<script src="{{asset('/admin1/js/datatable.js')}}"></script>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script>

 function fnPushId(id){
  var exists = cur_ids.includes(id)
  if (exists) 
    cur_ids= cur_ids.filter((c) => { return c !== id })
   else
    cur_ids.push(id)
}   
  function currencymerge(){
  if(cur_ids.length>1)
    {
  swal({
    title:"Do you  want to Merge  Currencies?",
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
      selectcurrency();
  
    }); 
  } 
  else
    swal("Please Select At Least Two Items",'','error'); 
}    
var $ = jQuery;
  (function($) {
  $(document).ready( function () {
cur_ids=[];
$("#customCheck").click(function(){
       if($("#customCheck").is(':checked'))
        {  
         cur_ids=[]; 
         $(".clsallcheck").prop('checked',true);
         $(".clsallcheck").each(function() {
            cur_ids.push(parseInt($(this).attr('id')));
          });
       }
      else
        {
          $(".clsallcheck").prop('checked',false);
           cur_ids=[];
        }
    });
      var id = '12';
	  var editurl="{{route('edit.currency', ':id')}}";
    $('#datatable').DataTable({
         "processing": true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
         serverSide: true,
        'searching': false,
         "lengthChange": false,
          "order": [ 0,'desc'],
         'ajax': {
       'url':"{{ url('getCurrenciesvalues') }}",
       'data': function(){
          _token="{{csrf_token()}}";
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
           return '<div class="custom-control custom-checkbox"><input id="'+full.id+'" type="checkbox" class="custom-control-input clsallcheck"  onclick="fnPushId('+full.id+')">'+(meta.row + meta.settings._iDisplayStart + 1)+'</div>';
      }
    },
     {
       "targets":5, 
       "orderable": false,
    },
      {
       "targets":7, 
       "orderable": false,
       "data":"id",
       "render": function(data,type,full,meta)
      {
        editurl = editurl.replace(':id', '');
        return "<div class='icon-bx'> <a href='"+editurl+data+"'><i class='icon  cil-pencil'></i></a>  <a href='JavaScript:void(0);' onclick='deleteconfirm("+data+")'><i class='icon cil-trash'></i></a> </div>";
      }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'id' },
          { data: 'name' }, 
		  { data: 'shortcode' },
		  { data: 'symbol' },
          { data: 'created_at' }    
         ]
      });
  });
})(jQuery);
function deleteconfirm(data){
  var id=data;
  var deleteurl="{{route('delete.currency', ':id')}}".replace(':id', '')+data;
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

function selectcurrency(){
 $('#Mymodal').modal('show');
}


function fnclosepopup1(){
    $("#Mymodal").modal('hide');
}
function fninitupdate(){

var merge_id = $('#currency_id').val(); 
if($("#currency_id").val()!='') {    
        // $(".loaderajax").show();
          $.ajax({
              url: "{{ url('currencymerge') }}",
                  type: "post",
                  data:{ 
                      _token:'{{ csrf_token() }}',
                        cur_ids: cur_ids,
                        merge_id:merge_id,
                        action_type:'products'
                      },
                  async:true,
                  cache: false,
                  dataType: 'json',
                  success: function(data){
                        $(".loaderajax").hide();
                        swal(data,'','success');
                        $("input:checkbox").prop('checked',"");
                        cur_ids=[]; 
                        location.reload();
                } ,
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                     $(".loaderajax").hide();
                  swal("Error: "+errorThrown ,'','error'); 
                }  
              })  ;
              
              
}  
              
     
      
}

</script>
@endsection