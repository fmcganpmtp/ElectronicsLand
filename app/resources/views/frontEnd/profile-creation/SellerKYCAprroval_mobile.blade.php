@extends('layouts.template_mobile')
@section('title', 'KYC Approval')
@section('content')
<sectiion class="seller-page no-bg white-bg bottom-no-padding kyc-page">

  <div class="container">
    <div class="row"> </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">
        
        
        <div class="card vrfy">
            <h3>Email Verification</h3>
            @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
            <div class="row">
                
                
                
                
                <div class="col-lg-12 col-12">
                 @if($email_status=="No")
                <p>Please verify your email address and company details. If any details are incorrect, we'll update them. Please note that failure to complete verification within 14 business days may result in your account being temporarily blocked.</p>
                 @else
                     <img  src="{{ asset('uploads/defaultImages/Email-verified.png')}}" style="max-width:35%"/>
               	 @endif  
                </div>
                
                @if($email_status=="No")
                <div class="col-lg-12">
                 <form method="post" action="{{route('send.kyc.mail')}}" > @csrf
                 <button type="submit" class="btn-right "><i class="fa fa-check-circle-o" aria-hidden="true"></i>Verify email address</button>
                 </form>
                </div>
				@endif
            </div>
          </div>
        
        
          <div class="card">
            <h3>KYC approval</h3>
		<form method="post" action="{{route('update.kyc.doc')}}" enctype='multipart/form-data' >
			@csrf
            <div class="row">
                
                <div class="col-lg-4 col-12">
                  <div class="form-group">
                    <label>File type</label>
                    <select name="file_type" class="list-menu" id = "myselect">
               
                                     	
                      <option value="Chamber of commerce">  Chamber of commerce</option>
                      <option value="VAT number">VAT number </option>
                      <option value="Identification">Identification (Driving license/ passport/ ID) </option>
                    </select>
                  </div>
                </div>
                
                
                <div class="col-lg-4 col-12">
                  <div class="form-group">
                    <div class="file-drop-area form-group">
                      <label >Upload file</label>
                      <input  type="file" id="file_up" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" accept=".jfif,.jpg,.jpeg,.png,.gif,.pdf,.doc,.xls,.docx" name="image" required>
                    @if ($errors->has('image'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                      <img id="loading-image" src="{{ asset('images/ajax-loder.gif')}}" style="display:none;width:80px;"/>
                    </div>
                  </div>
                </div>
				
				<div class="col-lg-4 col-12">
                  <div class="form-group">
                      
                    <button type="submit" class="img_upload">submit</button>
                  </div>
                </div>
				
                <div id="changeimage" style="color:green;"></div>
            </div>
			</form>
          </div>
          <?php 
$chamber_img = $identification_img = $vat_img = asset('uploads/defaultImages/no-image-icon.png');
$kycs = Auth::guard('user')->user()->KycFile;

?>
          
          <div class="card kyc-Up">
            <h3>Uploaded files</h3>
		
            
			
      <div class="row">
			<?php
      $cls_appr = $cls_fa = $tool_no ="";
      foreach($kycs as $kyc) {  
			
		 
                   	$reason = "Not Verified";
                   	if($kyc->status=="Rejected")
                   	$reason = "Rejected";
                   	if(!empty($kyc->reason))
                   	$reason = $kyc->reason;
                   	?>
			
              @if($kyc->file_type == "Chamber of commerce") 
<?php
            $status = $kyc->status;
			if($status=="Active") { 
			$cls_fa = "fa fa-check-square-o";
			$cls_appr = "";
			
			
			
			} else { $cls_fa = "fa fa-circle-o ";
			$cls_appr = "no-appr";
			
			
			}
$file_path_chamber = $kyc->file_path;
$file_path_chamber_ext = pathinfo($file_path_chamber, PATHINFO_EXTENSION); 
if($file_path_chamber_ext == "pdf") $chamber_img = asset('/images/ic-pdf.jpg');
elseif($file_path_chamber_ext == "doc") $chamber_img = asset('/images/ic-doc.jpg');
elseif($file_path_chamber_ext == "xls") $chamber_img = asset('/images/xls.jpg');
elseif($file_path_chamber_ext == "docx") $chamber_img = asset('/images/ic-doc.jpg');
else $chamber_img = asset('/uploads/KYCFiles/').'/'.$file_path_chamber;

?>
              <div class="col-lg-3 col-6">
             	<div class="file-type-bx">
                	<div class="fil-ic"><a target="_blank" class="chamber_a" href="<?=asset('/uploads/KYCFiles/').'/'.$file_path_vat = $kyc->file_path;?>"><img class="chamber" src="{{ $chamber_img }}"></a>
                    <div class="appr <?=$cls_appr?> chamber_appr"><i class="<?=$cls_fa?> fa_chamber" aria-hidden="true"></i></div>
                  </div>
                   <h5> Chamber of commerce</h5>
                   @if($status != "Active") 
                   	
                     <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title=" {{ $reason }}" class="kyc-tool-tip"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                
					 
   			       @endif
              </div>
              </div>
			  @endif
@if($kyc->file_type == "VAT number" ) 
<?php 
            $status = $kyc->status;
			if($status=="Active") { 
			$cls_fa = "fa fa-check-square-o";
			$cls_appr = "";
			$tool_no = "tool-tip-display";
			} else { $cls_fa = "fa fa-circle-o ";
			$cls_appr = "no-appr";
			
			}
$file_path_vat = $kyc->file_path;  
$file_path_vat_ext = pathinfo($file_path_vat, PATHINFO_EXTENSION); 
if($file_path_vat_ext == "pdf") $vat_img = asset('/images/ic-pdf.jpg');
elseif($file_path_vat_ext == "doc") $vat_img = asset('/images/ic-doc.jpg');
elseif($file_path_vat_ext == "xls") $vat_img = asset('/images/xls.jpg');
elseif($file_path_vat_ext == "docx") $vat_img = asset('/images/ic-doc.jpg');
else $vat_img = asset('/uploads/KYCFiles/').'/'.$file_path_vat;
?>

              <div class="col-lg-3 col-6">
             	<div class="file-type-bx">
                	<div class="fil-ic"><a target="_blank"  class="vat_a" href="<?=asset('/uploads/KYCFiles/').'/'.$file_path_vat = $kyc->file_path;?>"><img class="vat" src=" {{ $vat_img }}"></a>
                    
                      <div class="appr <?=$cls_appr?> vat_appr"><i class="<?=$cls_fa?>  fa_appr" aria-hidden="true"></i></div>
                    </div>
                         <h5> VAT number </h5>
                       
					   @if($status != "Active") 
                    	
                     <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title=" {{ $reason }}" class="kyc-tool-tip"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                
					 
				       @endif
                </div>
              </div>
@endif
@if($kyc->file_type == "Identification")  
<?php 
          $status = $kyc->status;
			if($status=="Active") { 
			$cls_fa = "fa fa-check-square-o";
			$cls_appr = "";
			
			} else { $cls_fa = "fa fa-circle-o ";
			$cls_appr = "no-appr";
			
			}
$file_path_identification = $kyc->file_path;  
$file_path_identification_ext = pathinfo($file_path_identification, PATHINFO_EXTENSION); 
if($file_path_identification_ext == "pdf") $identification_img = asset('/images/ic-pdf.jpg');
elseif($file_path_identification_ext == "doc") $identification_img = asset('/images/ic-doc.jpg');
elseif($file_path_identification_ext == "docx") $identification_img = asset('/images/ic-doc.jpg');
elseif($file_path_identification_ext == "xls") $identification_img = asset('/images/xls.jpg');
else $identification_img = asset('/uploads/KYCFiles/').'/'.$file_path_identification;
?>	
              <div class="col-lg-3 col-6">
             	<div class="file-type-bx">
                	<div class="fil-ic"><a target="_blank"  class="identification_a" href="<?=asset('/uploads/KYCFiles/').'/'.$file_path_vat = $kyc->file_path;?>"><img class="identification" src="{{$identification_img}}"></a>
                    <div class="appr <?=$cls_appr?> ident_appr"><i class="<?=$cls_fa?>  fa_ident" aria-hidden="true"></i></div>
                    </div>
                    <h5>Identification (Driving license/ passport/ ID)</h5>
					
					@if($status != "Active") 
						
                     <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title=" {{ $reason }}" class="kyc-tool-tip"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                
					 
					 @endif

                </div>
              </div>
 @endif             
              
              
              
              
              
             <?php } ?>


@if( ($kycs->where('file_type',"VAT number")->count()==0))


              <div class="col-lg-3 col-12">
             	<div class="file-type-bx">
                	<div class="fil-ic"><a   class="vat_a" href="javascript:void(0)"><img class="vat" src=" {{ $vat_img }}"></a>
                    
                      <div class="appr no-appr"><i class="fa fa-circle-o" aria-hidden="true"></i></div>
                    </div>
                         <h5> VAT number </h5>
                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Document is not uploaded" class="kyc-tool-tip"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                </div>
              </div>
@endif

@if( ($kycs->where('file_type',"Identification")->count()==0)	)

              <div class="col-lg-3 col-12">
             	<div class="file-type-bx">
                	<div class="fil-ic"><a   class="identification_a" href="javascript:void(0)"><img class="identification" src="{{$identification_img}}"></a>
                    <div class="appr no-appr"><i class="fa fa-circle-o" aria-hidden="true"></i></div>
                    </div>
                    <h5>Identification (Driving license/ passport/ ID)</h5>
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Document is not uploaded" class="kyc-tool-tip "><i class="fa fa-exclamation-circle" aria-hidden="true"></i></a>


                </div>
              </div>
 @endif   



	@if( ($kycs->where('file_type',"Chamber of commerce")->count()==0)	)

              <div class="col-lg-3 col-12">
             	<div class="file-type-bx">
                	<div class="fil-ic"><a  class="chamber_a" href="javascript:void(0)"><img class="chamber" src="{{ $chamber_img }}"></a>
                    <div class="appr no-appr"><i class="fa fa-circle-o" aria-hidden="true"></i></div>
                  </div>
                   <h5> Chamber of commerce</h5>
                   <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Document is not uploaded" class="kyc-tool-tip <?=$tool_no?> tool_chamber"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></a>
                
              </div>
              </div>
			  @endif


			 
              <div class="col-lg-12 col-12 full-width">
             	<div class="rejectionC">
                <h4>reason for rejection</h4>
                @foreach ($kycs as $kyc)
                 @if($kyc->reason!="")
                	<div class="rj-bx">
                  <h5> {{ $kyc->file_type }}</h5>
                  <p>{{ $kyc->reason }}</p>
                    </div>
                    @endif
                   @endforeach
                    
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
</sectiion>
<script type="text/javascript">
//var $fmcg = $.noConflict();
    $fmcg( document ).ready(function() { 
$fmcg('.img_upload').click(function(){ 
 if(!$fmcg('#file_up').val()){		
		$fmcg('#file_up').attr('required', 'required');
		 $fmcg("#loading-image").hide();
 } else { $fmcg("#loading-image").show();

       
   } 

        });





});
    </script>



@endsection
