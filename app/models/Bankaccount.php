<?php


class Bankaccount extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	static $unguarded = true;
	
	public $timestamps=false;

	public function bank()
	{
		return  $this->belongsTo('Bank','bank')->first();
	}
	public function bankname(){
		return $this->belongsTo('Bank','bank')->first()->name;
	}

}

?>