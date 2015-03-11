<?php


class Comment extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	static $unguarded = true;
	
	public $timestamps=true;

	public function author()
	{
		return $this->belongsTo('User','author');
	}

}

?>