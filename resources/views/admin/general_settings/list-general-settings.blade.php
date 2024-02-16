@extends('admin.master')
@section('title', 'Admin General Settings')
@section('breadcrumb') General Settings @endsection
@section('content')




 
    
    <div class="body flex-grow-1 px-3">
    <div class="container-lg">
      <div class="card-out mb-4 inner-form">
            
     <br />
     <h3> Admin General Settings</h3>
     <br />
   <div class="table-responsive">
                 <span id="result"></span>
                 <form method="post" id="general_setting_form" action="{{route('update.settings')}}">
                 <table class="table table-bordered table-striped" id="user_table">
               <thead>
                <tr>
                    <th width="35%">Unique Key</th>
                    <th width="35%">Diplay Name</th>
					 <th width="35%">Value</th>
                    <th width="30%">Action</th>
                </tr>
               </thead>
               <tbody>
                @foreach($general_settings as $general_setting)
                <tr>
        <td><input type="text" name="unique_key[]" class="form-control" value="{{$general_setting->unique_key}}" readonly></td>
        <input type="hidden" name="id[]" class="form-control" value="{{$general_setting->id}}" >
		<td><input type="text" name="display_name[]" class="form-control" value="{{$general_setting->display_name}}"/></td>
        <td><input type="text" name="value[]" class="form-control" value="{{$general_setting->value}}"/></td>
		<td><a href="{{route('delete.general-settings',$general_setting->id)}}"><i class="icon cil-trash"></i></a></td>   
</tr>
                @endforeach
               </tbody>
                
                  @csrf
           </table>
           <button type="submit" class="bl-btn">
                                    {{ __('Update') }}
                                </button>
                </form>
                <a href="{{route('create.settings')}}" class="bl-btn flt-right">Add New Settings</a>
   </div>
  
      </div>
    </div>
  </div>






@endsection