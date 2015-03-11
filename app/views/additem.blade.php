@extends('layouts.main')
@section('title')
เพิ่มสินค้าใหม่
@stop
@section('script')
<script src="{{ asset('assets/js/dropzone.js') }}"></script>
<script>
Dropzone.autoDiscover = false;
// or disable for specific dropzone:
// Dropzone.options.myDropzone = false;

$(function() {
  // Now that the DOM is fully loaded, create the dropzone, and setup the
  // event listeners
  var myDropzone = new Dropzone("#drop-new-item");
  myDropzone.on("addedfile", function(file) {
    /* Maybe display some more file information on your page */
    $('#itemdetail').slideDown();
    //var ext=file.name.split('.').pop();
   // file.name=$.md5(new Date().getTime())+"."+ext;
   // console.log(file);
  });
  myDropzone.on("success", function(response) {
    /* Maybe display some more file information on your page */
    console.log(response);
    if($('#filelist').val()=="")$('#filelist').val(jQuery.trim(response.xhr.response));
    else $('#filelist').val(jQuery.trim($('#filelist').val())+","+jQuery.trim(response.xhr.response));
  });
})

jQuery( document ).ready(function( $ ) {

	$("#additem").submit(function(e)
	{
		var formObj = $(this);
	    var formData = new FormData(this);
	    var formURL = $(this).attr("action");
	    
	    $.ajax(
	    {
	        url : formURL,
	        type: "POST",
	        data : formData,
	        mimeType:"multipart/form-data",
    	contentType: false,
        cache: false,
        processData:false,
	        success:function(data, textStatus, jqXHR) 
	        {

	        	console.log(data);
	            if(data==1)bootbox.alert("คุณได้เพิ่มข้อมูลสินค้าเรียบร้อยแล้ว",function() {

	            	location.href='{{ route('home'); }}';
				});
	            else {
	            	var errs='<ul>';
	            	data=jQuery.parseJSON( data );
	            	$.each( data, function( key, value ) {
					  errs=errs+"<li>"+value+"</li>";
					});
					errs+="</ul>";
	            	bootbox.alert("ข้อมูลไม่ถูกต้องกรุณาตรวจสอบข้อมูลทีมต่อไปนี้ <div class='alert alert-danger'>"+errs+"</div>", function() {
					});
	            }
	        },
	        error: function(jqXHR, textStatus, errorThrown) 
	        {
	            //if fails      
	        }
	    });
	    e.preventDefault(); //STOP default action
	});

	$(function() {
	    $('.timepicker').datetimepicker({
	      pickDate: false
	    });
	  });
});



</script>
@stop
@section('style')
<link href="{{ asset('assets/css/dropzone.css') }}" rel="stylesheet">
@stop
@section('content')

<div class="row">
  	<div class="col-md-12 left">
            <form action="{{ route('uploaditem')}}" class="dropzone" id="drop-new-item">
            	<div class="dz-message">
					<p>ลากไฟล์เพื่อเพิ่มสินค้าใหม่</p>
					<span>หรือคลิกเพื่อเลือกไฟล์</span>
					<span>สูงสุด 5 รูป</span>
					</div>
            </form>
            {{ Form::open(array('url' => 'item/add','method'=>'post','enctype'=>'multipart/form-data','id'=>'additem','role'=>'form','class'=>'form-horizontal')) }}
            <div class="itemdetail">
         
  <div class="form-group" >
    <label for="inputEmail3" class="col-sm-2 control-label">ชื่อสินค้า</label>
    <div class="col-sm-3">
      <input name="itemname" type="text" class="form-control" id="inputEmail3" placeholder="ชื่อสินค้า" maxlength="40">
    </div>
    <label for="inputPassword3" class="col-sm-2 control-label">หมวดหมู่สินค้า</label>
						    <div class="col-sm-4">
						    	<select class="form-control" name="cat">
						    	@foreach ($categories as $cat)
								    <option value='{{ $cat->id }}'>{{ $cat->name }}</option>
								@endforeach
						      	</select>
						    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label" for="exampleInputAmount">ราคา (บาท)</label>
    <div class="col-sm-3">
      <input name="price" type="number" class="form-control" id="inputEmail3" placeholder="ราคา" maxlength="10">
    </div>
    <label class="col-sm-2 control-label" for="exampleInputAmount">จำนวน</label>
    <div class="col-sm-1">
      <input name="amount" type="number" class="form-control" id="inputEmail3" value="1" maxlength="3">
    </div>
  
    
						    
						  </div>

   <div class="form-group">
						  <label for="inputPassword3" class="col-sm-2 control-label">ค่าจัดส่ง</label>
						  <div class="col-sm-3">
						    
						    @foreach ($shippings as $shipping)
						    <label class="radio-inline">
  <input type="radio" name="shippingprice" id="inlineRadio1" value="{{$shipping->id}}"> {{$shipping->name}}
</label>
@endforeach
</div>
 <label for="inputPassword3" class="col-sm-2 control-label">รายละเอียด</label>
						    <div class="col-sm-4">
						    	<textarea name="detail" class="form-control" rows="3"></textarea>
						    </div>
						    
						  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-info btn-default">ลงประกาศ</button>
    </div>
  </div>
  <input type="hidden" id="filelist" name="filelist" />
{{ Form::close() }}
            </div>
	</div>
</div>




@stop
