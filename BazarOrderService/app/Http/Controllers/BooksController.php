<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;




class BooksController extends Controller
{
//in buy operation,do the buy and notify other order replicas if the buy operation is successful (the same order record in all replica DB)
public function updateOrdersAndNotify($itemNumber){
$res=$this->buyBook($itemNumber);
if(strpos($res,"Buy faild")){
}else{

 $this->sendUpdateNotification($itemNumber);}
return $res;

}
//notify  other order replicas that there is a new order record
public function sendUpdateNotification($itemNumber){
  
 $client = new Client();
$notifyRequest='http://192.168.164.131/notify/'.$itemNumber;
 $res1= $client->request('GET',  $notifyRequest);
   
    return $res1->getStatusCode();
}
//from other replicas,do the update->insert the order record in the DB
public function applyUpdates($itemNumber){
DB::insert('insert into orders (bookId,customerName,date) values(?,?,?)',[$itemNumber,"malak Bawwab",date("Y-m-d")]);

}
//do the buy
    public function buyBook($itemNumber)
    {
    $client = new Client();

//send look request to front node that contains cache,to know if the item exists or not
$queryRequest='http://192.168.164.128/lookup/number/'.$itemNumber;
     
 $res= $client->request('GET',  $queryRequest);
   
 if ($res->getStatusCode() == 200) { // 200 OK
     
$array = json_decode($res->getBody()->getContents(), true); 
//item esist and not out of stock
if($array!=null && $array[0]["quantity"]>0){
 
/*to catalog,decrease the quantity of the book by
1(buy operation is successful).*/
 $updateRequest='http://192.168.164.129/update/book/'.$itemNumber.'/type/buy/value/1';
   
 $updateRes= $client->request('PUT',$updateRequest);
//insert order in orders table 
  DB::insert('insert into orders (bookId,customerName,date) values(?,?,?)',[$itemNumber,"malak Bawwab",date("Y-m-d")
]);

  return  $updateRes->getBody();

}elseif($array!=null && $array[0]["quantity"]<=0){
 return   "Buy faild,book is out of stock";
}else{
 return   "Buy faild,no book with this number".' '.$itemNumber;
}

       
       }}
/* return a list for all the received orders of the book with this itemNumber.*/
 public function showAllOrders($itemNumber)
    {
$result= DB::select('select * from orders where bookId=?',[$itemNumber]);

  if(!empty($result)){

return response()->json($result);
}else{

return  response()->json("book with this itemNumber ".$itemNumber." Not Found");
}



}
}

