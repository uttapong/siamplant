@extends('layouts.main')
@section('title')
siamplant.com ตลาดต้นไม้หน้าแรก
@stop
@section('script')
<script src="{{ asset('assets/js/packery.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>
    <script>
$( document ).ready(function( ) {
var $container = $('#container');
// init
$container.packery({
  itemSelector: '.item',
  gutter: 10
});


$("#saveinvoice").submit(function(e)
{
   
    var formObj = $(this);
    var formURL = formObj.attr("action");
    var formData = new FormData(this);
    total=0;
    $.ajax(
        {
            url : formURL,
            type: "POST",
            data : formData,
        contentType: false,
        cache: false,
        processData:false,
            success:function(data, textStatus, jqXHR) 
            {
                console.log(data);
                $('#invModal').modal('hide');

                if(data==1){

                    location.href='{{ route('order') }}';
                    }
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
         $('#orderlist').empty();
});



});


var total=0;
function showInvoice(buyerid){
    $('#orderlist').empty();
    total=0;
    $.ajax(
        {
            url : '{{route('home')}}'+"/order/invoice/"+buyerid,
            type: "POST",
            data : null,
        contentType: false,
        dataType: "json",
        cache: false,
        processData:false,
            success:function(data, textStatus, jqXHR) 
            {
                count=0;
                var orderlist = [];
                $.each( data, function( key, value ) {
                 


                    ++count;
                $('#orderlist').append('<tr><th scope="row">'+count+'</th><td>'+value.item+'</td><td class="align-right">x '+value.amount+'</td> <td class="align-right">'+(value.total)+'</td></tr>');
                total+=value.total;

                orderlist.push(value.orderid);

                $('#buyerid').val(buyerid);
                $('#sellerid').val({{Auth::user()->id}});

                });
                $('#orders').val(JSON.stringify(orderlist));

                $('#total').html(total);
                $('#invModal').modal();
                
   
                
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                bootbox.alert(" <div class='alert alert-danger'>เกิดข้อผิดพลาดในการรับส่งข้อมูล กรุณาลองอีกครั้ง</div>", function() {
                        
                    }); 
            }
        });
}


function findtotal(){
    if(!$('#ship').val())$('#ship').val(0);
    if(!$('#discount').val())$('#discount').val(0);

        ship=parseInt($('#ship').val());
    discount=parseInt($('#discount').val());
    texttotal=total+ship-discount;
    if((texttotal)<0)$('#total').html(0);
    else $('#total').html(texttotal);
}
function cartStatus(instatus,orderid,buyerid){
var msg="";
if(instatus=='cancel')msg="ท่านแน่ใจหรือไม่ว่าต้องการยกเลิกสินค้านี้";
if(instatus=='relist')msg="ท่านแน่ใจหรือไม่ว่าต้องการยกเลิกการจองสินค้า หากสินค้ามีการยกเลิกการจอง สินค้าจะกลับเข้าสู่ สถานะประกาศขายอีกครั้ง";
if(instatus=='reject')msg="กรุณายืนยันการแจ้งลูกค้าให้ทำการชำระเงินใหม่ หรือข้อมูลการชำระเงินไม่ถูกต้อง";
if(instatus=='shipping')msg="กรุณายืนยันการแจ้งสถานะการจัดส่งสินค้า";
bootbox.confirm(msg, function(result) {
    if(result){
        $.ajax(
        {
            url : '{{route('home')}}'+"/orderstatus/"+orderid,
            type: "POST",
            data : {status:instatus,buyerid:buyerid},
            success:function(data, textStatus, jqXHR) 
            {
                if(data){
                    location.href='{{ route('order') }}';
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                bootbox.alert(" <div class='alert alert-danger'>เกิดข้อผิดพลาดในการรับส่งข้อมูล กรุณาลองอีกครั้ง</div>", function() {
                        
                    }); 
            }
        });
    }
});

}

</script>
@stop
@section('content')
@if(Session::has('message'))
    {{ Session::get('message') }}
@endif

<h2>รายการสั่งสินค้า <a class='btn btn-default btn-sm' href=""> ดูทั้งหมด</a> </h2> 
<div class="round cart">
<div class="row">
    
    <div class="col-xs-1"></div>
    <div class="col-xs-3"><h4> สินค้าสั่งซื้อทั้งหมด {{ $numcart }} รายการ</h4></div>
     <div class="col-xs-3">ผู้ซื้อ</div>
    <div class="col-xs-1 text-center">ราคา</div>
    <div class="col-xs-2 ">แจ้งสถานะ</div>
    <div class="col-xs-2 ">สถานะสินค้า</div>
    <div class="col-xs-12 underline"></div>
    
</div>

    @foreach ($orders as $order)
    <?php $img=explode(",",$order->item->filelist); 
    $image=Imagecache::get($order->item->owner->id.'/'.$img[0], 'cart');
    ?>
   
    <div class="row itemrow">
    <div class="col-xs-1 itemphoto"> <img class="round" src="{{$image->src}}" /></div>
    <div class="col-xs-3 text-left"><h4>{{$order->item->itemname}}</h4><p>{{$order->item->detail}}</p> <p class="metadate">{{$order->thai_cart_date()}}</p></div>
    <div class="col-xs-3 address">
      <h5><a href="{{route('profile',array($order->buyer->username))}}">{{$order->buyer->firstname}} {{$order->buyer->lastname}} @if($order->buyer->displayname) ({{$order->buyer->displayname}}) @endif </a></h5>
      <p class="grey"><i class="fa fa-home"></i> {{$order->buyer->address}} {{$order->buyer->province}}</p>
        <p class="grey"><i class="fa fa-envelope-o"></i><a href="mailto:{{$order->buyer->shopemail}}">  {{$order->buyer->email}}</a></p>
        <p class="grey"><i class="fa fa-phone-square"></i>  {{$order->buyer->phone}}, <i class="fa fa-mobile"></i>  {{$order->buyer->phone}}</p>
    </div>
    <div class="col-xs-1 text-right cart-value">{{number_format ( $order->item->price , 2 )}} ฿</div>
    <div class="col-xs-2 cart-value">
    @if($order->status=='cart'|| $order->status=='relist')
    <p><button class='btn btn-info btn-xs' onclick="showInvoice({{$order->userid}});"><i class="fa fa-fax"></i> ส่งใบแจ้งราคา</button></p>
    <p><button class='btn btn-danger btn-xs' onclick="cartStatus('relist',{{$order->id}},{{$order->buyer->id}});"> <i class="fa fa-trash-o fa-lg"></i> ยกเลิกจอง</button></p>  
    @elseif($order->status=='invoiced')
    <p><button class='btn btn-info btn-xs' onclick="showInvoice({{$order->userid}});"><i class="fa fa-fax"></i> ส่งใบแจ้งราคาใหม่</button></p>
    <p><button class='btn btn-danger btn-xs' onclick="cartStatus('relist',{{$order->id}},{{$order->buyer->id}});"> <i class="fa fa-trash-o fa-lg"></i> ยกเลิกจอง</button></p> 
    @elseif($order->status=='expired')
    <p><button class='btn btn-info btn-xs' onclick="showInvoice({{$order->userid}});"><i class="fa fa-fax"></i> ส่งใบแจ้งราคา</button></p> 
    <p><button class='btn btn-danger btn-xs' onclick="cartStatus('relist',{{$order->id}},{{$order->buyer->id}});"> <i class="fa fa-trash-o fa-lg"></i> ยกเลิกจอง</button></p> 
    @elseif($order->status=='process')
    <p><button class='btn btn-info btn-xs' onclick="showInvoice({{$order->userid}});"><i class="fa fa-fax"></i> ส่งใบแจ้งราคาใหม่</button></p>
    <p><button class='btn btn-warning btn-xs' onclick="cartStatus('reject',{{$order->id}},{{$order->buyer->id}});"><i class="fa fa-exclamation-circle"></i> ข้อมูลชำระเงินไม่ถูกต้อง</button></p> 
    <p><button class='btn btn-success btn-xs' onclick="cartStatus('shipping',{{$order->id}},{{$order->buyer->id}});"><i class="fa fa-truck"></i> กำลังจัดส่ง</button></p>
    @elseif($order->status=='reject')
    <p><button class='btn btn-info btn-xs' onclick="showInvoice({{$order->userid}});"><i class="fa fa-fax"></i> ส่งใบแจ้งราคาใหม่</button></p>
    <p><button class='btn btn-danger btn-xs' onclick="cartStatus('relist',{{$order->id}},{{$order->buyer->id}});"> <i class="fa fa-trash-o fa-lg"></i> ยกเลิกจอง</button></p>
    @else
    <p>ยังทำรายการไม่ได้ขณะนี้</p>
    @endif
    </div>
    <div class="col-xs-2 cart-value">{{$order->thai_status()}}</div>
    </div>
    <div class="row">
        <div class="col-xs-2"> </div>
        <div class="col-xs-10 underline"></div>
    </div>
    @endforeach
  
</div>

<div class="modal fade" id="invModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header align-center" >
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">ใบแจ้งราคาสินค้า</h3>
        <h1>{{Auth::user()->shopname}}</h1>
        <p>{{Auth::user()->shopaddress}} {{Auth::user()->shopprovince}}</p>
      </div>

       {{ Form::open( array('route' => 'saveinvoice', 'id'=>'saveinvoice','role'=>'form','class'=>'form-inline','method'=>'post')) }}
      <div class="modal-body">
        <div id="invoicebody">
            <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>สินค้า</th>
          <th>จำนวน</th>
          <th>รวม</th>
        </tr>
      </thead>
      <tbody id="orderlist">
        
      </tbody>
    </table>
        </div>
        <hr />
     
       
          <div class="form-group">
            <label for="exampleInputName2">ส่วนลด </label>
            <input type="number" class="form-control" id="discount"  name="discount" onchange="findtotal()" onkeyup="findtotal()" value="0"> บาท 
          </div>
          <div class="form-group">
          &nbsp;
          </div>
          <div class="form-group">
            <label for="exampleInputEmail2">ค่าจัดส่ง</label>
            <input type="number" class="form-control" id="ship" name="shippingprice" onchange="findtotal()" onkeyup="findtotal()" placeholder="0"> บาท
          </div>
          <hr />
         <div class="align-right">
          
          <h2>รวมเงิน <span id="total">0</span> บาท</h2>
          </div>
          <input type="hidden" id="orders" name="orders" />
          <input type="hidden" id="buyerid"  name="buyerid" />
          <input type="hidden" id="sellerid" name="sellerid"/>
        
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning">ส่งใบแจ้งราคา</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
        
      </div>
      </form>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop
