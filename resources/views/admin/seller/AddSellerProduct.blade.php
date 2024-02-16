@extends('layouts.template')
@section('title', 'List Packages')
@section('content')

<sectiion class="seller-page no-bg">
  <div class="pr-banner">
    <!--<img src="assets/images/pr-banner.jpg">-->
  </div>
  <div class="container">
    <div class="row"> </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="pr-bottom ad-pro" >
          <div class="card">
            <div class="step-icon">
              <div class="step-img"><img src="assets/images/product-add.png"></div>
              <div class="step-count">1</div>
            </div>
            <h3>Add product</h3>
            <div class="btn-align-center">
                <!-- After product id pass -->
               <form action="{{ route('existing.product') }}" method="get">
              <button class="default-btn">Choose from existing</button>
                   </form>
           
              <button class="default-btn">Create new</button>
           
              
             
                  
          
              
              
            </div>
            <!--step--->
            
            <div class="row" >
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Product name:</label>
                  <input type="text" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Product price:</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label> Category:</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>SKU number:</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Stock count:</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label> Color:</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Weight</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Size:</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Dimension:</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Variants:</label>
                  <input type="email" class="form-control" >
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Thumbnail image:</label>
                  <input type="file" class="file-input form-control"  multiple>
                </div>
              </div>
              <div class=" col-lg-4  col-12">
                <div class="form-group">
                  <label>Gallery Images(multiple upload):</label>
                  <input type="file" class="file-input form-control"  multiple>
                </div>
              </div>
              <div class=" col-lg-12  col-12">
                <div class="form-group">
                  <label>Description:</label>
                  <textarea class="form-control" placeholder="" rows="5" cols="5" > </textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="bl-btn">Submit</button>
                </div>
              </div>
            </div>
            <!---step--->
            
            <!--step-2-->
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
</sectiion>



			

@endsection
@section('footer_script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

</script>


@endsection