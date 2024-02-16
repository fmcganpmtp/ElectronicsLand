@extends('layouts.template')
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


<!--

<div class="about-new">

<div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pack-box-03 abt-top1">
          <div class="top-title">
            <h2>About FMCG Land</h2>
          </div>
          <div class="pack-sec-1">
            <div class="row">
              <div class="col-lg-6 col-12">
                <h3>We are a B2B social business platform connecting the world of FMCG professionals.</h3>
                <div class="pack-top-content">
                  <p>FMCG Land is a new unique platform that connects buyers, sellers, manufacturers, wholesalers and brands in the fast-moving consumer goods industry into a single dynamic global community. Delivering unbeatable opportunities for buying, selling, sourcing, networking, and advertising on a truly global scale.</p>
				  
				  <p>No more long searches online to expand your business. Become part of the fastest growing B2B community in FMCG, and get instant access to a world full of opportunities!</p>
                </div>
               </div>
              <div class="col-lg-6 col-12">
			  
			  
			  
			  
			  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/images/about-slide-1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="assets/images/about-slide-2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="assets/images/about-slide-3.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
			  
		  
              </div>
            </div>
          </div>
    
  
        </div>
      </div>
    </div>
  </div>
   <section class="section-green">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h2>FMCG Land. What's in it for you.</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6 col-12">
        <ul>
          <li>Become part of the fastest growing B2B community in the FMCG industry and get instant access to a world full of opportunities</li>
           <li> No more long searchings on Google/internet in order to expand your business</li>
           <li> Get access to FMCG Land's Marketplace with over 30.000 products, representing all FMCG categories</li>
        </ul>
      </div>
	  
	   <div class="col-lg-6 col-12">
        <ul>
	  <li>Unique and innovative B2B platform that takes networking to the next level</li>
	  <li>	Connect & chat with FMCG professionals in over 100 countries</li>
		  <li>No commissions or hidden fees</li>
		  <li>All FMCG Land users are Verified Members</li>
        </ul>
      </div>
	  
	  
    </div>
	
	<div class="row">
		<div class="col-lg-12">
			<a href="#" class="golden-btn-2">GET YOUR PERSONAL PLAN, FROM 'FREE' TO 'PREMIUM', HERE</a>
		</div>
	</div>
	
  </div>
</section>

	<div class="container">
		<div class="row">
		
		<div class="col-lg-12">
		
		<div class="pack-box-03 abt-btm2">
		<div class="row">
	
		 <div class="col-lg-6 col-12">
		 	<div class="bottom-add"><img src="assets/images/bottom-add-image.jpg" /></div>
			<div class="bottom-add"><img src="assets/images/bottom-add-image-2.jpg" /></div>
		 </div>
		 	 <div class="col-lg-6 col-12">
		 <h3>Advertise on FMCG Land!</h3>
		     <div class="pack-top-content">
			 
			 <p>With FMCG Land you'll reach exactly the ones you are looking for. From Distributors to Wholesalers, from Private Labels to Manufacturers, and from Buyers to Sellers - everybody is there. This makes FMCG Land the place to put your presence in the FMCG market top-of-mind. Whether you are a brand or independent trader.</p>
			 <p>Get to know our advertising possibilities and competitive rates. Start
advertising on our unique and innovative B2B platform that takes trading and networking in the FMCG industry to the next level.</p>
			 </div>
			 
			                 <a href="#" class="yellow-btn03">INQUIRE OUR ADVERTISING POSSIBILITIES
</a> </div>

		 </div>
		</div> </div></div>
		</div>

	</div>
-->
@endsection
