<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use GuzzleHttp\Client;




class BooksController extends Controller
{


    public function buyBook($itemNumber)
    {
    $client = new Client();
       
        $queryRequest='http://192.168.209.134/query/'.$itemNumber;
     
   $res= $client->request('GET',  $queryRequest);
   
    if ($res->getStatusCode() == 200) { // 200 OK
     
$array = json_decode($res->getBody()->getContents(), true); 
if($array["message"]=="Found,Not out of stock"){

       return   "Yes";
}elseif($array["message"]=="Found  but out of stock"){
 return   "No";
}elseif ($array["message"]=="Not Found"){
 return   "No";
}

       
       }
}
}
