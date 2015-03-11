<?php


class Badge extends Eloquent {

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
	

	public function upload()
    {

        return $this->hasMany('Upload','uploadable_id');
    }
    public function filename(){
    	if(isset($this->upload()->first()->filename))
    	return '/uploads/posts/'.$this->id."/".$this->upload()->first()->filename;
    	else return null;
    }
    public function userbadge(){
    	return $this->hasMany('Userbadge','badge_id');
    }

}

?>