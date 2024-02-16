@extends('admin.master')
@section('title', 'Slider')
@section('breadcrumb')  Slider @endsection
@section('content')
 
<div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
        <h2>Sliders</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header"> </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12 col-12">
                      
                    
             

                      
                      <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @php $count = 0; @endphp
                                @foreach ($slider_images as $images)
                                    <li data-bs-target="#carouselExampleCaptions" data-slide-to="0" @if ($count == 0) {{ 'class="active"' }} @endif></li>
                                    @php $count++; @endphp
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @php $count = 0; @endphp
                                @foreach ($slider_images as $images)
                                    <div class="carousel-item @if ($count==0) {{ 'active' }} @endif">
                                        @if ($images->image != '')<img src="{{ asset('/assets/uploads/sliders/' . $images->image) }}" class="img-thumbnail" width="98%" height="100px" />@endif
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>{{ $images->title }}</h5>
                                            <p>{{ $images->description }}</p>
                                            @if ($images->target)
                                                <a href="{{ $images->target }}">Readmore</a>
                                            @endif
                                        </div>
                                    </div>
                                    @php $count++; @endphp
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </a>
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
  




<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

@endsection