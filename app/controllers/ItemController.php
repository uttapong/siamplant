<?php

class ItemController extends BaseController {

	protected $layout = 'layouts.main';
	
	public function newitem()
	{
		$data=array();
		$data['categories']=Category::all();
		$data['shippings']=Shipping::all();
		
		return View::make('additem',$data);
	}
	public function upload(){
		$file = Input::file('file');
	    $extension = File::extension($file->getClientOriginalName());
	    $directory = 'uploads/'. Auth::user()->id;
	    //$filename =  File::name($file->getClientOriginalName()).".".$extension;
	     $filename=md5(rand()).".".strtolower($extension);

	    $upload_success = Input::file('file')->move($directory, $filename); 
	     if( $upload_success ) {
	        	return $filename;
	        } else {
	        	return 'error';
	        }
	}
	public function add(){

		$user=Auth::user();

		//update user for first time start selling
		if(!Item::where('seller','=',$user->id)){
			$user['shopstart']=date('Y-m-d H:i:s');
			$user->save();
		}
		
		$new_item = Input::except('_token');
		$new_item['seller']=$user->id;
		$new_item['status']='new';
		$new_item['remaining']=Input::get('amount');
		$new_item['date_added']=date('Y-m-d H:i:s');
		$item = new Item($new_item);
		if ($item->save()) {
            $msg= "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> เพิ่มสินค้าใหม่ของคุณเรียบร้อยแล้ว</div>";
			Session::flash('message', $msg);
			return "1";
        } else {
            return $item->errors()->all();
        }
		

		return Redirect::route('home');
		

	}
	public function edit($id){
		if(Input::has('title')){


		$new_item = Input::except('_token');
		 // let's setup some rules for our new data
		 // I'm sure you can come up with better ones
		$rules = array(
		'title' => 'required',
		 'slug' => 'required',
		 'content' => 'required',
		 'category'=>'required'
		 );
		 // make the validator

		$v = Validator::make($new_item, $rules);
		//dd($v->fails());
		 if ( $v->fails() )
		 {
		 // redirect back to the form with
		 // errors, input and our currently
		 // logged in user
		 	return $v->messages();
		 	//return json_encode(array('result'=>0,'message'=>$v->messages()));
		 
		 }
		 else{
		 	
		 	$content = Content::find($id);
		 	$content->title=stripslashes(Input::get('title'));
		 	$content->slug=$this::seoUrl(Input::get('slug'));
		 	$content->category=Input::get('category');
		 	$content->content=stripslashes(Input::get('content'));

			$result=$content->save();
			//dd($result);
			if (Input::hasFile('upload'))
			{

				Upload::where('uploadable_id', '=', $content->id)->delete();
				
				$dest=public_path()."/uploads/contents/".$content->id."/";
				if( ! file_exists($dest)) {
				    mkdir($dest, 0777);
				}

				$extension = Input::file('upload')->getClientOriginalExtension();
				$filename=uniqid().".".$extension;
				
			    Input::file('upload')->move($dest,$filename);

			    $upload=new Upload(array(
			    	'uploadable_id'=>$content->id,
			    	'path'=>'content',
			    	'filename'=>$filename,
			    	'extension'=>$extension

			    	));
			   $upload->save();

			   
			}

			if($result){
					$msg= "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> แก้ไขบทความเรียบร้อยแล้ว</div>";
					return Redirect::route('admin')->with('message',$msg);
			}
		 }


		}
		else{
			$data['content']= Content::where('id',$id)->first();
			//dd($data['content']);
			return View::make('admin/editcontent',$data);
		}

	}
	
	function index(){
		$data['contents']=Content::orderBy('updated_at', 'DESC')->paginate(12);
		return View::make('admin/content',$data);

	}
	
	function remove($id){

		if(Content::find($id)){

		Upload::where('uploadable_id', '=', $id)->delete();	

		Content::find($id)->delete();
		$msg= "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span> บทความถูกลบเรียบร้อยแล้ว</div>";
		Session::flash('message', $msg);
		}
		else{

			$msg= "<div class='alert alert-danger'><span class='glyphicon glyphicon-ok'></span> ไม่พบบทความที่ต้องการลบ</div>";
			Session::flash('message', $msg);
		}
		return Redirect::route('admin');
	}

	function seoUrl($text) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
	   // $text = strtolower($text);
	    //Strip any unwanted characters
	     $text = str_replace(' ', '-', $text);
		 $text = preg_replace( '/[«»"!?,.]+/', '', $text );
		 $text=str_replace('\\', '', $text);

		 // $text = strtolower(trim($text, '-'));

