<?php
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	protected $layout = 'layouts.main';
	
	public function login()
	{
		return View::make('login');
	}
	public function profile($username=null){
		
		if(!$username&&!Auth::user())return "You can not access this page.";
		if(!$username)$data['user']=Auth::user();
		else $data['user']=User::where('username','=',$username)->first();
		//dd($data['user']);
		
		
		return View::make('profile',$data);
	}
	
	public function signup(){

		$data=array();
		$data['provinces']=Province::all();
		return View::make('signup',$data);
	}
	public function add(){
	//dd(implode(',',Input::get('openday')));
		//dd(Input::all());
		
		$new_user = Input::except('_token','password2');
		
		 // let's setup some rules for our new data
		 // I'm sure you can come up with better ones
		$rules = array(
		'username' => 'required|unique:users',
		 'password' => 'required',
		 'firstname' => 'required',
		 'lastname'=>'required',
		 'email'=>'required'
		 );
		 // make the validator
		$v = Validator::make($new_user, $rules);
		 if ( $v->fails() )
		 {
		 // redirect back to the form with
		 // errors, input and our currently
		 // logged in user
		 	return $v->messages();
		 	//return json_encode(array('result'=>0,'message'=>$v->messages()));
		 
		 }
		 else{
		 	$new_user['password']=Hash::make(Input::get('password'));

		 }
		 // create the new post
		$user = new User($new_user);
		$result=$user->save();
		$this->activation_email($user->id);
		if($result){
			return "<div class='alert alert-success'><strong>การลงทะเบียนเสร็จสมบูรณ์</strong> ท่านได้ทำการลงทะเบียนกับ siamplant.com เสร็จสมบูรณ์แล้ว
				กรุณาตรวจสอบอีเมล์ของเท่านที่ใช้ในการสมัคร เพื่อยืนยันการสมัครและเปิดใช้งานบัญชี</div>";

		}
		

	}
	public function addfav(){
		$user=Auth::user();
		$update_data=array();
		$allitem=unserialize($user->favitems);
		$id=Input::get('itemid');
		if(!$allitem)$allitem=array();
		array_push($allitem,$id);
		$update_data['favitems']=serialize($allitem);
		
		
		return strval($user->update($update_data));
	}
	public function removefav(){
		//return "xxx";
		$user=Auth::user();
		$update_data=array();
		$allitem=unserialize($user->favitems);
		//return strval($allitem);
		$id=Input::get('itemid');
		$allitem=array_diff($allitem, [$id]);

	
		$update_data['favitems']=serialize($allitem);
		
		
		return strval($user->update($update_data));
	}
	public function signin(){
		// get POST data
	    $userdata = array(
	        'username'      => Input::get('username'),
	        'password'      => Input::get('password')
	    );

	    

	    if ( Auth::attempt($userdata) )
	    {	
	    	if(Auth::user()->status==0){
		    	$msg= "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign'></span> <strong>ท่านยังไม่ได้ยืนยันการลงทะเบียน</strong>  กรุณาตรวจสอบการลงทะเบียนจากอีเมล์ที่ท่านได้ทำการสมัครไว้</div>";
				Session::flash('message', $msg);
				Auth::logout();
				return View::make('login');

		    }

	        return Redirect::to('/');
	    }
	    else
	    {
	        // auth failure! lets go back to the login
	        return Redirect::to('login')
	            ->with('login_errors', true);
	        // pass any error notification you want
	        // i like to do it this way :)
	    }
	}

	public function api_signin(){
		// get POST data
	    $userdata = array(
	        'username'      => Input::get('username'),
	        'password'      => Input::get('password')
	    );

	    

	    if ( Auth::attempt($userdata) )
	    {	
	    	if(Auth::user()->status==0){
		    	
				Auth::logout();
				return json_encode(false);

		    }
		    $token = JWTAuth::fromUser(Auth::user());
		    return $token;
	        return json_encode($token);
	    }
	    else
	    {
	        // auth failure! lets go back to the login
	        return json_encode('login error');
	        // pass any error notification you want
	        // i like to do it this way :)
	    }
	}

	public function api_getuser(){
		// get POST data
		JWTAuth::parseToken();
		if(Auth::user()){
			$user = JWTAuth::parseToken()->toUser();
	    	return json_encode($user);
	    }else{
	    	return json_encode(null);
	    }
	}
	public function signinfb(){

		$facebook = new Facebook(array(
		  'appId'  => '612396988819496',
		  'secret' => 'e2ab814a26b255a01ee2bc59fe8f3995',
		));

				// Get User ID
		$user = $facebook->getUser();
		//dd($user);
		
		if ($user) {
		  try {
		    // Proceed knowing you have a logged in user who's authenticated.
		    $user_profile = $facebook->api('/me');
		    
		    
		    //check if user has registered
		    $dbuser=User::where('fb_id','=',$user_profile['id'])->first();
		    //dd($dbuser);
		    if($dbuser){
			    Auth::login($dbuser);
		    }
		    else{
			    $new_user = array(
				 'username' => $user_profile['username'],
				 'firstname' => $user_profile['first_name'],
				 'lastname' => $user_profile['last_name'],
				 'fb_id'=>$user_profile['id'],
				 'link'=>$user_profile['link'],
				 'email'=>$user_profile['email']
				);
				
				$im=file_get_contents('https://graph.facebook.com/'.$user_profile['id'].'/picture?type=large');
						
		    	  
				if($im){
					$imdata = base64_encode($im);
			    	$imdata="data:image/jpg;base64,".$imdata;
					$new_user['picture']=$imdata;
				}
				//die($imdata);
				$inserted_user = new User($new_user);
				
				
				if($inserted_user->save()){
					Auth::login($inserted_user);
					Redirect::to('/');
				}
			    
		    }
		  } catch (FacebookApiException $e) {
		    error_log($e);
		    $user = null;
		  }
		}
		
		 //dd($statusUrl);
		// Login or logout url will be needed depending on current user state.
		if ($user) {
		  $logoutUrl = $facebook->getLogoutUrl();
		  //dd($logoutUrl);
		} else {
		  $statusUrl = $facebook->getLoginStatusUrl();
		  $loginUrl = $facebook->getLoginUrl();
		   //dd($loginUrl);
		  return header('Location: '.$loginUrl);
		  //dd($loginUrl);
		}

		// This call will always work since we are fetching public data.
		$naitik = $facebook->api('/naitik');

		return Redirect::to('/');
	}
	public function fbconnect(){

		$facebook = new Facebook(array(
		  'appId'  => '612396988819496',
		  'secret' => 'e2ab814a26b255a01ee2bc59fe8f3995',
		));

				// Get User ID
		$user = $facebook->getUser();
		//dd($user);
		
		if ($user) {
		  try {
		    // Proceed knowing you have a logged in user who's authenticated.
		    $user_profile = $facebook->api('/me');
		   
		    $user=Auth::user();
		    $fullname=$user_profile['first_name']." ".$user_profile['last_name'];
		    
		    $ret_obj = $facebook->api('/me/feed', 'POST',
                                    array(
                                      'link' => 'www.siamplant.com',
                                      'message' => $fullname.' ได้สมัครสมาชิกตลาดต้นไม้ siamplant.com'
                                 ));
		    
		    
		   
			    
				$im=file_get_contents('https://graph.facebook.com/'.$user_profile['id'].'/picture?type=square');
						
		    	  
				if($im){
					$imdata = base64_encode($im);
			    	$imdata="data:image/jpg;base64,".$imdata;
					$user->picture=$imdata;
				}
				//die($imdata);
				
				$user->fb_id=$user_profile['id'];
				$user->link=$user_profile['link'];

				if($user->save()){
					Redirect::to('/');
				}
			    
		   
		  } catch (FacebookApiException $e) {
		    dd($e);
		    $user = null;
		  }
		}
		
		 //dd($statusUrl);
		// Login or logout url will be needed depending on current user state.
		if ($user) {
		  $logoutUrl = $facebook->getLogoutUrl();
		  //dd($logoutUrl);
		} else {
		  $statusUrl = $facebook->getLoginStatusUrl();
		  $loginUrl = $facebook->getLoginUrl();
		   //dd($loginUrl);
		  return header('Location: '.$loginUrl);
		  //dd($loginUrl);
		}

		// This call will always work since we are fetching public data.
		$naitik = $facebook->api('/naitik');
		$msg= "<div class='alert alert-success'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<i class='fa fa-facebook-square fa-lg'></i> การเชื่อมต่อกับ Facebook เสร็จสมบูรณ์ &nbsp;</div>";
		Session::flash('message', $msg);
		
		
		return Redirect::to('/');
	}
	public function edit($id){
		$data=array();
		$data['provinces']=Province::all();
		$data['banks']=Bank::all();
		$data['amphurs']=Amphur::all();
		$data['user'] = User::where('id',$id)->first();
		return View::make('useredit',$data);

	}

	public function update($id){
	//dd(implode(',',Input::get('openday')));
		//dd(Input::all());
	

		$update_user = Input::except('_token','password','password2','picture');
		if(Input::file('picture'))
		$update_user['picture']=Input::file('picture');
		$user=User::find($id);
		
		 // I'm sure you can come up with better ones
		$rules = array(
		 'firstname' => 'required',
		 'lastname'=>'required',
		 'email'=>'required|email',
		 'picture'=>'mimes:jpeg,gif,png|max:400',
		 'displayname'=>'unique:users,displayname,'.$user->id
		 );

		 // make the validator
		$v = Validator::make($update_user, $rules);
		 if ( $v->fails() )
		 {
		 	return $v->messages();;
		 
		 }
		 else{
		 	

		 	if(Input::get('password')&&Input::get('password')!='')$update_user['password']=Hash::make(Input::get('password'));

		 	//create base64 encoded for avatar picture
		 	if(Input::file('picture'))
		 	{
		 		$file = Input::file('picture');
			    $extension = File::extension($file->getClientOriginalName());
			    $directory = 'uploads/'. Auth::user()->id.'/profile';
			    //$filename =  File::name($file->getClientOriginalName()).".".$extension;
			     $filename=md5(rand()).".".strtolower($extension);
		 		Input::file('picture')->move($directory, $filename);
				$update_user['picture']=$filename;
			}

			
		 }
		 // create the new post
		return strval($user->update($update_user));
	}
	public function shopupdate($id){
	//dd(implode(',',Input::get('openday')));
		//dd(Input::all());


		$update_user = Input::except('_token','shoppicture','bank','bankaccountname','bankaccountno');
		if(Input::file('shoppicture'))
		$update_user['shoppicture']=Input::file('shoppicture');
		$user=User::find($id);
		
		 // I'm sure you can come up with better ones
		$rules = array(
		 'shopname' => 'required|max:40|unique:users,shopname,'.$user->id,
		 'shopdetail'=>'required|max:200',
		 'shopemail'=>'required|email',
		 'shopaddress'=>'required',
		 'picture'=>'mimes:jpeg,gif,png|max:1024',
		 );

		 // make the validator
		$v = Validator::make($update_user, $rules);
		 if ( $v->fails() )
		 {
		 	return $v->messages();;
		 
		 }
		 else{
		 	//remove and insert new bank account
		 	$bank_arr=Input::get('bank');
		 	if($bank_arr){
		 		$bankaccountname_arr=Input::get('bankaccountname');
		 		$bankaccountno_arr=Input::get('bankaccountno');
		 		Bankaccount::where('userid', '=',$user->id)->delete();
		 		for($i=0;$i<count($bank_arr);$i++){
		 			$bankaccount=new Bankaccount(array('bank'=>$bank_arr[$i],'name'=>$bankaccountname_arr[$i],'accountno'=>$bankaccountno_arr[$i],'userid'=>$user->id));
		 			$bankaccount->save();
		 		}
		 	}
		 	//print_r(Input::get('bank'));
		 	//die();
		 	//create base64 encoded for avatar picture
		 	if(Input::file('shoppicture'))
		 	{
		 		$file = Input::file('shoppicture');
			    $extension = File::extension($file->getClientOriginalName());
			    $directory = 'uploads/'. Auth::user()->id.'/profile';
			    //$filename =  File::name($file->getClientOriginalName()).".".$extension;
			     $filename=md5(rand()).".".strtolower($extension);
		 		Input::file('shoppicture')->move($directory, $filename);
				$update_user['shoppicture']=$filename;
			}

			
		 }
		 // create the new post
		return strval($user->update($update_user));
	}
	public function typeahead($kw){
		$users = DB::select("select * from users where concat(firstname,lastname,username) like '%{$kw}%'", array());
		//$users=User::whereRaw("concat(firstname,lastname,username) like '%?%'", array($kw))->get();
		return json_encode($users);

	}
	public function activation_email($uid){

		$user=User::find($uid);
		//print_r($user->id);die();
		$data['user']=$user;
		$key=Config::get('app.key');
		$data['firstname']=$user->firstname;
		$data['lastname']=$user->lastname;


		$password = Hash::make('secret');
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $user->username, MCRYPT_MODE_CBC, md5(md5($key))));
		$data['link']=route('activate', array('token'=>$encrypted));

		Mail::queue('activation_email', $data, function($message) use ($user)
		{
			//dd($user->firstname);
		    $message->to($user->email, $user->firstname." ".$user->lastname)->subject('siamplant.com : กรณายืนยันการสมัครสมาชิกเว็บไซต์');
		});
	}
	public function message(){
		return View::make('message');

	}
	public function activate($cipher){
		$key=Config::get('app.key');
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cipher), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

		$user = User::where('username', '=', $decrypted)->first();

		$user->status = 1;

		$user->save();

		$msg= "<div class='alert alert-success'><span class='glyphicon glyphicon-ok-sign'></span> <strong>การยืนยันการลงทะเบียนเสร็จสมบูรณ์</strong> ท่านได้ยืนยันการลงทะเบียนเรียบร้อยแล้ว ท่านสามารถลงชื่อเข้าใช้ได้จาก username และ password ที่ได้ทำการสมัครไว้ &nbsp;<a class='btn btn-large btn-info' href='".route('login')."'><span class='glyphicon glyphicon-user'></span> เข้าสู่ระบบ</a></div>";
		Session::flash('message', $msg);
		return View::make('message');


	}
	public function getBadgeList(){
		
	}

	public function cart(){
		$user=Auth::user();
	
		/*foreach($items_obj as $item){
			
			array_push($item_arr,$item->itemid);
		}*/

		//$data['items']=Item::whereIn('id', $item_arr)->get();
		$data['orders']=Order::where('userid','=',$user->id)->whereIn('status', array('cart','invoiced','reject','process','cancel','shipping'))->get();

		return View::make('cart',$data);
	}
	public function shop($username){

		$user=User::where('username','=',$username)->first();
		$data['user']=$user;
		$data['numsold']=Item::where('seller','=',$user->id)->count();
		$data['items']=Item::where('seller','=',$user->id)->orderBy('date_added', 'DESC')->paginate(20);
		return View::make('shop',$data);
	}
	public function order(){

		$user=Auth::user();
		$data['user']=$user;
		$data['numcart']=Order::where('sellerid','=',$user->id)->whereNotIn('status',['received'])->count();
		$data['orders']=Order::where('sellerid','=',$user->id)->whereIn('status',['cart','invoiced','reject','process'])->orderBy('order_dated', 'DESC')->paginate(20);
		return View::make('order',$data);
	}


	

}

?>