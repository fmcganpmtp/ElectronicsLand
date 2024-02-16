@extends('layouts.template_mobile')
@section('title')
FMCG | Terms & Conditions
@endsection
@section('content')
<?php if(Cache::has('is_online' . 224))
echo  "logged in"; ?>

<div class="page-title-area">
  <div class="container">
    <div class="page-title-content">
      <ul>
        <li> <a href="{{ route('home') }}"> Home </a> </li>
        <li class="active">{{ $terms->title}}</li>
      </ul>
    </div>
  </div>
</div>
<div class="about-area  trms-and-condition innerp">
  <div class="container">
    <div class="about-content">
      <!--<h3>{{ $terms->title}}</h3>-->
      
     <a href="https://www.iubenda.com/terms-and-conditions/48420235" class="iubenda-nostyle iubenda-noiframe iubenda-embed iubenda-noiframe iub-body-embed" title="Terms and Conditions">Terms and Conditions</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
     
     
     
     
     
     
     
     
     
    <div class="gap-30"></div>
  </div>
</div></div>
@endsection
