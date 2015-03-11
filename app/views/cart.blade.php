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

$('[data-countdown]').each(function() {
   var $this = $(this), finalDate = $(this).data('countdown');
   $this.countdown(finalDate, function(event) {
     $this.html(event.strftime('เหลือเวลาชำระเงิน %D วัน %H:%M:%S'));
   }).on('finish.countdown', function(event){
        $this.html('เลยเวลาชำระเงิน');
        $this.parent().addClass('danger');
   });

 });


$("#savepayment").submit(function(e)
{
   
    var formObj = $(this);
    var formData = new FormData(this);
    total=0;
    $.ajax(
        {
            url : "{{route('home')}}"+"/savepayment",
            type: "POST",
            mimeType:"multipart/form-data",
            data : formData,
        contentType: false,
        cache: false,
        processData:false,
            success:function(data, textStatus, jqXHR) 
            {
                
                

                if(data==1){
                    $('#paymentModal').modal('hide');
                    $('#orderlist').empty();
                    location.href='{{ route('cart') }}';
                }
                else {
                   /* console.log(data);
                    var obj = jQuery.parseJSON( data );
                    var errs='<ul>';
                    $.each( obj, function( key, value ) {
                      errs=errs+"<li>"+value+"</li>";
                    });
                    errs+="</ul>";
                    $('#error').html("ข้อมูลไม่ถูกต้องกรุณาตรวจสอบข้อมูลของท่าน <div class='alert alert-danger'>"+errs+"</div>");
                    */
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


});

function showpayment(invoiceid){
    $('#orderlist').empty();
    $('#invoiceid').val(invoiceid);
    total=0;
    $.ajax(
        {
            url : '{{route('home')}}'+"/payment/"+invoiceid,
            type: "POST",
            data : null,
        contentType: false,
        dataType: "json",
        cache: false,
        processData:false,
            success:function(data, textStatus, jqXHR) 
            {
                count=0;
                bankcount=0;
                var orderlist = [];
                $.each( data.items, function( key, value ) {
                 
                ++count;
                $('#orderlist').append('<tr><th scope="row">'+count+'</th><td>'+value.item+'</td><td class="align-right">x '+value.amount+'</td> <td class="align-right">'+(value.total)+'</td></tr>');
                total+=value.total;
                

                });

                $.each( data.banks, function( key, value ) {
                    if(bankcount++==0)$('#bankinfo').html("<p>ชื่อบัญชี:"+value.name+", หมายเลขบัญชี: "+value.accountno);
                    $('#selectbank').append("<option value='"+value.bank+"' name='"+value.name+"' accountno='"+value.accountno+"' >"+value.bankname+"</option>");
                });

                console.log(data);
                $('#shopname').html(data.seller.shopname);
                $('#shopaddress').html(data.seller.shopaddress);

                $('#shippingprice').html(data.invoice.shippingprice);
                $('#discount').html(data.invoice.discount);
               

                $('#total').html(parseFloat(data.invoice.shippingprice)-parseFloat(data.invoice.discount)+total);
                $('#paymentModal').modal();
                
   
                
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                bootbox.alert(" <div class='alert alert-danger'>เกิดข้อผิดพลาดในการรับส่งข้อมูล กรุณาลองอีกครั้ง</div>", function() {
                        
                    }); 
            }
        });
}
function changeBankInfo(select){
    $('#bankinfo').html("<p>ชื่อบัญชี:"+select.children('option:selected').attr('name')+", หมายเลขบัญชี: "+select.children('option:selected').attr('accountno'))
}

function cartStatus(instatus,orderid,buyerid){
var msg="";
if(instatus=='cancel')msg="ท่านแน่ใจหรือไม่ว่าต้องการยกเลิกสินค้านี้";
if(instatus=='relist')msg="ท่านแน่ใจหรือไม่ว่าต้องการยกเลิกการจองสินค้า หากสินค้ามีการยกเลิกการจอง สินค้าจะกลับเข้าสู่ สถานะประกาศขายอีกครั้ง";
if(instatus=='reject')msg="กรุณายืนยันการแจ้งลูกค้าให้ทำการชำระเงินใหม่ หรือข้อมูลการชำระเงินไม่ถูกต้อง";
if(instatus=='shipping')msg="กรุณายืนยันการแจ้งสถานะการจัดส่งสินค้า";
if(instatus=='received')msg="กรุณายืนยันการแจ้งสถานะว่าท่านได้รับสินค้าแล้ว";
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
                    location.href='{{ route('cart') }}';
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

<h2>ตะกร้าสินค้า <a class='btn btn-default btn-sm' href=""> ดูทั้งหมด</a> </h2> 
<div class="round cart">
<div class="row">
    
    <div class="col-xs-2"><h4> สินค้าทั้งหมด {{ $orders->count() }} ชิ้น</h4></div>
    <div class="col-xs-4"></div>
    <div class="col-xs-1 text-center">ราคา</div>
    <div class="col-xs-3 ">แจ้งสถานะ</div>
    <div class="col-xs-2 ">สถานะสินค้า</div>
    <div class="col-xs-12 underline"></div>
    
</div>

    @foreach ($orders as $order)
    <?php $img=explode(",",$order->item->filelist) ;
//echo $order->item->owner->id.'/'.$img[0];
      $image=Imagecache::get($order->item->owner->id.'/'.$img[0], 'cart');
    ?>
   
    <div class="row itemrow">
    <div class="col-xs-2 itemphoto"> <img  class="round" src="{{$image->src}}" /></div>
    <div class="col-xs-4 text-left"><h4>{{$order->item->itemname}}</h4><p>{{$order->item->detail}}</p> <p class="metadate">{{$order->thai_cart_date()}}</p></div>
    <div class="col-xs-1 text-right cart-value">{{number_format ( $order->item->price , 2 )}} ฿</div>
    <div class="col-xs-3 cart-value">
    @if($order->status=='invoiced')
    <button onclick="showpayment({{$order->invoiceid}})" class='btn btn-info btn-xs' href=""><i class="fa fa-money"></i> แจ้งโอนเงิน</button> 
    @elseif($order->status=='shipping')
    <button class='btn btn-success btn-xs' onclick="cartStatus('received',{{$order->id}},{{$order->buyer->id}});"><i class="fa fa-check"></i> ได้รับสินค้าแล้ว</button> 
    @else
    <p>ยังทำรายการไม่ได้ขณะนี้</p>
    @endif
    </div>
    <div class="col-xs-2 cart-value"><span>{{$order->thai_status()}}</span>
        @if($order->status=='invoiced')
    <div data-countdown="{{ $order->expire_date() }}"></div>
        @endif
    </div>
    
    <div class="row">
        <div class="col-xs-12 underline"></div>
    </div>
    @endforeach
  
</div>


<div class="modal fade" id="paymentModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        {{ Form::open( array( 'id'=>'savepayment','role'=>'form','class'=>'','method'=>'post')) }}
      <div class="modal-header align-center" >
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">ใบแจ้งการชำระเงิน</h3>

        <p>ชำระให้กับ : <span id="shopname">ชื่อร้าน </p>
        <p>ที่อยู่ร้าน : <span id="shopaddress">ที่อยู่ร้าน</span></p>
      </div>
      <div class="modal-body">
    <div class="row">
        <div class="col-xs-6">
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
        <p>ค่าจัดส่ง <span id="shippingprice"></span> บาท ส่วนลด <span id="discount"></span> บาท</p>
        <h3>รวมทั้งหมด <span id="total"></span> บาท</h3>
        
        </div>
        <div class="col-xs-6">
       <div id="error"></div>
  <div class="form-group">
    <label for="exampleInputEmail1">ชื่อธนาคาร</label>
        <select id="selectbank" class="form-control" name="paid_bank" onchange="changeBankInfo($(this))">
        </select>
    <p class="grey" style="margin-top: 8px" id="bankinfo"></p>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">จำนวนเงิน</label>
    <input type="text" name="paid_amount" class="form-control" id="exampleInputPassword1" placeholder="จำนวนเงินโอน">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">วันที่ / เวลาโอน</label>
    <input type="text" name="paid_time" class="form-control" id="exampleInputPassword1" placeholder="วันที่/เวลาโอน">
  </div>
  <div class="form-group">
    <label for="exampleInputFile">อัพโหลดไฟล์</label>
    <input type="file" name="paid_file" id="exampleInputFile">
    <p class="help-block">ไฟล์สลิปหรือหลักฐานการโอนเงิน (ไฟล์รูปหรือ pdf)</p>
  </div>
  <input type="hidden" name="invoiceid" id="invoiceid" />
  

     
        </div>
    </div><!--end row -->
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">แจ้งชำระเงิน</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
        
      </div>
 </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop
