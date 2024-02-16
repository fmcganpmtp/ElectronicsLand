@include('layouts.header')
@section('title')
FMCG | Product Details
@endsection
@section('content')

<style>
.ui-state-active h4,
.ui-state-active h4:visited {
    color: #26004d ;
}

.ui-menu-item{
    height: 80px;
    border: 1px solid #ececf9;
}
.ui-widget-content .ui-state-active {
    background-color: white !important;
    border: none !important;
}
.list_item_container {
    width:740px;
    height: 80px;
    float: left;
    margin-left: 20px;
}
.ui-widget-content .ui-state-active .list_item_container {
    background-color: #f5f5f5;
}

.image {
    width: 15%;
    float: left;
    padding: 10px;
}
.image img{
    width: 40px;
}
.label_search{
    width: 85%;
    float:right;
    white-space: nowrap;
    overflow: hidden;
    color: rgb(124,77,255);
    text-align: left;
}
input:focus{
    background-color: #f5f5f5;
}

</style>


    <p>&nbsp;</p>
    <div class="well">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="input-group">

                    <input type="text" autocomplete="off" id="search" class="form-control input-lg" placeholder="Search">
                </div>
            </div>
        </div>
    </div>
<!-- search box container ends  -->
<div style="padding-top:280px;" ></div>


<script type="text/javascript">
//var $fmcg = $.noConflict();
$fmcg(document).ready( function () { 
    $fmcg("#search").autocomplete({ 
        source: "{{ url('TypeaheadSearch') }}",
            focus: function( event, ui ) {
            $fmcg( "#search" ).val( ui.item.title ); // uncomment this line if you want to select value to search box  
            return false;
        },
        select: function( event, ui ) {
            window.location.href = ui.item.url;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        var inner_html = '<a href="' + item.url + '" ><div class="list_item_container"><div class="image"><img src="' + item.image + '" ></div><div class="label_search"><p><b>' + item.title + '</b></p></div></div></a>';
        return $fmcg( "<li></li>" )
                .data( "item.autocomplete", item )
                .append(inner_html)
                .appendTo( ul );
    };
});
</script>  

     
     
     
     
@include('layouts.footer_scripts')
@include('layouts.footer')