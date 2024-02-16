@extends('layouts.template')
@section('title', 'Checkout')
@section('content')
<div class="bg-light min-vh-100 d-flex flex-row align-items-center login-scr sub-chk-out pack-bg">
   <div class="container">
      <div class="row">
         <div class="col-lg-12 ">
            <div class="loginC align-middle chk-bx">
               <div class="row">
                  <div class="col-lg-6 col-12 ">
                     <div class="log-left ">
                        <div class="chk-out-left">
                           <h3> {{ $package->name }} </h3>
                           @if(($package->package_offer_price != "") || ($package->package_offer_price>0))
                           <h4><del> ${{$package->package_basic_price ?? ''}}</del><b> ${{$package->package_offer_price ?? ''}}</b> {{$package->package_validity ?? ''}} </h4>
                           @else
                           <h4><b> ${{$package->package_basic_price ?? ''}}</b> {{$package->package_validity ?? ''}} </h4>
                           @endif
                           <div class="shk-left-txt">
                              <p> {{$package->package_description ?? ''}} </p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6 col-12">
                     <div class="log-right">
                        <h2>Pay with your card</h2>
                        <div class="row mr-5">
                           <div class="col-lg-6 col-12">
                              <form method="POST" action="{{ route('checkout.submit') }}" name="stripe-form"  >
                                 <input type="hidden" name="package_id" value="{{ Request::get('pkg_id') }}" >
                                 <input type="hidden" name="accounts_id" value="{{ Request::get('accounts_id') }}" >
                                 <input type="hidden" name="order_type" value="{{ Session::get("order_type") ?? '' }}" 
                                 <input type="hidden" name="old_pkg_id" value="{{ Session::get("old_pkg_id") ?? '' }}" >
                                 @csrf
                                 <div class="form-group">
                                    <label>Name <sup>*</sup></label>
                                    <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" value="{{old('name') ? old('name') : Auth::guard('user')->user()->name ?? ''}}" >
                                    @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                 </div>
                           </div>
                           <div class="col-lg-6 col-12">
                           <div class="form-group">
                           <label>Email <sup>*</sup></label>
                           <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" name="email" value="{{ old('email') ? old('email') : Auth::guard('user')->user()->email ?? '' }}" >
                           @if ($errors->has('email'))
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $errors->first('email') }}</strong>
                           </span>
                           @endif
                           </div>
                           </div>
                           <div class="col-lg-6 col-12">
                           <div class="form-group">
                           <label>Phone <sup>*</sup></label>
                           <input class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" type="text" name="phone" value="{{ old('phone') ? old('phone') : Auth::guard('user')->user()->phone ?? '' }}" >
                           @if ($errors->has('phone'))
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $errors->first('phone') }}</strong>
                           </span>
                           @endif
                           </div>
                           </div>
                           <div class="col-lg-6 col-12">
                           <div class="form-group">
                           <label>Address <sup>*</sup></label>
                           <input class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" type="text" name="address" value="{{ old('address') ? old('address') : Auth::guard('user')->user()->address ?? '' }}" >
                           @if ($errors->has('address'))
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $errors->first('address') }}</strong>
                           </span>
                           @endif
                           </div>
                           </div>
                           <div class="col-lg-6 col-12">
                           <div class="form-group">
                           <label>City <sup>*</sup></label>
                           <input class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" type="text" name="city" value="{{ old('city') ? old('city') : Auth::guard('user')->user()->store_city ?? '' }}" >
                           @if ($errors->has('city'))
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $errors->first('city') }}</strong>
                           </span>
                           @endif
                           </div>
                           </div>
                           <div class="col-lg-6 col-12">
                           <div class="form-group">
                           <label>Zip <sup>*</sup></label>
                           <input class="form-control {{ $errors->has('zip') ? ' is-invalid' : '' }}" type="text" name="zip" value="{{ old('zip') ? old('zip') : Auth::guard('user')->user()->store_zip ?? '' }}" >
                           @if ($errors->has('zip'))
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $errors->first('zip') }}</strong>
                           </span>
                           @endif
                           </div>
                           </div>
                        </div>
                        <!--
                           <div class="form-group">
                             <label>Card information</label>
                             <input class="form-control" name="card_number" value="{{ old('card_number')}}" type="text" placeholder="1111 2222 3333 4444" >
                             <div class="row no-marg">
                                 <div class="col-lg-6 col-12">
                                 <input class="form-control {{ $errors->has('card_month') ? ' is-invalid' : '' }}" value="{{ old('card_month')}}" name="card_month" type="text" placeholder="MM" >
                                @if ($errors->has('card_month'))
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $errors->first('card_month') }}</strong>
                           </span>
                           @endif
                               </div>
                               <div class="col-lg-6 col-12">
                                 <input class="form-control {{ $errors->has('card_year') ? ' is-invalid' : '' }}" value="{{ old('card_year')}}" name="card_year" type="text" placeholder="YYYY" >
                                  @if ($errors->has('card_year'))
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $errors->first('card_year') }}</strong>
                           </span>
                           @endif
                               </div>
                               <div class="col-lg-6 col-12">
                                 <div class="ccv">
                                   <input class="form-control {{ $errors->has('card_cvc') ? ' is-invalid' : '' }}" value="{{ old('card_cvc')}}" name="card_cvc"  type="text" placeholder="CVC">
                                    @if ($errors->has('card_cvc'))
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $errors->first('card_cvc') }}</strong>
                           </span>
                           @endif
                                   <div class="ccv-img"><img src="assets/images/ccv.png"></div>
                                 </div>
                               </div>
                             </div>
                           </div>
                           <div class="form-group">
                             <label>Name on card</label>
                             <input class="form-control{{ $errors->has('name_on_card') ? ' is-invalid' : '' }}" type="text" name="name_on_card" value="{{ old('name_on_card') }}" >
                           </div> -->
                        <div class="form-group">
                        <label>Country</label>
                        <select placeholder="Country" name="country"  class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}">
                        <option value="">Select</option>
                        @foreach($countries as $country)
                        <option {{ (Auth::guard('user')->user()->country_id == $country->id) ? 'selected' : '' }} value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                        </select>  
                        @if ($errors->has('country'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                        </div>
                        <button type="submit">Pay now</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
