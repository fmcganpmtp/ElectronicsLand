@extends('layouts.template_mobile')
@section('title')
FMCG | About Us
@endsection
@section('content')






<?php ?>

<div class="page-title-area">
  <div class="container">
    <div class="page-title-content">
      <ul>
        <li> <a href="{{ route('home') }}"> Home </a> </li>
        <li class="active">{{ $about_us->title ?? ''}}</li>
      </ul>
    </div>
  </div>
</div>



<div class="about-area ptb-54 trms-and-condition ">
  
    <div class="about-content">
      <!--<h3>{{ $about_us->title  ?? ''}}</h3>-->
      
    <?php echo $about_us->page_content; ?>
     
     
     
     
     
     
     
     
     
     
    <div class="gap-30"></div>
  </div>
</div><?php ?>




@endsection
