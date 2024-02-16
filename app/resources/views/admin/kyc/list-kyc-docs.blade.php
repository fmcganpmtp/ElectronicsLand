@extends('admin.master')
@section('title', 'Kyc List')
@section('breadcrumb') Kyc List @endsection
@section('content')




 
  <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Kyc List</h2>
       
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                <div class="row">

<div class="col-lg-4 col-12">
 <label> Keyword search:</label>
 <input type="search" id="search_key"  placeholder="Search...." class="form-control" value={{$search_key}}>

</div>

<div class="col-lg-3 col-12">
<div class="form-group">
 <label>Status:</label>
 <select id="status" class="form-control">
                      <option value="">None</option>
                      <option value="Active">Active</option>
                      <option value="In-Active">In Active</option>
</select>

</div>
</div>


<div class="col-lg-2 col-12">
<button type="button" id="btnsearch" value="search" class="bl-btn flt-right top-mrg"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
</div>
<div class="col-lg-2 col-12">
<button type="button" onclick="showkycmodel()" class="bl-btn flt-right top-mrg"> Kyc Upload</button>
</div>
</div>


              </div>


<!-- Modal -->
<div class="modal fade" id="kycMymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Kyc Docs</h5>
        <button type="button" class="close" onclick="fnclosepopup11()" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method="POST" action="{{route('userkycupload')}}" enctype="multipart/form-data">
        @csrf
      <div class="modal-body ch-dt kycmodelpopup">

        
         <div class="form-group">
                      <input type="hidden" name="hdnuserid" id="hdnuserid">
                      <input type="hidden" name="frompage" value="kycside">
                      <label>User:</label>

                      <select type="text" placeholder="Select User"  id="dduser_id" class="form-control" required>
                      </select>
                       
                    </div>   
                  <div class="form-group">
                      
                      <label>Doc Type:</label>
                       <select name="file_type" class="form-control" required>
               
                      <option value="">Select Type</option>               
                      <option value="Chamber of commerce">  Chamber of commerce</option>
                      <option value="VAT number">VAT number </option>
                      <option value="Identification">Identification (Driving license/ passport/ ID) </option>
                    </select>
                    </div>   

                    <div class="form-group">
                      
                      <label>File:</label>
                       <input type="file" name="file_docupload" class="form-control" required>
                    </div>  
                    
                    <button type="submit"  class="bl-btn">Upload</button>
                      </div>
        </form>
    </div>
  </div>
