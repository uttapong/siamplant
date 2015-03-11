<?php


class Userbadge extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	static $unguarded = true;
	

	public function add()
	{
		
	}
	

	public function upload()
    {

        return $this->hasMany('Upload','uploadable_id');
    }
    public function filename(){
    	if(isset($this->upload()->first()->filename))
    	return '/uploads/posts/'.$this->id."/".$this->upload()->first()->filename;
    	else return null;
    }
    public function badge(){
    	return $this->belongsTo('Badge');
    }
    public function getBadge(){
    	return Badge::find($this->badge_id)->first();
    }

}

?>