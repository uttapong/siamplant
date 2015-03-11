<?php

class Content extends Eloquent{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	static $unguarded = true;
	
	public function add()
	{
		
	}
	

	public function shorten(){
		return iconv_substr(strip_tags($this->content), 0,200, "UTF-8");
	}
	public function upload()
    {

        return $this->hasMany('Upload','uploadable_id');
    }
    public function filename(){
    	if(isset($this->upload()->first()->filename))
    	return public_path().'/uploads/contents/'.$this->id."/".$this->upload()->first()->filename;
    	else return null;
    }
    public function getfile(){
    	if(isset($this->upload()->first()->filename)){
	    	$filename=public_path().'/uploads/contents/'.$this->id."/".$this->upload()->first()->filename;

	    	$newfile=public_path().'/uploads/contents/'.$this->id."/".'title_'.$this->upload()->first()->filename;
	    	if(!file_exists ( $newfile))
	    	$url=Image::make($filename)->grab(300, 100)->save($newfile);
	    	return Config::get('app.public_url').'uploads/contents/'.$this->id."/title_".$this->upload()->first()->filename;
    	}
    	else return null;
    }
    
    public function getoriginalfile(){
    	if(isset($this->upload()->first()->filename)){
	    	$filename=public_path().'/uploads/contents/'.$this->id."/".$this->upload()->first()->filename;

	    	
	    	return Config::get('app.public_url').'uploads/contents/'.$this->id."/".$this->upload()->first()->filename;
    	}
    	else return null;
    }

    public function comments()
    {
    	//dd($this->morphMany('Comment', 'commentable'));
        return $this->hasMany('Comment', 'content');
    }
    public function user()
    {
        return $this->belongsTo('User','authorable_id');
    }

}

?>