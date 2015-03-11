<?php
use Carbon\Carbon;

class Order extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	static $unguarded = true;
	
	public $timestamps=false;

	public function add()
	{
		
	}
	public function item(){
		return $this->belongsTo('Item','itemid');
	}
	public function buyer(){
		return $this->belongsTo('User','userid');
	}
	public function thai_cart_date(){
  
  		$datetime=$this->order_dated;

  		$phpdate = strtotime( $datetime );
		$date = date( 'j', $phpdate );
		$year = date( 'Y', $phpdate );
		$m=date('m',$phpdate);
		$time=date('H:i',$phpdate);

	 $year = intval($year)+543;
	 
	 //$month = array('01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน','07'=>'กรกฏาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤษจิกายน','12'=>'ธันวาคม'),
	 $month =  array('01'=>'ม.ค.','02'=>'ก.พ.','03'=>'มี.ค.','04'=>'เม.ย.','05'=>'พ.ค.','06'=>'มิ.ย.','07'=>'ก.ค.','08'=>'ส.ค.','09'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');
	
	
	 // return $d.' '.$month[$m].' '.$Y;
	
	  return $date.' '.$month[$m]." ".$year." เวลา ".$time." น.";
	
	}
	public function thai_status(){
		switch($this->status){
			case 'cart':
				return "รอใบแจ้งหนี้";
				break;
			case 'invoiced':
				return "รอชำระเงิน";
				break;
			case 'process':
				return "กำลังตรวจสอบ";
				break;
			case 'shipping':
				return "อยู่ระหว่างจัดส่งสินค้า";
				break;
			case 'received':
				return "ได้รับสินค้า";
				break;
			case 'rated':
				return "ได้รับสินค้า";
				break;
			case 'cancel':
				return "ยกเลิกสินค้า";
				break;
			case 'reject':
				return "การชำระเงินไม่ถูกต้อง";
				break;
			case 'reject':
				return "นำมาขายใหม่";
				break;
			default :
				return "ไม่พบ";
				break;	
		}
	}
	public function expire_date(){
		//die($this->order_dated);
		//die(Carbon::parse($this->order_dated)->addDays(2)->format('Y/n/j H:i:s'));
		$expired_date=3;
		//return Carbon::parse($this->order_dated)->addDays($expired_date)->diffInseconds(Carbon::now('Asia/Bangkok'),false);
		//return Carbon::now('Asia/Bangkok')->format('Y/n/j H:i:s');
		//return (Carbon::parse($this->order_dated)->addDays($expired_date)->diffInSeconds(Carbon::now('Asia/Bangkok')) );
		//return Carbon::parse($this->order_dated)->addDays($expired_date)->format('Y/n/j H:i:s');

		//if(Carbon::parse($this->order_dated)->addDays($expired_date)->lte(Carbon::now('Asia/Bangkok'),false))return "expired";
		return Carbon::parse($this->order_dated)->addDays($expired_date)->format('Y/n/j H:i:s');
	}
	public function invoice(){
		return $this->belongsTo('Invoice','invoiceid');
	}
	

}

?>