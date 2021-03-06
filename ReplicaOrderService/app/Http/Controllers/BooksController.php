<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;




class BooksController extends Controller
{


public function applyUpdates($itemNumber){
DB::insert('insert into orders (bookId,customerName,date) values(?,?,?)',[$itemNumber,"malak Bawwab",date("Y-m-d")]);


}


public function updateOrdersAndNotify($itemNumber){
$res=$this->buyBook($itemNumber);
if(strpos($res,"Buy faild")){
}else{

 $this->sendUpdateNotification($itemNumber);}

return $res;
}
public function sendUpdateNotification($itemNumber){
  
 $client = new Client();
$notifyRequest='http://192.168.164.133/notify/'.$itemNumber;
 $res1= $client->request('GET',  $notifyRequest);
   
    return $res1->getStatusCode();
}


    public function buyBook($itemNumber)
    {
    $client = new Client();
 

 $queryRequest='http://192.168.164.128/lookup/number/'.$itemNumber;
     
 $res= $client->request('GET',  $queryRequest);
   
 if ($res->getStatusCode() == 200) { // 200 OK
     
$array = json_decode($res->getBody()->getContents(), true); 
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
