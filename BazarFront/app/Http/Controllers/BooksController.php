<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Cache;

class BooksController extends Controller
{
  
//2 catalog:main1,replica1=2
//round-robin

//parse the entered commands that comes from GUI(greeting.php)
    public function parseCommands(Request $request)
    {


        //for example,bodyContent is command=search+distributed+systems
        $bodyContent = $request->getContent();
        
        
      //to sepearte leftside from rightside(required full command)
      $body=explode('=',$bodyContent);
      
        //to extract command ,$command[0] is the command search,lookup,buy
        $command=explode('+',$body[1]);

        //to get data of the command like:distributed systems
       $data='';
       for ($index = 1; $index < sizeof($command); $index++) {


         $data=$data.' '.$command[$index];
    
     }
     $data=trim($data);
     
     //send rest requests based on the input command
      $client = new Client();
       
        $Request='';


       if($command[0]=="search"){
       //send to catalog
       $Request='http://192.168.164.129/search/'.$data;
$res= $client->request('GET',  $Request);
 
                

        }else if($command[0]=="lookup"){
//send to catalog



  $Request='http://192.168.164.129/lookup/'.$data;

$res= $client->request('GET',  $Request);
 

        }
        else if($command[0]=="buy"){
//send to order
  $Request='http://192.168.164.130/buy/'.$data;
$res= $client->request('POST',  $Request);



        }
        else{
        return view('greeting', ['result' => "Try again,command not found"]);
        }

   
    if ($res->getStatusCode() == 200) {

 
         
 return view('greeting', ['result' =>  $res->getBody()]);

   }
}

public function checkReplicaTurn(){
$state;
if (Cache::has("CatalogloadBalance")){
$state=Cache::get("CatalogloadBalance");
}else{

$state=1;
Cache::set("CatalogloadBalance",1);
}

return $state;

}
    public function searchBasedOnTopic($topic)    {

$client = new Client();
$topic = str_replace('%20','-',$topic);
if(Cache::has($topic)){
$f=Cache::get($topic);
return($f);}

//return Cache::getMemcached()->getAllKeys();
else{
$oldTopic = str_replace('-',' ',$topic);
//send to catalog      

$state=$this->checkReplicaTurn();
if($state==1){
Cache::set("CatalogloadBalance",2);
$Request='http://192.168.164.129/search/'.$oldTopic;

}else{
$state=1;
Cache::set("CatalogloadBalance",1);
$Request='http://192.168.164.132/search/'.$oldTopic;

}

$res= $client->request('GET',  $Request);
     //to return json response
$x=json_decode($res->getBody(),true);

Cache::put($topic,$x);
}
return $x;
}
  public function invalidateData($itemNumber)
    {
if(Cache::has($itemNumber)){


Cache::delete($itemNumber);
}
return Cache::getMemcached()->getAllKeys();
//return "ok";
}
  public function lookupBasedOnNumber($itemNumber)
    {
//Round Robin

if(Cache::has($itemNumber)){
return Cache::get($itemNumber);
}else{

$state=$this->checkReplicaTurn();
if($state==1){
Cache::set("CatalogloadBalance",2);
$Request='http://192.168.164.129/lookup/'.$itemNumber;
}else{
$state=1;
Cache::set("CatalogloadBalance",1);
$Request='http://192.168.164.132/lookup/'.$itemNumber;


}
$client = new Client();
//send to catalog
$res= $client->request('GET',  $Request);
$x=json_decode($res->getBody(),true);
Cache::put($itemNumber,$x);
return $x;
}
}

  public function buyBasedOnNumber($itemNumber)
    {
  $client = new Client();

         //send to order
       $Request='http://192.168.164.130/buy/'.$itemNumber;
$res= $client->request('POST',  $Request);
return $res->getBody();


}
}
