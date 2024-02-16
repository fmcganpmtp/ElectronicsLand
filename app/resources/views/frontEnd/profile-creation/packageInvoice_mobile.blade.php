@extends('layouts.template_mobile')
@section('title', 'Package Invoice')
@section('content')
<div class="pack-listing pack-dtl">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-12 ">
        <div class="card-bx plan-dtl-bx">
        <div class="pl-tp">
          <h1> {{$package->name  ?? ''}}<br/>
<span>({{$package->user_type  ?? ''}})</span></h1>
          <h2> <b>(${{$package->package_basic_price ?? ''}})</b>
            ({{$package->package_validity}}) </h2>
            </div>
            
          <div class="pans-txt">
            <p>{{$package->package_description ?? ''}}</p>
          </div>
         <ul>
                @foreach ($package->IncludedFeatures as $includ_feature)
                        @if($includ_feature->feature!="")
                        <li class="avl">{{ $includ_feature->feature }}</li>
                        <p></p>
                       @endif
                        @endforeach 
                        @foreach ($package->ExcludedFeatures as $exclud_feature)
                        @if($exclud_feature->feature!="")
                        <li class="n-avl">{{ $exclud_feature->feature }}</li>
                       @endif
                        @endforeach
                  
                </ul>
                <!--href="subscription-checkout.php"-->
          
        </div>
      </div>
     
	 
	 
	 
	 
	 
	 
	  <div class="col-lg-6 col-12 " >
                  <div class="oreder-dtl pack-dtl-right">
                  
                  <a class="print-invo" href="{{route('prnpriview', ['package_id'=>$package->id,'subscription_id'=>$subscription->id])}}" target="_blank" ><i class="fa fa-print" aria-hidden="true"></i></a>
                  <!--  <a class="print-invo" href="javascript:void(0);" onclick="printPageArea('printableArea')" ><i class="fa fa-print" aria-hidden="true"></i></a> -->

<h4>Order Detail</h4>
                    <div class="tableC " id="printableArea">
                      <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <th>Order Date </th>
                            <td>{{date('d - M - Y', strtotime($subscription->date))}}</td>
                          </tr>
                          <tr>
                            <th>Order Status </th>
                            <td><span class="act"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;{{$subscription->status}}</span></td>
                          </tr>
                          <tr>
                            <th>Date of payment </th>
                            <td>{{date('d - M - Y', strtotime($subscription->date))}}</td>
                          </tr>
                          <tr>
                            <th>Billing Address </th>
                            <td><ul>
                               <?php if(!empty($subscription->OrderDetail->name)) { ?><li>{{$subscription->OrderDetail->name}}</li><?php } ?>
                               <?php if(!empty($subscription->OrderDetail->phone)) { ?><li> {{$subscription->OrderDetail->phone}}</li><?php } ?>
                               <?php if(!empty($subscription->OrderDetail->address)) { ?> <li> {{$subscription->OrderDetail->address}}</li><?php } ?>
                               <?php if(!empty($subscription->OrderDetail->city)) { ?> <li> {{$subscription->OrderDetail->city}}</li><?php } ?>
                               <?php if(!empty($subscription->OrderDetail->zip)) { ?> <li> {{$subscription->OrderDetail->zip}}</li><?php } ?>
                              </ul></td>
                          </tr>
                        </tbody>
                      </table>
                      
                      
                      
                    </div>
                    
                                          <div class="price-footer">  </div>

                    </div>
                  </div>
	 
	 
	 
	 
	 
	 
	 
	 
    </div>
  </div>
</div>
<style>
@page { size: auto;  margin: 0mm; }
</style>
<script>
function printPageArea(areaID){
    var printContent = document.getElementById(areaID).innerHTML;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
}
</script>

@endsection
