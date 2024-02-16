@extends('admin.master')
@section('title', 'Subscription Details')
@section('breadcrumb') Subscription  @endsection
@section('content')
   



 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Subscription Details</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 col-12">
                      <div class="form-group">
                  
                        
                         <label><b>Name</b> : <span class="ad-rol-name">{{$package->name}}</span></label>
                      </div>
                      <div class="form-group">
                        <div class="no-over">
                          <label>Package type :<span class="ad-rol-name">{{$package->subscription_type}}</span></label>
                        </div>
                        
                        
                        
                      </div>

                      @foreach ($package->PackageAccount as $PackageAccount)
                      <label>Account Details</label>
                   <div >
                      <div class="form-group">
                       <p>No. of accounts : <span class="ad-rol-name">{{$PackageAccount->no_of_accounts}}</span></p>
                      </div>
                      
                           <div class="form-group">
                      
                           <p>Cost per account : <span class="ad-rol-name">{{$PackageAccount->cost_per_account}}</span></p>

                      </div>
                      
                              <div class="form-group">
                     <p>Offer price : <span class="ad-rol-name">{{$PackageAccount->per_account_offer_price}}</span></p>

                      </div>
                      </div>
                       
                      @endforeach 
             

                      
                      
                      
                      <div class="form-group three-radio">
                        <div class="no-over">
                          <label>User type :: <span class="ad-rol-name">{{$package->user_type}}</span></label>
                      </div>
                      
                      
                      
                      
                      <div class="form-group">
                       <label><b>Price</b> : <span class="ad-rol-name">{{$package->package_basic_price}}</span></label>
                  
                      </div>
                      <div class="form-group">
                        <label><b>Offer price</b> : <span class="ad-rol-name">{{$package->package_offer_price}}</span></label>
                      </div>
                      <div class="form-group three-radio">
                        <div class="no-over">
                          <label>Validity:: <span class="ad-rol-name">{{$package->package_validity}}</span></label>
                        </div>
                       
                      </div>
                      
                      
                      
                      
                      
                      <div class="form-group add-mr">
                        <label>Key Features Included :</label>
                      @foreach ($package->IncludedFeatures as $includ_feature)
                        @if($includ_feature->feature!="")
                        <p>{{ $includ_feature->feature }}</p>
                       @endif
                        @endforeach 
                      </div>
                      
                      
   <div class="form-group add-mr">
                        <label>Key Features Excluded :</label>
                         <p> @foreach ($package->ExcludedFeatures as $exclud_feature)
                        @if($exclud_feature->feature!="")
                       <p>{{ $exclud_feature->feature }}</p>
                       @endif
                        @endforeach</p>
                      </div>                   
                      
                      
                      
                       <div class="form-group">
                      <label>     Description:</label>
 
 
   <p>{{$package->package_description}}</p>
 
 
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