<?php
use LaravelBook\Ardent\Ardent;

class Invoice extends Ardent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	static $unguarded = true;
	
	public $timestamps=false;
	public static $rules= array(
		'orders' => 'required',
		 'sellerid' => 'required',
		 'buyerid' => 'required'
		 );
	
	public function buyer()
	{	
		return $this->belongsTo('User','buyerid');
	}
	public function seller(){

		return $this->belongsTo('User','sellerid');
	}
	public function status_thai(){
		//dd($this->status);
		switch ($this->status) {
		    case 'cart':
		        return 'จองแล้ว';
		        break;
		    case 'ending':
		        return 'ใกล้เวลา';
		        break;
			case 'recommended':
		        return 'แนะนำ';
		        break;
		    default :
		    return 'no-status'; break;
		}
	}
	public function thai_date(){
  
  		$datetime=$this->created_at;

  		$phpdate = strtotime( $datetime );
		$date = date( 'j', $phpdate );
		$year = date( 'Y', $phpdate );
		$m=date('m',$phpdate);
		$time=date('H:i',$phpdate);

	 $year = intval($year)+543;
	 
	 //$month = array('01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน','07'=>'กรกฏาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤษจิกายน','12'=>'ธันวาคม'),
	 $month =  array('01'=>'ม.ค.','02'=>'ก.พ.','03'=>'มี.ค.','04'=>'เม.ย.','05'=>'พ.ค.','06'=>'มิ.ย.','07'=>'ก.ค.','08'=>'ส.ค.','09'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');
	
	
	 // return $d.' '.$month[$m].' '.$Y;
	
	  return $time."<br />".$date.' '.$month[$m]." ".$year;
	
	}
	public function allorders(){
		return $this->hasMany('Order','invoiceid');
	}

	
	
	
	
}

?>