		  return $text;
	}

	public function addcart($numitem=1){
		$user=Auth::user();
		$id=Input::get('itemid');
		$item=Item::find($id);
		$update_data= array();

		if($item->remaining<1){
			$update_data['status']='cart';
			$item->update($update_data);
			return strval('soldout');
		}

		$order=new Order;
		$order->userid=$user->id;
		$order->itemid=$id;
		$order->sellerid=$item->owner->id;
		$order->status='cart';
		$order->order_dated=date('Y-m-d H:i:s');

		
		

		if($order->save()){

			
			$alluser=unserialize($item->reserved_user);
			
			if(!$alluser)$alluser=array();
			array_push($alluser,$user->id);
			
			$update_data['reserved_user']=serialize($alluser);
			$remaining=intval($item->remaining)-intval($numitem);
			$update_data['remaining']=$remaining;
			if($remaining<1)$update_data['status']='cart';
			return strval($item->update($update_data));

		}
		return  $order->errors()->all();
	}
	public function viewcat(){
		
	}
	public function image($size,$itemid){
		return Item::find($itemid)->getimage($size);

	}

	public function preinvoice($buyerid){
		$orders=Order::where('userid','=',$buyerid)->whereIn('status', array('cart','invoiced','process','reject'))->get();
		$order_items=array();
		foreach($orders as $order){

			array_push($order_items,array('orderid'=>$order->id,'item'=>$order->item->itemname, 'amount'=>$order->amount,'price'=>$order->item->price,'total'=>($order->amount)*($order->item->price) ));
		}
		return json_encode($order_items);
		
	}

	public function getpayment($invoiceid){
		$orders=Order::where('invoiceid','=',$invoiceid)->whereIn('status', array('cart','invoiced'))->get();
		$result=array();
		$order_items=array();
		$invoice=Invoice::find($invoiceid);
		foreach($orders as $order){
			array_push($order_items,array('orderid'=>$order->id,'item'=>$order->item->itemname, 'amount'=>$order->amount,'price'=>$order->item->price,'total'=>($order->amount)*($order->item->price) ));
		}
		$result['items']=$order_items;
		$result['invoice']=$invoice;
		$result['seller']=$invoice->seller;
		$banks=$invoice->seller->bankaccounts;
		foreach($banks as $bank){
			$bank->bankname=$bank->bankname();
		}

		$result['banks']=$banks;
		return json_encode($result);
		
	}
	public function savepayment(){
		
		$invoiceid=Input::get('invoiceid');
		$invoice=Invoice::find($invoiceid);

		$update_inv = Input::except('_token',"invoiceid");
		$update_inv['status']='process';
		 // let's setup some rules for our new data
		 // I'm sure you can come up with better ones
		$rules = array(
		'paid_bank' => 'required',
		 'paid_amount' => 'required',
		 'paid_time' => 'required',
		 );
		 // make the validator

		$v = Validator::make($update_inv, $rules);
		//dd($v->fails());
		 if ( $v->fails() )
		 {
		 // redirect back to the form with
		 // errors, input and our currently
		 // logged in user
		 	return $v->messages();
		 	//return json_encode(array('result'=>0,'message'=>$v->messages()));
		 
		 }
		 else{

		 	$orders=$invoice->allorders;
		 	//die($orders);
		 	foreach($orders as $order){
		 		$order->status='process';
		 		$order->save();
		 	}
			//dd($result);
			if(Input::file('paid_file'))
		 	{
		 		$file = Input::file('paid_file');
			    $extension = File::extension($file->getClientOriginalName());
			    $directory = 'uploads/'. Auth::user()->id.'/profile';
			    //$filename =  File::name($file->getClientOriginalName()).".".$extension;
			     $filename=md5(rand()).".".strtolower($extension);
		 		Input::file('paid_file')->move($directory, $filename);
				$update_inv['paid_file']=$filename;
			}
			return strval($invoice->update($update_inv));
			
		 }
		
	}
	public function saveinvoice(){


		$new_invoice = Input::except('_token');
		//print_r($new_invoice);

		//set status for old invoice
		//$affectedRows = Invoice::where('votes', '>', 100)->update(array('status' => 2))


		$new_invoice['status']='invoiced';
		$new_invoice['created_at']=date('Y-m-d H:i:s');
		$invoice = new Invoice($new_invoice);

		$result=$invoice->save();

		if($result){
			$orders=json_decode(Input::get('orders'));
			foreach($orders as $order){
				$db_order=Order::find($order);
				$db_order->status='invoiced';
				$db_order->invoiceid=$invoice->id;
				$db_order->save();
			}
			//echo "1";
			$msg="<div class='alert alert-success'><strong>ส่งใบแจ้งราคาเรียบร้อยแล้ว</strong> กรุณารอรอการแจ้งชำระเงินจากลูกค้า</div>";
			Session::flash('message', $msg);
		}
		else{
			 return $invoice->errors()->all();
		}

		return strval($result); 
	}
	public function item($itemid){
		$data['item']=Item::find($itemid);
		return View::make('item',$data);

	}
	public function orderstatus($orderid){
		$order=Order::find($orderid);
		$order->status=Input::get('status');
		if(Input::get('status')=='relist'){
			$item=Item::find($order->item->id);
			$item->status='relist';
			$item->save();

			$update_data=array();
			$alluser=unserialize($item->reserved_user);
			//return strval($allitem);
			$userid=Input::get('buyerid');
			$allitem=array_diff($alluser, [$userid]);
			$item['reserved_user']=serialize($allitem);
			$item->save();

	
		}
		return strval($order->save());
	}
	
	
	
}

?>
