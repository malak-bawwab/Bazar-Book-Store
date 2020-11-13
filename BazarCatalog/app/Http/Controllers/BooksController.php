<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class BooksController extends Controller
{


    public function showBasedOnTopic($topic)
    {
$topic = str_replace('%20',' ',$topic);
$result= DB::select('select * from books where topic=?',[$topic]);

if(!empty($result)){

return response()->json(['Books'=>$result]);
   
              
  }
  return response()->json(['message'=>'Try again,There is no book with this topic'.' '.$topic]);
  
  }
  
    public function showBasedOnItemNumber($itemNumber)
    {

$result= DB::select('select * from books where id=?',[$itemNumber]);

if(!empty($result)){

return response()->json(['Book'=>$result]);
   
              
  }
  return response()->json(['message'=>'Try again,There is no book with this itemNumber'.' '.$itemNumber]);
  
  }
    public function checkIfExists($itemNumber)
    {
    
    $result1= DB::select('select * from books where id=?',[$itemNumber]);
 
    $result2=DB::select('select * from books where id=? and quantity>0',[$itemNumber]);

 if(!empty($result1) && !empty($result2)){
   return response()->json(['message'=>'Found,Not out of stock']);
 
 }elseif(!empty($result1) && empty($result2)){
    return response()->json(['message'=>'Found  but out of stock']);
 }
 elseif(empty($result1)){
     return response()->json(['message'=>'Not Found']);
 }

  
  }
    public function  updateStore($itemNumber)
    {
    
    $result1= DB::select('select * from books where id=?',[$itemNumber]);

    $quantity=$result1[0]->quantity-1;

    $result2=DB::update('update books set quantity = '  .$quantity. ' where id= ?' ,[$itemNumber]);



  return response()->json(['message'=>'Bought book'.' '.$result1[0]->title]);
   
 
 
  
  }
   
  public function  updateCost($itemNumber,$newCost)
    {
    $result= DB::select('select * from books where id=?',[$itemNumber]);

if(!empty($result)){


    $result1=DB::update('update books set price = '  .$newCost. ' where id= ?' ,[$itemNumber]);



  return response()->json(['message'=>"Book".'('.$result[0]->title.')'."Cost is updated Successfully"." From".' '.$result[0]->price.' '."To ".$newCost]);
   }else{


return response()->json(['message'=>"Book(with this itemNumber".$itemNumber.')'." Not Found"]);

}
} 
  public function  increaseQuantity($itemNumber,$numberOfItems)
    {
    $result= DB::select('select * from books where id=?',[$itemNumber]);

if(!empty($result)){

$quantity=$result[0]->quantity+$numberOfItems;
    $result1=DB::update('update books set quantity = '  .$quantity. ' where id= ?' ,[$itemNumber]);

    return response()->json(['message'=>"Book".'('.$result[0]->title.')'."Quantity is updated Successfully"." From".' '.$result[0]->quantity.' '."To ".$quantity]);

   }else{


return response()->json(['message'=>"Book(with this itemNumber".$itemNumber.')'." Not Found"]);


}  

  }
public function  decreaseQuantity($itemNumber,$numberOfItems)
    {
    $result= DB::select('select * from books where id=?',[$itemNumber]);

if(!empty($result)){

$quantity=$result[0]->quantity-$numberOfItems;
if($quantity>=0){
    $result1=DB::update('update books set quantity = '  .$quantity. ' where id= ?' ,[$itemNumber]);
 return response()->json(['message'=>"Book".'('.$result[0]->title.')'."Quantity is updated Successfully"." From".' '.$result[0]->quantity.' '."To ".$quantity]);

}else{
return response()->json(['message'=>"You only have".' '.$result[0]->quantity]);

} 

  }else{


return response()->json(['message'=>"Book(with this itemNumber".$itemNumber.')'." Not Found"]);
}

  }

}
