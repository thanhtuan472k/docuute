<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
Carbon::setLocale('vi');
use App\User;
use App\Products;
use App\UserCarts;
use App\Order;
use App\OrderDetail;
use App\Comments;
use App\Notifications;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\PriceSearchRequest;




class ProductController extends Controller
{
    public function getIndex(){
		//$products = DB::table('products')->where('status',1)->get();
		// $products = Products::where('status',1)->paginate(9);
		$products = Products::orderBy('created_at', 'DESC')->paginate(9);
		$categories = DB::select('select * from categories');
    	return view('template.pages.index', compact('products', 'categories'));
	}
	public function showCheckout(){
		return view('template.pages.checkout');
	}
	public function showContact(){
		return view('template.pages.contact');
	}
	public function postRating(Request $req){
    	if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
		


		
		request()->validate(['rate' => 'required']);

		$rating = $req->rate;

		$product = DB::table('products')->where('id',$req->pid)->first();

		
		DB::table('products')->where('id',$req->pid)->increment('numRate',1);
		DB::table('products')->where('id',$req->pid)->increment('numStar',$rating);
		
    	return redirect()->route('product',$req->pid);
	}

	public function showAbout(){
		return view('template.pages.about');
	}
	public function showProduct(){
		return view('template.pages.product');
	}
	public function getSearch(SearchRequest $req){
		$from = $req->from;
		$to = $req->to;
		$name = $req->key;
		$products = DB::table('products')->where('status',1)->where('name','like','%'.$req->key.'%')
					->orwhere('price',$req->key)
					->get();

		return view('template.pages.search', compact('products'), compact('name'),compact('from'), compact('to'));
	}

	public function getSearchPrice(PriceSearchRequest $req){
		$from = $req->input('from');
		$to = $req->input('to');
		$name = $req->name;
		$products = DB::table('products')->where('name','like','%'.$name.'%')
		->whereBetween('price',[$req->input('from'),$req->input('to')])->where('status',1)
					->get();
		return view('template.pages.search', compact('products'), compact('name'), compact('from'), compact('to'));
	}
	
	public function getProduct($id){

		$product = DB::table('products')->where('id',$id)->first();
		$relatedPd = DB::table('products')->where('cateID',$product->cateID)->where('status',1)->get();
		$seller = DB::table('users')->where('id',$product->sellerID)->first();
		$comments = DB::table('comments')->where('productID',$product->id)->get();
		return view('template.pages.product',compact('product','seller','relatedPd','comments'));
	}
	public function getCategories($id){

		//$products = DB::table('products')->where('cateID',$id)->where('status',1)->get();
		$products = Products::where('status',1)->where('cateID',$id)->paginate(9);
		$categories = DB::select('select * from categories');
		return view('template.pages.index',compact('products','categories'));
	}
	// public function addToCart($pid,$uid){
 //    	if (!Auth::check()){
	// 		return redirect()->route('signin.getSignin');
	// 	}
	// 	$product= DB::table('products')->where('id',$pid)->first();
	// 	if($product->sellerID == Auth::User()->id){
	// 		return redirect()->route('product',$product->id)->withErrors(['error'=>'Kh??ng th??? t??? mua s???n ph???m c???a ch??nh m??nh']);

	// 	}
	// 	if ($product->quantity <=0){
	// 		return redirect()->route('product',$pid)->withErrors(['error'=>'S???n ph???m n??y ???? h???t']);
	// 	}
 //        $oldcart = DB::table('usercarts')->where('userID',$uid)->where('productID',$pid)->get();
 //        if (count($oldcart) != 0){

 //            DB::table('usercarts')->where('userID',$uid)->where('productID',$pid)->increment('quantity',1);
 //            DB::table('products')->where('id',$pid)->decrement('quantity',1);
 //            return redirect()->route('order.getCart');
 //        }
 //        $cart = new UserCarts();
 //        $cart->userID = $uid;
 //        $cart->productID = $pid;
 //        $cart->quantity = 1;
 //        $cart->status = 0;
 //        $cart->save();
 //        DB::table('products')->where('id',$pid)->decrement('quantity',1);
	// 	// $carts = DB::table('usercarts')->where('userID',$uid)->get();
 //        // $seller = Products::find(1)->user;
 //        // $product = DB::table('usercarts')->where('userID',$uid)->get();






 //        return redirect()->route('order.getCart');
 //    }

    public function postAddToCart(Request $request){
    	if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
		$uid = Auth::User()->id;
		$pid = $request->pid;

		$product= DB::table('products')->where('id',$pid)->first();
		if($product->sellerID == Auth::User()->id){
			return redirect()->route('product',$product->id)->withErrors(['error'=>'Kh??ng th??? t??? mua s???n ph???m c???a ch??nh m??nh']);

		}
		if ($request->quantity <=0){
			return redirect()->route('product',$pid)->withErrors(['error'=>'S??? l?????ng kh??ng h???p l???']);
		}
		if ($product->quantity <=0){
			return redirect()->route('product',$pid)->withErrors(['error'=>'S???n ph???m n??y ???? h???t']);
		}
		if ($product->quantity < $request->quantity){
			return redirect()->route('product',$pid)->withErrors(['error'=>'S??? l?????ng h??ng c??n l???i kh??ng ????? theo y??u c???u']);
		}
		
        $oldcart = DB::table('usercarts')->where('userID',$uid)->where('productID',$pid)->get();
        if (count($oldcart) != 0){

            DB::table('usercarts')->where('userID',$uid)->where('productID',$pid)->increment('quantity',$request->quantity);
            DB::table('products')->where('id',$pid)->decrement('quantity',$request->quantity);
            return redirect()->route('index.getIndex')->with('message','S???n ph???m ???? ???????c th??m v??o gi??? h??ng');
        }
        $cart = new UserCarts();
        $cart->userID = $uid;
        $cart->productID = $pid;
        $cart->quantity = $request->quantity;
        $cart->status = 0;
        $cart->save();
        DB::table('products')->where('id',$pid)->decrement('quantity',$request->quantity);
		// $carts = DB::table('usercarts')->where('userID',$uid)->get();
        // $seller = Products::find(1)->user;
        // $product = DB::table('usercarts')->where('userID',$uid)->get();
        return redirect()->route('index.getIndex')->with('message','S???n ph???m ???? ???????c th??m v??o gi??? h??ng');
    }

