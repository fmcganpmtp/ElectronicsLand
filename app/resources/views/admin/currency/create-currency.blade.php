@extends('admin.master')
@section('title', 'CreateCurrency')
@section('breadcrumb')Currency Creation @endsection
@section('content')


@if($Currency!="")  
                  <?php 
                    $name=$Currency->name;
                    $shortcode=$Currency->shortcode;
					$symbol=$Currency->symbol;
                    $start_date=date('Y-m-d', strtotime($Currency->start_date));
                    $end_date=date('Y-m-d', strtotime($Currency->end_date));
					$currency_id=$Currency->id;
                    $duty = 'Edit' ;
                  ?>
             @else
                <?php 
                  $name=$shortcode=$symbol="";
                 
                  $start_date="";
                  $end_date="";
                  
                  $duty = 'Creation' ;
                ?>
@endif
 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Currency {{$duty}}</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  
@if (\Session::has('msg'))
    <div class="alert alert-danger">
        {!! \Session::get('msg') !!}
    </div>
@endif
                   @if($Currency!="") 
                <form method="post" action="{{ route('update.currency',$currency_id) }}"  enctype="multipart/form-data">
                   
         @else
                <form method="post" action="{{ route('save.currency') }}"  enctype="multipart/form-data">
         @endif   
                    @csrf
                  <div class="row">
				  <input type="hidden" value="{{$currency_id ?? ''}}" name="currency_id"/>
                  	<div class="col-lg-6 col-12">
                    	 <div class="form-group">
                      <label>Currency : *</label>
                      <input type="text" name="name"  value="{{ old('name',$name) }}"   class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" >
                      @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                       @endif
                    </div>
					<div class="form-group">
                      <label>Short Code :*</label>
                      <input type="text" name="shortcode"  value="{{ old('shortcode',$shortcode) }}"   class="form-control {{ $errors->has('shortcode') ? ' is-invalid' : '' }}" >
                      @if ($errors->has('shortcode'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('shortcode') }}</strong>
                                    </span>
                       @endif
                    </div>
					<div class="form-group">
                      <label>Symbol :*</label>
                      <input type="text" name="symbol"  value="{{ old('symbol',$symbol) }}"   class="form-control {{ $errors->has('symbol') ? ' is-invalid' : '' }}" >
                      @if ($errors->has('symbol'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('symbol') }}</strong>
                                    </span>
                       @endif
                    </div>
                    </div>
                  </div>
                  
                  

                         <button type="submit" class="bl-btn">Submit</button>

</form>




@endsection