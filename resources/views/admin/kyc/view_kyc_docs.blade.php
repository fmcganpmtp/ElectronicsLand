@extends('admin.master')
@section('title', 'Update KYC')
@section('breadcrumb') Update KYC @endsection
@section('content')



<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Admin Edit User</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-12">
              <div class="card ">
              <div class="card-body">
                                
                        @foreach($data as $kyc)
                        <?php 
                        $file_path = $kyc->file_path; 
                        $file_path_ext = pathinfo($file_path, PATHINFO_EXTENSION); 
                        if($file_path_ext == "pdf") $image_path = asset('/images/ic-pdf.jpg');
                        elseif($file_path_ext == "doc") $image_path = asset('/images/ic-doc.jpg');
                        elseif($file_path_ext == "xls") $image_path = asset('/images/xls.jpg');
                        else $image_path = asset('/uploads/KYCFiles/').'/'.$file_path;
                        ?>
                        <a target="_blank" href="<?=asset('/uploads/KYCFiles/').'/'.$file_path?>"><img src="<?=$image_path?>"></a>
                        <div class="form-group">
                            <label for="name" >{{ __('Reason for Rejection') }} </label>
                            <div >
                                <textarea name="reason">{{ $kyc->reason }}</textarea>
                              </div>
                        </div>
                    
                        @endforeach
                  
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>





@endsection