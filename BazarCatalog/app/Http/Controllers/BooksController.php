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
  
  
  }
