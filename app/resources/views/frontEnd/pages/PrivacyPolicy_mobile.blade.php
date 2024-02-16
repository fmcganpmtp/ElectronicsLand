@extends('layouts.template_mobile')
@section('title')
FMCG | Privacy Policy
@endsection
@section('content')
<div class="page-title-area">
  <div class="container">
    <div class="page-title-content">
      <ul>
        <li> <a href="{{ route('home') }}"> Home </a> </li>
        <li class="active">{{ $privacy->title}}</li>
      </ul>
    </div>
  </div>
</div>
<div class="about-area ptb-54 trms-and-condition innerp">
  <div class="container">
    <div class="about-content">
      <!--<h3>{{ $privacy->title}}</h3>-->
      
      <?php //echo $privacy->page_content; ?>
     
     
     
     <a href="https://www.iubenda.com/privacy-policy/48420235" class="iubenda-nostyle iubenda-noiframe iubenda-embed iubenda-noiframe iub-body-embed" title="Privacy Policy">Privacy Policy</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
     
     
     
     
     
     
    <div class="gap-30"></div>
  </div>
</div></div>
@endsection
