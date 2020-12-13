<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;




class BooksController extends Controller
{


    public function buyBook($itemNumber)
    {
    $client = new Client();
/*to catalog, check if
the book exists on the store and is not out of sock(there are items available
to buy)*/       

 $queryRequest='http://192.168.164.129/query/'.$itemNumber;
     
 $res= $client->request('GET',  $queryRequest);
   
 if ($res->getStatusCode() == 200) { // 200 OK
     
$array = json_decode($res->getBody()->getContents(), true); 
if($array["message"]=="Found,Not out of stock"){
 
/*to catalog,decrease the quantity of the book by
1(buy operation is successful).*/
 $updateRequest='http://192.168.164.129/update/book/'.$itemNumber.'/type/buy/value/1';
   
 $updateRes= $client->request('PUT',$updateRequest);
//insert order in orders table 
  DB::insert('insert into orders (bookId,customerName,date) values(?,?,?)',[$itemNumber,"malak Bawwab",date("Y-m-d H:i:s")
]);

  return  $updateRes->getBody();

}elseif($array["message"]=="Found  but out of stock"){
 return   "Buy faild,book is out of stock";
}elseif ($array["message"]=="Not Found"){
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

