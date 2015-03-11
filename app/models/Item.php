<?php
use LaravelBook\Ardent\Ardent;

class Item extends Ardent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	static $unguarded = true;
	
	public $timestamps=false;
	public static $rules= array(
		'itemname' => 'required',
		 'cat' => 'required',
		 'price' => 'required|numeric',
		 'amount'=>'required|numeric',
		 'shippingprice'=>'required',
		 'detail'=>'required',
		 'filelist'=>'required',
		 'seller'=>'required'
		 );
	public function total(){
		return $this->price+$this->shippingprice;
	}
	public function owner()
	{	
		return $this->belongsTo('User','seller');
	}
	public function seller_username(){

		return User::find($this->seller)->username;
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
		    case 'relist':
		        return 'หลุดจอง';
		        break;
		    default :
		    return 'no-status'; break;
		}
	}
	public function thai_date(){
  
  		$datetime=$this->date_added;

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

	public function thai_date_single(){
  
  		$datetime=$this->date_added;

  		$phpdate = strtotime( $datetime );
		$date = date( 'j', $phpdate );
		$year = date( 'Y', $phpdate );
		$m=date('m',$phpdate);
		$time=date('H:i',$phpdate);

	 $year = intval($year)+543;
	 
	 $month = array('01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน','07'=>'กรกฏาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤษจิกายน','12'=>'ธันวาคม');
	 //$month =  array('01'=>'ม.ค.','02'=>'ก.พ.','03'=>'มี.ค.','04'=>'เม.ย.','05'=>'พ.ค.','06'=>'มิ.ย.','07'=>'ก.ค.','08'=>'ส.ค.','09'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');
	
	
	 // return $d.' '.$month[$m].' '.$Y;
	
	  return $date.' '.$month[$m]." ".$year. " เวลา ".$time . " น." ;
	
	}

	public function getshippingprice(){
		$price=Shipping::find($this->shippingprice);
		return $price->price;
	}
	public function getimage($size){
		$filelist=explode(",",$this->filelist);
		$img=$filelist[0];
		//Debugbar::info(public_path().'/uploads/'.$this->owner->id.'/'.$img);
		//die('public/uploads/'.$this->owner->id.'/'.$img);
		$ext=pathinfo($img, PATHINFO_EXTENSION);
		$oldfilename=pathinfo($img, PATHINFO_FILENAME);
		$new_filename=$oldfilename."_".$size.".".$ext;
		header('Content-Type: image/jpg');
		$image=Image::make(public_path().'/uploads/'.$this->owner->id.'/'.$img);
	
		switch($size){
			case 'market':
				return (string)$image->resize(180, null, function ($constraint) { $constraint->aspectRatio(); })->save($new_filename);
				break;
			case 'cart':
				return (string)$image->fit(100,60, function ($constraint) { $constraint->aspectRatio(); })->encode($new_filename);
				break;
			case 'single':
				return (string)$image->fit(240,180, function ($constraint) { $constraint->aspectRatio(); })->encode($new_filename);
				break;
			default :
				return (string)$image->fit(240,180, function ($constraint) { $constraint->aspectRatio(); })->encode($new_filename);
				break;	
		}
		
	}
	public function category(){
		return $this->belongsTo('Category','cat');
	}
	
	
	
}

?>