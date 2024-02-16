@extends('admin.master')
@section('title', 'Create Company Type')
@section('breadcrumb') Company Type Creation @endsection
@section('content')


@if($CompanyType!="")  
                  <?php 
                    $company_type=$CompanyType->company_type;
                    
                    $start_date=date('Y-m-d', strtotime($CompanyType->start_date));
                    $end_date=date('Y-m-d', strtotime($CompanyType->end_date));
					$type_id=$CompanyType->id;
                    $duty = 'Edit' ;
                  ?>
             @else
                <?php 
                  $company_type="";
                 
                  $start_date="";
                  $end_date="";
                  
                  $duty = 'Creation' ;
                ?>
@endif
 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Company Type {{$duty}}</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  
                  
                   @if($CompanyType!="") 
                <form method="post" action="{{ route('update.company.type',$type_id) }}"  enctype="multipart/form-data">
                   
         @else
                <form method="post" action="{{ route('save.company.type') }}"  enctype="multipart/form-data">
         @endif   
                    @csrf
                  <div class="row">
				  <input type="hidden" value="{{$type_id ?? ""}}" name="type_id"/>
                  	<div class="col-lg-6 col-12">
                    	 <div class="form-group">
                      <label>Company Type :</label>
                      <input type="text" name="company_type"  value="{{ old('company_type',$company_type) }}"   class="form-control {{ $errors->has('company_type') ? ' is-invalid' : '' }}" >
                      @if ($errors->has('company_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('company_type') }}</strong>
                                    </span>
                       @endif
                    </div>
                    </div>
                  </div>
                  
                  

                         <button type="submit" class="bl-btn">Submit</button>

</form>




@endsection