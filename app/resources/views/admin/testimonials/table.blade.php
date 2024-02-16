@extends('admin.master')
@section('title', 'Content Page')
@section('breadcrumb')Page Content @endsection
@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
@if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>{{ _('messages.Whoops') }}!</strong> {{ _('messages.There were some problems with your input') }}.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            <ul>
                <li>{{ session('success') }}</li>
            </ul>
        </div>
    @endif
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
      @if(Session::has('message')) <div class="alert alert-success">{{Session::get('message') }}</div> @endif
        <h2>Content Pages</h2>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-12">
              <div class="card ">
                <div class="card-header">
                	<div class="search-area wth-btn">
                    
                     <div class="form-group">
					 
					  <a href="{{ route('admin.sliders') }}" style="text-decoration:none;padding: 10px 16px;position: relative;top: 7px; color:#fff;" class="bl-btn flt-left">Slider</a>
                        <a href="{{ route('admin.contentpages') }}"  style="text-decoration:none;padding: 10px 16px;position: relative;top: 7px; color:#fff;" class="bl-btn flt-left">Content Page</a>
					 
				
                    </div>
                    
                    </div>
                    
                    <a href="{{route('contentpages.create') }}" class="bl-btn flt-right">Create Content</a>
                    
                    
                </div>
                <div class="card-body">
    <div class="tableC">
				  
                
<table class="table table-striped table-bordered" id="sortTable">
<thead>
<tr>
<th>Firstname</th>
<th>Lastname</th>
<th>Email</th>
</tr>
</thead>
<tbody>
<tr>
<td>Adam</td>
<td>joo</td>
<td>Jadamj@yahoo.com</td>
</tr>
<tr>
<td>seri</td>
<td>sami</td>
<td>ami_seri@rediff.com</td>
</tr>
<tr>
<td>zeniya</td>
<td>deo</td>
<td>zee@gmail.com</td>
</tr>
</tbody>
</table>
             </div>
                  
                 
                  @include('admin.UserButtons')
                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  

<script>
$('#sortTable').DataTable();
$(document).ready(function () {
  $('#selectedColumn').DataTable({
    "aaSorting": [],
    columnDefs: [{
    orderable: false,
    targets: 3
    }]
  });
    $('.dataTables_length').addClass('bs-select');
});
</script>



@endsection