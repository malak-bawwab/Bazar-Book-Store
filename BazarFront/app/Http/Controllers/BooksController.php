<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;

use GuzzleHttp\Client;



class BooksController extends Controller
{

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
       $Request='http://192.168.209.134/search/'.$data;
$res= $client->request('GET',  $Request);
 
                

        }else if($command[0]=="lookup"){
//send to catalog
               $Request='http://192.168.209.134/lookup/'.$data;
$res= $client->request('GET',  $Request);
 

        }
        else if($command[0]=="buy"){
//send to order
  $Request='http://192.168.209.131/buy/'.$data;
$res= $client->request('POST',  $Request);



        }
        else{
        return view('greeting', ['result' => "Try again,command not found"]);
        }

   
    if ($res->getStatusCode() == 200) { 
            return view('greeting', ['result' =>  $res->getBody()]);

   }
}

    public function searchBasedOnTopic($topic)
    {
  $client = new Client();
       
//send to catalog      
       $Request='http://192.168.209.134/search/'.$topic;
$res= $client->request('GET',  $Request);
     //to return json response
return json_decode($res->getBody());
  

}

  public function lookupBasedOnNumber($itemNumber)
    {
  $client = new Client();

         //send to catalog
       $Request='http://192.168.209.134/lookup/'.$itemNumber;
$res= $client->request('GET',  $Request);
       return json_decode($res->getBody());
}

  public function buyBasedOnNumber($itemNumber)
    {
  $client = new Client();

         //send to order
       $Request='http://192.168.209.131/buy/'.$itemNumber;
$res= $client->request('POST',  $Request);
return $res->getBody();


}
}