    public function getCart(){
        if (!Auth::check()){
            return redirect()->route('signin.getSignin');
        }
        $uid = Auth::User()->id;
        $carts = DB::table('usercarts')->where('userID',$uid)->get();
        return view('template.pages.shopping-cart',compact('carts'));
    }
     public function removeCart($id){
        if (!Auth::check()){
            return redirect()->route('signin.getSignin');
        }
        $cart = DB::table('usercarts')->where('id',$id)->first();
        DB::table('products')->where('id',$cart->productID)->increment('quantity',$cart->quantity);
        DB::table('usercarts')->where('id',$id)->delete();
        return redirect()->route('order.getCart');
     }

    public function postComment(Request $req){
    	if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
		$comment = new Comments();
		$comment->content = $req->comment;
		$comment->userID = Auth::User()->id;
		$comment->productID = $req->pid;
		$comment->save();
    	return redirect()->route('product',$req->pid);

    }

	
	public function createOrder(){
		if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
		
		$uid =[];

		//tao order vs order_detail
		$carts = DB::table('usercarts')->where('userID',Auth::User()->id)->get();
		if (count($carts)==0){
			return redirect()->route('order.getCart')->withErrors(['error'=>'B???n kh??ng c?? s???n ph???m trong gi??? h??ng']);
		}
		$order = new Order();
		
		$order->userID = Auth::User()->id;
		$total = 0;
		for($i = 0; $i < count($carts); $i++){
			$cart = $carts[$i];
			$product = DB::table('products')->where('id',$cart->productID)->first();
			$total += $product->price*$cart->quantity;
			$uid[$i]=$product->sellerID;
		}
		$order->total = $total;
		$order->save();
		$id_order = $order->id;
		for($i = 0; $i < count($carts); $i++){
			$cart = $carts[$i];
			$product = DB::table('products')->where('id',$cart->productID)->first();
			
			$order_detail = new OrderDetail();
			$order_detail->productID = $product->id;
			$order_detail->price = $product->price;
			$order_detail->quantity = $cart->quantity;
			$order_detail->oderID = $id_order;
			$order_detail->save();

		}

		//tao thong bao
		
		for($i = 0; $i < count($carts); $i++){

			$cart = $carts[$i];
			$product = DB::table('products')->where('id',$cart->productID)->first();
			$notification = new Notifications();
			$notification->userID = $product->sellerID;
			$notification->customerID = Auth::User()->id;
			$notification->orderID = $order->id;
			$notification->isNew = 1;
			$notification->isDone = 0;
			$notification->save();
			break;
			
		}
		

		//xoa san pham vua mua trong gio hang
		//$product = DB::table('products')->where('id',$carts[0]->productID)->first();
		DB::table('usercarts')->where('userID',Auth::User()->id)->delete();

		return redirect()->route('user.getHistory');
	}
	




	public function updateProduct(ProductRequest $request){

        if (!Auth::check()){
			return redirect()->route('signin.getSignin');
		}
		request()->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		$user = Auth::User();
		$product=DB::table('products')->where('sellerID',$user->id)->where('id',$request->pid)->first();
		
        if(!$request->description){
            $request->description = $product->description;
        }
        if(!$request->image){
            $imageName = $product->image;
        }
        else{
        	$imageName = time().'.'.request()->image->getClientOriginalExtension();
			request()->image->move(public_path('assets/dest/products'), $imageName);
        }
		
		$product=DB::table('products')->where('sellerID',$user->id)->where('id',$request->pid);
		
		
		$product->update(['image'=>$imageName,'name' => $request->name, 'price' => $request->price, 'description' => $request->description, 'cateID' => $request->cateID, 'quantity' => $request->quantity]);
		// $products->id = $request->id;
		// $products->name = $request->name;
        // $products->price = $request->price;
        // $products->description = $request->description;
		// $products->cateID = $request->cateID;
		// $products->quantity = $request->quantity;
        // $products->update();
        return redirect()->route('productuser')->with('message','Th??ng tin s???n ph???m ???? ???????c c???p nh???t!');
	}

    public function showProductUser($id){
		$user = Auth::User();
        $products = DB::table('products')->where('sellerID',$user->id)->where('id',$id)->first();
		return view('template.pages.update_product',compact('products'));
	}
	public function removeProduct($id){
        if (!Auth::check()){
            return redirect()->route('signin.getSignin');
        }
        $products = DB::table('products')->where('id',$id)->delete();
        // DB::table('products')->where('id',$cart->productID)->increment('quantity',$cart->quantity);
        // DB::table('usercarts')->where('id',$id)->delete();
        return redirect()->route('productuser');
    }
    public function getRemoveOrder($id){
    	if (!Auth::check()){
            return redirect()->route('signin.getSignin');
        }
        DB::table('orders')->where('id',$id)->delete();
        return redirect()->route('user.getHistory')->with('message','X??a ????n h??ng th??nh c??ng');	
    }
}
