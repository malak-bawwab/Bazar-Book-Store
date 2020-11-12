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
  return response()->json(['message'=>'There is no book with this topic']);
  
  }
  
    public function showBasedOnItemNumber($itemNumber)
    {

$result= DB::select('select * from books where id=?',[$itemNumber]);

if(!empty($result)){

return response()->json(['Books'=>$result]);
   
              
  }
  return response()->json(['message'=>'There is no book with this itemNumber']);
  
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
    $quantity=$result1[0]->quantity -1;
    $result2=DB::update('update books set quantity = '  .$quantity. ' where id= ?' ,[$itemNumber]);



  return response()->json(['message'=>'Bought book'.' '.$result1[0]->title]);
   
 
 
  
  }}
