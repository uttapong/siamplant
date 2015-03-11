@extends('layouts.main')
@section('title')
สมัครสมาชิก
@stop
@section('script')
<script>
jQuery( document ).ready(function( $ ) {
	

	$("#adduser").submit(function(e)
	{
	    var postData = $(this).serializeArray();
	    var formURL = $(this).attr("action");
	    $.ajax(
	    {
	        url : formURL,
	        type: "POST",
	        data : postData,
	        success:function(data, textStatus, jqXHR) 
	        {

	        	objtype=typeof  data;
	        	console.log(data);

	            if(objtype=='string')bootbox.alert(data,function() {

	            	location.href='{{ action('HomeController@index'); }}';
					});
	            else {
	            	var errs='<ul>';
	            	$.each( data, function( key, value ) {
					  errs=errs+"<li>"+value+"</li>";
					});
					errs+="</ul>";
	            	bootbox.alert("ข้อมูลไม่ถูกต้องกรุณาตรวจสอบข้อมูลของท่าน <div class='alert alert-danger'>"+errs+"</div>", function() {
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
	
});



</script>
@stop
@section('style')
<style>
	html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        text-overflow: ellipsis;
      }

      #pac-input:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 401px;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

	 #target {
        width: 345px;
      }
</style>
@stop
@section('content')

<div class="row">
  	<div class="col-md-12 align-center" >
  <h1 class="title">สมัครสมาชิก</h1>
  </div>
  <div class="col-md-2 left">
	
  </div>
  <div class="col-md-8 left">
	
	
	<div class="row">
		
        <div class="col-md-12 left">
            <div id="open-team">
                         {{ Form::open(array('url' => 'user/add','method'=>'post','id'=>'adduser','role'=>'form','class'=>'form-horizontal')) }}
						  <div class="form-group">
						    <label for="name" class="col-sm-2 control-label">ชื่อผู้ใช้</label>
						    <div class="col-sm-4">
						      <input type="text" class="form-control"  name='username'>
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="name" class="col-sm-2 control-label">รหัสผ่าน</label>
						    <div class="col-sm-4">
						      <input type="password" class="form-control"  name='password'>
						    </div>
						    <label for="name" class="col-sm-2 control-label">ยืนยันรหัสผ่าน</label>
						    <div class="col-sm-4">
						      <input type="password" class="form-control"  name='password2'>
						    </div>
						  </div>
						 
						  <div class="form-group">
						    <label for="lat" class="col-sm-2 control-label">ชื่อ</label>
						    <div class="col-sm-4">
						      <input type="text" class="form-control" name="firstname" >
						    </div>
						    <label for="inputPassword3" class="col-sm-2 control-label">สกุล</label>
						    <div class="col-sm-4">
						      <input type="text" class="form-control" name="lastname">
						    </div>
						    <div class="col-sm-4">
						    </div>

						  </div>
						  <div class="form-group">
						    <label for="inputEmail3" class="col-sm-2 control-label">อีเมล์</label>
						    <div class="col-sm-4">
						      <input type="email" class="form-control" name="email" >
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputPassword3" class="col-sm-2 control-label">โทร</label>
						    <div class="col-sm-4">
						      <input type="text" class="form-control" name="phone">
						    </div>
						    <label for="inputPassword3" class="col-sm-2 control-label">มือถือ</label>
						    <div class="col-sm-4">
						      <input type="text" class="form-control" name="mobile">
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputPassword3" class="col-sm-2 control-label">วันเกิด</label>
						    <div class="col-sm-4">
						    	<div class="bfh-datepicker "  data-format="y-m-d" data-name="birthdate"></div>
								
						    </div>
						    
						  </div>

						  <div class="form-group">
						  <label for="inputPassword3" class="col-sm-2 control-label">ที่อยู่</label>
						    <div class="col-sm-4">
						    	<textarea name="address" class="form-control" rows="3"></textarea>
						    </div>
						    <label for="inputPassword3" class="col-sm-2 control-label">จังหวัด</label>
						    <div class="col-sm-4">
						    	<select class="form-control" name="province">
						    	@foreach ($provinces as $province)
								    <option value='{{ $province->id }}'>{{ $province->name }}</option>
								@endforeach
						      	</select>
						    </div>
						  </div>

						  
						  <div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="submit" class="btn btn-info btn-default">สมัคร</button>
						    </div>
						  </div>
					{{ Form::close() }}
            </div>
        </div>
        		
	</div>
	
  </div>

  <div class="col-md-2 left">
	
  </div>
</div>




@stop