</div>  
             
                <div class="card-body">
                  <div class="tableC ad-rl-list">
                  <table id="datatable" class="table  table-bordered" data-page-length='20' cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th></th>
                          <th>Sl No</th>
                          <th>User Name</th>
                          <th>File Type</th>
                          <th> Reason</th>
                          <th> Created At</th> 
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

  <div class="modal fade" id="Mymodal" tabindex="-1" aria-labelledby="MymodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MymodalLabel">Enter reason</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="fnclosepopup1()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
     <div class="form-group">
          <input type="hidden" id="hdnkycid">
          <input type="hidden" id="hdnkyc_user_id">
          <textarea class="form-control" id="txtreason" placeholder=""> </textarea>
     </div>
          <button  type="submit" onclick="rejectdocs()" class="bl-btn mar-10">SUBMIT</button>
        
      </div>
      <div class="modal-footer">
       
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
  
  var route = "{{route('available.users')}}";
  $('#dduser_id').select2({
       dropdownParent: $("#kycMymodal"),
     placeholder: 'Users',

    escapeMarkup: function(markup) { 
          return markup;
    },
    templateResult: function(data) {
       
      return data.html;
    },
    templateSelection: function(data) {
      

      if (data && !data.selected) 
      return data.text;
    },
    ajax: {
      url: route,
      dataType: 'json',
      delay: 250,
      processResults: function(data) {
        return {
          results: $.map(data, function(item) {
            return {
              html:"<span>"+item.name+"</span>",
              text: item.name,
              id: item.id
            }
          })
        };
      },
      cache: true,

    }
  }).on("change", function (e) {
     $("#hdnuserid").val($(this).val());
    });;

    var straction=''; 
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
       'url':"{{ url('getkyclist') }}",
       'data': function(data){
          _token="{{csrf_token()}}";
          data.search_key = $("#search_key").val();
          data.status= $("#status").val();
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
       var full_path='"'+full.file_path+'"';
       var str='"Are you Sure?"';

       straction="<div class='icon-bx'><button onclick='fnviewdocs("+full.id+","+full_path+")'  style='border: 0 none;'><i class='icon  fa fa-eye'></i></button>"; 
       if(full.status!='Rejected')
       {   
       if(full.status!='Active')
       straction+="<button type='button' title='Approve this Docs' onclick='fnapprove("+full.id+","+full.user_id+")' style='border: 0 none;'><i class='fa fa-check-square' aria-hidden='true'></i></button>  <button type='button' title='Reject Docs' onclick='fnopenpopup1("+full.id+","+full.user_id+")' style='border: 0 none;'><i class='fa fa-window-close-o' aria-hidden='true'></i></button>";
       if(full.status!='In-Active')
       straction+="<button type='button' title='Reject this Docs'  onclick='fnopenpopup1("+full.id+","+full.user_id+")' style='border: 0 none;'><i class='fa fa-window-close-o' aria-hidden='true'></i></button>";
       }
       straction+="</div>";
       return  straction;
   }
    }
  ],
         columns: [
          { data: 'id' },
          { data: 'id' },
          { data: 'name' },
          { data: 'file_type' } ,  
          { data: 'reason' } ,
          { data: 'created_at' } ,
         ]
      });

  
     
  });
})(jQuery);

function fnviewdocs(id,name){
  var myArray = name.split(".");
  var type=myArray[myArray.length-1]; 
  var url= "{{asset('/uploads/KYCFiles/')}}"+'/'+name;
  window.open(url, "_blank");
}

function fnclosepopup11(){
    $("#kycMymodal").modal('hide');
}


function showkycmodel(){
   $('#kycMymodal').modal('show');
  }
function fnapprove(id,user_id){


  swal({
        title: "Are you Sure you want to Approve this Docs?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
             $(".loaderajax").show();
          $.ajax({
         url: "{{ url('approveuserdocs') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  id: id,
                  user_id: user_id,
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
              $(".loaderajax").hide(); 
             if(data==1)
              {
                swal('Successfully Updated', "", "success"); 
                location.reload(); 
              }
             else 
               swal('Updation Failed', "", "error");
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


 function rejectdocs(id,user_id){
         $(".loaderajax").show();
          $.ajax({
         url: "{{ url('rejectdocs') }}",
            type: "post",
            data:{ 
                _token:'{{ csrf_token() }}',
                  reason: $("#txtreason").val(),
                  id: $("#hdnkycid").val(),
                  user_id: $("#hdnkyc_user_id").val(),
            },
            async:true,
            cache: false,
            dataType: 'json',
            success: function(data){
               $(".loaderajax").hide();
              if(data==1)
              {
                swal('Successfully Updated', "", "success"); 
                $("#txtreason").val('');
                $("#Mymodal").modal('hide');
                 location.reload(); 
              }
             else 
               swal('Updation Failed', "", "error");
          } ,
          error: function(XMLHttpRequest, textStatus, errorThrown) { 
               $(".loaderajax").hide();
            swal(errorThrown, "", "error");
          }  
        })  ;

       
    };




function fnopenpopup1(id,user_id){
  swal({
        title: "Are you Sure you want to Rejects this Docs?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
    $('#Mymodal').modal('show');
    $("#hdnkycid").val(id);
    $("#hdnkyc_user_id").val(user_id);

  } else {
          
        }
      });  
  
}
function fnclosepopup1(){
    $("#Mymodal").modal('hide');
}
</script>
<style>
    .select2-dropdown{border: 1px solid #aaa!important;}
</style>

@endsection