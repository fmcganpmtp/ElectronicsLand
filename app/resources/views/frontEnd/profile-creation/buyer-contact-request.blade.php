@extends('layouts.template')
@section('title')
FMCG | Buyer Contact Request
@endsection
@section('content')


<sectiion class="seller-page no-bg">
  <div class="container buyer-dtl55">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom">

          <div class="card">
          
                            <h3>Buyer Contact Request</h3>
@foreach( $sellermessages as $data )
            <div class="by-contactC slr-details">
              <div class="row">
                <div class="col-lg-1 col-12">
                  <div class="pr-logo ">
                  <?php
              if(!empty($data->Seller->profile_pic)) 
              $img_path = asset('/uploads/userImages/').'/'.$data->User->profile_pic;
              else  $img_path = asset('uploads/defaultImages/default_avatar.png');
              ?>
                  <img src="{{$img_path}}"></div>
                </div>
                <div class="col-lg-5 col-12">
                  <ul>
                    <li>
                  {{ $data->Seller->name}}<br>
                  {{ $data->Seller->address}}</li>
                    <li>Mob: {{ $data->Seller->phone}}</li>
                  </ul>
                  <div class="time-bx">
                    <h6>Contacted Time : <i class="fa fa-calendar" aria-hidden="true"></i> {{date('d - M - Y', strtotime($data->created_at))}} <i class="fa fa-clock-o" aria-hidden="true"></i> {{Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('g:i a')}}</h6>
                  </div>
                </div>
                
                <div class="col-lg-6 col-12">
                  <div class="cmt-bx-d1">
                    <h4>Comments</h4>
                    <div class="bg-white1 p-2">
                      <div class="d-flex flex-row user-info">
                        <div class="rounded-circle">
                        <?php
              if(!empty($data->User->profile_pic)) 
              $img_path = asset('/uploads/userImages/').'/'.$data->User->profile_pic;
              else  $img_path = asset('uploads/defaultImages/default_avatar.png');
              ?>
                        <img class="" src="{{$img_path}}"></div>
                        <div class="d-flex flex-column justify-content-start ml-2"><span class="d-block font-weight-bold name">{{$data->name}}</span></div>
                      </div>
                      <div class="mt-2">
                        <p class="comment-text">{{$data->message}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        
  @endforeach          
  {{$sellermessages->links()}}        
            
           
            
            
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</sectiion>




@endsection
