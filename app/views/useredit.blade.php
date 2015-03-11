@extends('layouts.main')
@section('title')
Teebadgun.com : แก้ไขข้อมูลสมาชิก
@stop
@section('script')
<script>
var allAmphur;
jQuery( document ).ready(function( $ ) {
	allAmphur=$('select[name="district"]').children();

	province=$('select[name="province"]').val();
	$('option.amphur[province!="'+province+'"]').detach();

	$(".updateuser").submit(function(e)
	{
	  

	    var formObj = $(this);
    	var formURL = formObj.attr("action");
    	var formData = new FormData(this);
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
	            if(data==1)bootbox.alert("ข้อมูลของท่านถูกแก้ไขเรียบร้อยแล้ว",function() {

	            	location.href='{{ action('HomeController@index'); }}';
					});
	            else {
	            	var obj = jQuery.parseJSON( data );
	            	var errs='<ul>';
	            	$.each( obj, function( key, value ) {
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

function showAmphur(){
	province=$('select[name="province"]').val();
	$('select[name="district"]').append(allAmphur);
	//$('option.amphur[province="'+province+'"]').show();
	$('option.amphur[province!="'+province+'"]').detach();

}

function showProfile(){
	$('#btn-shop').removeClass('btn-success');
	$('#shop-profile').hide();
	$('#personal-profile').show();
	$('#btn-profile').addClass('btn-success');
}

function showShopProfile(){
	$('#btn-profile').removeClass('btn-success');
	$('#personal-profile').hide();
	$('#shop-profile').show();
	$('#btn-shop').addClass('btn-success');
	
}

function removeBank(banknum){
	$('.bankinfo-'+banknum).remove();
}


var bankcount=100;
function addBank(){
	//var bankcount;
	bankcount++;

	strdiv=' <div class="bankinfo-'+bankcount+'">'
						  +'<div class="form-group">'
						    +'<label for="inputPassword3" class="col-sm-1 control-label">ธนาคาร</label>'
						    +'<div class="col-sm-3">'
						    	+'<select class="form-control" name="bank[]">'
						    	+'@foreach($banks as $bank)'
						    		+'<option value="{{$bank->id}}">{{$bank->name}}</option>'
						    	+'@endforeach'
						    		
						    	+'</select>'
						   +' </div>'
						    +'<label for="inputPassword3" class="col-sm-1 control-label">ชื่อบัญชี</label>'
						    +'<div class="col-sm-3">'
						    	+'{{ Form::text("bankaccountname[]",null,array("class"=>"form-control")) }}'
						    +'</div>'
						    +'<label for="inputPassword3" class="col-sm-1 control-label">เลขบัญชี</label>'
						    +'<div class="col-sm-2">'
						    	+'{{ Form::text("bankaccountno[]",null,array("class"=>"form-control")) }}'
						    +'</div>'
						    +'<div class=" col-sm-1">'
						      +'<button  class="btn btn-warning" onclick="removeBank('+bankcount+');"><span class="glyphicon glyphicon-minus-sign"></span></button>'
						    +'</div>'
						  +'</div>'

						+'</div>';
	$('#banks').append(strdiv);
}

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
  	<div class="col-md-12 align-center" style="margin-bottom: 20px;">
  	<div class="btn-group btn-group-lg " role="group" aria-label="...">
	  <button type="button" id="btn-profile" class="btn btn-success" onclick="showProfile();"><span class="glyphicon glyphicon-user" ></span> ข้อมูลสมาชิก</button>
	  <button type="button" id="btn-shop" class="btn btn-default" onclick="showShopProfile()"><span class="glyphicon glyphicon-tree-deciduous" ></span>ข้อมูลร้านค้า</button>
	</div>
  </div>
  <div class="col-md-1 left">
	
  </div>
  <div class="col-md-10 left">
	
	
	<div id="personal-profile" class="row">
		
        <div class="col-md-12 left">
            <div id="open-team">
            			{{ Form::model($user, array('route' => array('userupdate', $user->id),'enctype'=>'multipart/form-data','role'=>'form','class'=>'updateuser form-horizontal','method'=>'post')) }}
              
						  <div class="form-group">
						    <label for="name" class="col-sm-2 control-label">ชื่อผู้ใช้</label>
						    <div class="col-sm-4">
						      {{ Form::text('username',null,array('class'=>'form-control','disabled')) }}
						    </div>
						     <label for="name" class="col-sm-1 control-label">ชื่อแสดง</label>
						    <div class="col-sm-4">
						      {{ Form::text('displayname',null,array('class'=>'form-control',)) }}
						    </div>
						  </div>
						   <div class="form-group">
						    <label for="exampleInputFile" class="col-sm-2 control-label">รูปโปรไฟล์</label>
						    <div class="col-sm-4">
						    <input type="file" id="picture" name="picture">
						    <p class="help-block">รูปจตุรัส ขนาดไม่เกิน 400KB</p>
						    @if($user->picture)
						    	<?php $image=Imagecache::get($user->id.'/profile/'.$user->picture, 'profilepreview'); ?>
						    	<img src="{{$image->src}}" />
						    
						    @endif
						    </div>

						  </div>
						  <div class="form-group">
						    <label for="name" class="col-sm-2 control-label">รหัสผ่าน</label>
						    <div class="col-sm-4">
						      <input type="password" class="form-control"  name='password'>
						    </div>
						    <label for="name" class="col-sm-1 control-label">ยืนยันรหัสผ่าน</label>
						    <div class="col-sm-4">
						      <input type="password" class="form-control"  name='password2'>
						    </div>
						  </div>
						 
						  <div class="form-group">
						    <label for="lat" class="col-sm-2 control-label">ชื่อ</label>
						    <div class="col-sm-4">
						      {{ Form::text('firstname',null,array('class'=>'form-control')) }}
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">สกุล</label>
						    <div class="col-sm-4">
						       {{ Form::text('lastname',null,array('class'=>'form-control')) }}
						    </div>
						    <div class="col-sm-4">
						    </div>

						  </div>
						  <div class="form-group">
						    <label for="inputEmail3" class="col-sm-2 control-label">อีเมล์</label>
						    <div class="col-sm-4">
						      {{ Form::text('email',null,array('class'=>'form-control')) }}
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputPassword3" class="col-sm-2 control-label">โทร</label>
						    <div class="col-sm-4">
						       {{ Form::text('phone',null,array('class'=>'form-control')) }}
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">มือถือ</label>
						    <div class="col-sm-4">
						       {{ Form::text('mobile',null,array('class'=>'form-control')) }}
						    </div>
						  </div>
						  <div class="form-group">
						    <label for="inputPassword3" class="col-sm-2 control-label">วันเกิด</label>
						    <div class="col-sm-4 input-group ">
						    	<div class="bfh-datepicker"  data-format="y-m-d" data-name="birthdate" data-date="{{ $user->birthdate }}"></div>
								
						    </div>
						    
						  </div>

						   <div class="form-group">
						    <label for="inputPassword3" class="col-sm-2 control-label">ที่อยู่</label>
						    <div class="col-sm-4">
						    	{{ Form::textarea('address',null,array('class'=>'form-control','rows'=>'3')) }}
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">จังหวัด</label>
						    <div class="col-sm-4">
						    	<select class="form-control" name="province" onchange='showAmphur();'>
						    	@foreach ($provinces as $province)
								    <option  value='{{ $province->id }}' @if($user->province==$province->id) selected @endif >{{ $province->name }}</option>
								@endforeach
						      	</select>
						    </div>
						 
						  </div>

						  <div class="form-group">
						   
						    
						    <label for="inputPassword3" class="col-sm-2 control-label">อำเภอ</label>
						    <div class="col-sm-2">
						    	<select class="form-control" name="district">
						    	@foreach ($amphurs as $amphur)
								    <option class='amphur' province='{{ $amphur->province_id }}' value='{{ $amphur->id }}' @if($user->district==$amphur->id) selected @endif >{{ $amphur->name }}</option>
								@endforeach
								</select>
						    </div>
						  </div>
						  
						  

						
						  
						  <div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="submit" class="btn btn-warning">บันทึก</button>
						    </div>
						  </div>
					{{ Form::close() }}
            </div>
        </div>
        		
	</div>
	<div id="shop-profile" style="display:none;" class="row"><!--end personal profile -->
		
        <div class="col-md-12 left">
            <div id="open-team">
            			{{ Form::model($user, array('route' => array('shopupdate', $user->id),'enctype'=>'multipart/form-data','role'=>'form','class'=>'updateuser form-horizontal','method'=>'post')) }}
              
						  <div class="form-group">
						    <label for="name" class="col-sm-1 control-label">ชื่อร้าน</label>
						    <div class="col-sm-3">
						      {{ Form::text('shopname',null,array('class'=>'form-control')) }}
						    </div>
						      <label for="inputEmail3" class="col-sm-1 control-label">อีเมล์ร้าน</label>
						    <div class="col-sm-3">
						      {{ Form::text('shopemail',null,array('class'=>'form-control')) }}
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">โทร</label>
						    <div class="col-sm-3">
						       {{ Form::text('shoptel',null,array('class'=>'form-control')) }}
						    </div>
						  </div>

						   <div class="form-group">
						   <label for="inputPassword3" class="col-sm-1 control-label">เกี่ยวกับร้าน</label>
						    <div class="col-sm-3">
						    	{{ Form::textarea('shopdetail',null,array('class'=>'form-control','rows'=>'3')) }}
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">ที่อยู่</label>
						    <div class="col-sm-3">
						    	{{ Form::textarea('shopaddress',null,array('class'=>'form-control','rows'=>'3')) }}
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">จังหวัด</label>
						    <div class="col-sm-3">
						    	<select class="form-control" name="shopprovince" onchange='showAmphur();'>
						    	@foreach ($provinces as $province)
								    <option  value='{{ $province->id }}' @if($user->shopprovince==$province->id) selected @endif >{{ $province->name }}</option>
								@endforeach
						      	</select>
						    </div>
							</div>
						
						  <div class="form-group">
						     <label for="exampleInputFile" class="col-sm-1 control-label">พื้นหลังร้าน</label>
						    <div class="col-sm-4">
						    <input type="file" id="shoppicture" name="shoppicture">
						    <p class="help-block">ขนาดไม่เกิน 1000x200 พิกเซล และไม่เกิน 1 MB</p>
						    @if($user->shoppicture)
						    	<?php $image=Imagecache::get($user->id.'/profile/'.$user->shoppicture, 'profilepreview'); ?>
						    	<img src="{{$image->src}}" />
						    
						    @endif
						    
						  	</div>
						  	<div class="col-sm-7">
						 	</div>
						 </div>
						  <div style="margin-top: 30px;" id="banks">
						  <h3 >ข้อมูลสำหรับการชำระเงิน</h3>
					

						  <div class="form-group">
							 <div class="col-sm-1">
						      <button type="button" class="btn btn-success btn-xs" onclick='addBank();'><span class="glyphicon glyphicon-plus"></span> เพิ่มธนาคาร</button>
						    </div>
						  </div>
							<?php $bankaccounts=$user->getallbanks(); $bankcount=0; ?>
						  @if($bankaccounts)
							@foreach($bankaccounts as $bankacc)

							<div class="bankinfo-{{$bankcount}}">
						  <div class="form-group">
						    <label for="inputPassword3" class="col-sm-1 control-label">ธนาคาร</label>
						    <div class="col-sm-3">
						    	<select class="form-control" name="bank[]">
						    	@foreach($banks as $bank)
						    		<option value="{{$bank->id}}" @if($bankacc->bank==$bank->id) selected @endif >{{$bank->name}}</option>
						    	@endforeach
						    		
						    	</select>
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">ชื่อบัญชี</label>
						    <div class="col-sm-3">
						    	{{ Form::text('bankaccountname[]',$bankacc->name,array('class'=>'form-control')) }}
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">เลขบัญชี</label>
						    <div class="col-sm-2">
						    	{{ Form::text('bankaccountno[]',$bankacc->accountno,array('class'=>'form-control')) }}
						    </div>
						    <div class=" col-sm-1">
						      <button type="button" class="btn btn-warning" onclick='removeBank({{$bankcount++}});'><span class="glyphicon glyphicon-minus-sign"></span></button>
						    </div>
						  </div>

						</div><!-- end bankinfo -->

							@endforeach
						  @else

						  <div class="bankinfo-0">
						  <div class="form-group">
						    <label for="inputPassword3" class="col-sm-1 control-label">ธนาคาร</label>
						    <div class="col-sm-3">
						    	<select class="form-control" name="bank[]">
						    	@foreach($banks as $bank)
						    		<option value="{{$bank->id}}">{{$bank->name}}</option>
						    	@endforeach
						    		
						    	</select>
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">ชื่อบัญชี</label>
						    <div class="col-sm-3">
						    	{{ Form::text('bankaccountname[]',null,array('class'=>'form-control')) }}
						    </div>
						    <label for="inputPassword3" class="col-sm-1 control-label">เลขบัญชี</label>
						    <div class="col-sm-2">
						    	{{ Form::text('bankaccountno[]',null,array('class'=>'form-control')) }}
						    </div>
						    <div class=" col-sm-1">
						      <button type="button" class="btn btn-warning" onclick='removeBank(0);'><span class="glyphicon glyphicon-minus-sign"></span></button>
						    </div>
						  </div>

						</div><!-- end bankinfo -->
						 @endif 
						 
						</div>
						 <div class="form-group">
						    <div class=" col-sm-12">
						      <button type="submit" class="btn btn-warning">บันทึก</button>
						    </div>
						  </div>
					{{ Form::close() }}
            </div>
        </div>
        		
	</div><!--end shop profile-->
	
  </div>


</div>




@stop