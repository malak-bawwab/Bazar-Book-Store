<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use GuzzleHttp\Client;



class BooksController extends Controller
{


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
       
       $Request='http://192.168.209.134/search/'.$data;
                

        }else if($command[0]=="lookup"){
               $Request='http://192.168.209.134/lookup/'.$data;

        }
        else if($command[0]=="buy"){
               $Request='http://192.168.209.131/buy/'.$data;

        }
        else{
        return view('greeting', ['result' => "Try again,command not found"]);
        }

  $res= $client->request('GET',  $Request);
 
    if ($res->getStatusCode() == 200) { 
            return view('greeting', ['result' =>  $res->getBody()]);

     }


}

}

