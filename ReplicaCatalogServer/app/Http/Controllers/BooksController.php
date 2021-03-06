<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class BooksController extends Controller
{



public function updateAndNotify($itemNumber,$type,$value){
$res=$this->updateStoreQuantity($itemNumber,$type,$value);
if(strpos($res,"Not Found") || strpos($res,"You only have")){
}else{

 $this->sendUpdateNotification($itemNumber,$type,$value);
}

return $res;
}


public function updateCostAndNotify($itemNumber,$newCost){
$res=$this->updateCost($itemNumber,$newCost);
if(strpos($res,"Not Found")){
}else{

 $this->sendUpdateNotification($itemNumber,"cost",$newCost);
}

return $res;
}

//search for a book in books table based on topic
    public function showBasedOnTopic($topic)
    {
//covert %20 to spaces
$topic = str_replace('%20',' ',$topic);
$result= DB::select('select * from books where topic=?',[$topic]);

if(!empty($result)){

return response()->json($result);
   
              
  }
  return response()->json('Try again,There is no book with this topic'.' '.$topic);
  
  }
  //search for a book in books table based on the itemNumber
    public function showBasedOnItemNumber($itemNumber)
    {

$result= DB::select('select * from books where id=?',[$itemNumber]);

if(!empty($result)){

return response()->json($result);
   
              
  }
  return response()->json('Try again,There is no book with this itemNumber'.' '.$itemNumber);
  
  }

   
public function  updateStoreQuantity($itemNumber,$type,$value){
$result1= DB::select('select * from books where id=?',[$itemNumber]);
if($type=="buy"){
$quantity=$result1[0]->quantity-1;
if($quantity<0){
$quantity=0;
}
$result2=DB::update('update books set quantity = '  .$quantity. ' where id= ?' ,[$itemNumber]);
     $this->sendInvalidateRequest($itemNumber,$result1[0]->topic);


return response()->json(['message'=>'Bought book'.' '.$result1[0]->title]);

}else if(!empty($result1)){
   if ($type=="increaseNumber" ){
    
$quantity=$result1[0]->quantity+$value;
}else if($type=="decreaseNumber"){
$quantity=$result1[0]->quantity-$value;

}else if($type=="newValue"){

$quantity=$value;

}
}else {
return response()->json(['message'=>"Book(with this itemNumber".$itemNumber.')'." Not Found"]);
}


if($quantity<0){
return response()->json(['message'=>"You only have".' '.$result1[0]->quantity]);

}else{
$result2=DB::update('update books set quantity = '  .$quantity. ' where id= ?' ,[$itemNumber]);
    $this->sendInvalidateRequest($itemNumber,$result1[0]->topic);
 return response()->json(['message'=>"Book".'('.$result1[0]->title.')'."Quantity is updated Successfully from".$result1[0]->quantity." to ".$quantity]);

}

}
   
  public function  updateCost($itemNumber,$newCost)    {
    $result= DB::select('select * from books where id=?',[$itemNumber]);

if(!empty($result)){


    $result1=DB::update('update books set price = '  .$newCost. ' where id= ?' ,[$itemNumber]);
 $this->sendInvalidateRequest($itemNumber,$result1[0]->topic);
  return response()->json(['message'=>"Book".'('.$result[0]->title.')'."Cost is updated Successfully"." From".' '.$result[0]->price.' '."To ".$newCost]);
   }else{


return response()->json(['message'=>"Book(with this itemNumber".$itemNumber.')'." Not Found"]);

}
} 
public function sendUpdateNotification($itemNumber,$updateType,$newValue){
  
 $client = new Client();
$notifyRequest='http://192.168.164.129/notify/'.$itemNumber.'/'.$updateType.'/'.$newValue;
 $res1= $client->request('GET',  $notifyRequest);
   
    return $res1->getStatusCode();
}

public function sendInvalidateRequest($itemNumber,$topic){
    $client = new Client();
$invalidateRequest='http://192.168.164.128/invalidate/'.$itemNumber.'/'.$topic;
 $res1= $client->request('GET',  $invalidateRequest);
   
        return $res1->getStatusCode();

}

public function applyUpdates($itemNumber,$updateType,$newValue){

if($updateType=="cost"){
$this->updateCost($itemNumber,$newValue);
}else {

 $this->updateStoreQuantity($itemNumber,$updateType,$newValue);
}

}
  
}
