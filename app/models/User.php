<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	static $unguarded = true;
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
	public function comments()
    {
        return $this->morphMany('Comment', 'authorable');
    }
    public function province()
    {
    	return $this->belongsTo('Province','province')->first();
    }
    public function bankaccounts(){
    	return $this->hasMany('Bankaccount','userid');
    }
    public function userbadges(){
    	//dd($this->hasMany('Userbadge','user_id'));
    	return $this->hasMany('Userbadge','user_id');
    }

    public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}
	public function getcartcount(){
		return Order::where('userid','=',$this->id)->count();

	}
	public function getshopcount(){
		return Item::where('seller','=',$this->id)->count();
	}
	public function start_sell_thai_date(){
		$datetime=$this->shopstart;

  		$phpdate = strtotime( $datetime );
		$date = date( 'j', $phpdate );
		$year = date( 'Y', $phpdate );
		$m=date('m',$phpdate);
		$time=date('H:i',$phpdate);

	 $year = intval($year)+543;
	 
	 $month = array('01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน','07'=>'กรกฏาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤษจิกายน','12'=>'ธันวาคม');
	 //$month =  array('01'=>'ม.ค.','02'=>'ก.พ.','03'=>'มี.ค.','04'=>'เม.ย.','05'=>'พ.ค.','06'=>'มิ.ย.','07'=>'ก.ค.','08'=>'ส.ค.','09'=>'ก.ย.','10'=>'ต.ค.','11'=>'พ.ย.','12'=>'ธ.ค.');
	
	
	 // return $d.' '.$month[$m].' '.$Y;
	
	  return $date.' '.$month[$m]." ".$year;
	}
	public function getallbanks(){
		//Debugbar::info()
		return Bankaccount::where('userid','=',$this->id)->get();
	}

}