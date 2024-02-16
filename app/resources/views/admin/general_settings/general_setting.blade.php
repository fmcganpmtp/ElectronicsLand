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
                <form method="post" id="general_setting_form">
                 <span id="result"></span>
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

               </tbody>
               <tfoot>
                <tr>
                                <td colspan="3" align="right">&nbsp;</td>
                                <td>
                  @csrf
                  <input type="submit" name="save" id="save" class="btn btn-primary" value="Save" />
                 </td>
                </tr>
               </tfoot>
           </table>
                </form>
   </div>
  
      </div>
    </div>
  </div>



<script>
$(document).ready(function(){

 var count = 1;

 general_setting(count);

 function general_setting(number)
 {
  html = '<tr>';
        html += '<td><input type="text" name="unique_key[]" class="form-control" /></td>';
		html += '<td><input type="text" name="display_name[]" class="form-control" /></td>';
        html += '<td><input type="text" name="value[]" class="form-control" /></td>';
        if(number > 1)
        {
            html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
            $('tbody').append(html);
        }
        else
        {   
            html += '<td><button type="button" name="add" id="add" class="btn btn-success">Add</button></td></tr>';
            $('tbody').html(html);
        }
 }

 $(document).on('click', '#add', function(){
  count++;
  general_setting(count);
 });

 $(document).on('click', '.remove', function(){
  count--;
  $(this).closest("tr").remove();
 });

 $('#general_setting_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:'{{ route("general-setting.insert") }}',
            method:'post',
            data:$(this).serialize(),
            dataType:'json',
            beforeSend:function(){
                $('#save').attr('disabled','disabled');
            },
            success:function(data)
            {
                if(data.error)
                {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<p>'+data.error[count]+'</p>';
                    }
                    $('#result').html('<div class="alert alert-danger">'+error_html+'</div>');
                }
                else
                {
                    general_setting(1);
                    $('#result').html('<div class="alert alert-success">'+data.success+'</div>');
                }
                $('#save').attr('disabled', false);
            }
        })
 });

});
</script>


@endsection