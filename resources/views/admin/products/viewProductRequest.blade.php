@extends('admin.master')
@section('title', 'Product Request')
@section('breadcrumb') Product Request  @endsection
@section('content')
   



 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Product Request</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 col-12">
                      <div class="form-group">
                  
                        
                         <label><b>Company :</b> </label><span class="ad-rol-name">{{$cmp_name}}</span>
                         <!--{{$Req->User->name??''}}-->
                      </div>
                      <div class="form-group">
                        <div class="no-over">
                          <label>Product Description :</label><span class="ad-rol-name">{{$Req->product_description}}</span>
                        </div>
                        </div>
                        <div class="form-group">
                        <div class="no-over">
                          <label>Category :</label><span class="ad-rol-name">{{$Req->Category->name??''}}</span>
                        </div>
                        </div>
                        <div class="form-group">
                        <div class="no-over">
                          <label>Which country interested to get the product from?
 :</label><span class="ad-rol-name">{{$countries??''}}</span>
                        </div>
                        </div>
                        
                        
                        <div class="form-group">
                        <div class="no-over">
                          <label>What quantity? :</label><span class="ad-rol-name">{{$Req->quantity??''}}</span>
                        </div>
                        </div>
                        
                        <div class="form-group">
                        <div class="no-over">
                          <label>Product Language :</label><span class="ad-rol-name">{{$Req->language??''}}</span>
                        </div>
                        </div>
                        
                        <div class="form-group">
                        <div class="no-over">
                          <label>Image :</label><span class="ad-rol-name"><?=$prd_img?></span>
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
  






@endsection