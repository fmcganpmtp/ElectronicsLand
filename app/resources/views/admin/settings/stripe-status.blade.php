@extends('admin.master')
@section('title', 'Stripe Status')
@section('breadcrumb') Stripe Status @endsection
@section('content')
<div class="body flex-grow-1 px-3">
   <div class="container-lg">
      <div class="card-out mb-4 inner-form">
         <h2>Stripe Status</h2>
         <div class="card-body">
            <div class="row">
               <div class="col-lg-12 col-12">
                  <div class="card ">
                     <div class="card-header"> </div>
                     <div class="card-body">
                        <div class="row">
                           <div class="col-lg-6 col-12">
                              <div class="form-group">
                                 <label>Stripe Status :</label>
                                 <select name="stripe_status" id="stripe_status" class="form-control ">
                                 <option value="Enabled" @if($stripe_status=="Enabled") selected @endif >Enabled</option>
                                 <option value="Disabled" @if($stripe_status=="Disabled") selected @endif >Disabled</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="{{asset('/admin1/js/sweetalert.js')}}"></script>
<script type="text/javascript">
   var $ = jQuery;
   (function($) {
   $(document).ready( function () {
   $('#stripe_status').on('change', function() {
   var status = this.value ;
   $.ajax({
   url: "{{ url('stripe_status') }}",
   type: "get",
   data:{ 
      _token:'{{ csrf_token() }}',
      stripe_status:status ,
   },
   async:true,
   cache: false,
   dataType: 'json',
   success: function(){ 
    swal("Status Updated..", "", "success");
    //location.reload();
   } ,
   error: function(XMLHttpRequest, textStatus, errorThrown) { 
   swal(errorThrown, "", "error");
   }  
   
   })  ;
   
   
   });
   });
   })(jQuery);
</script>
@endsection}