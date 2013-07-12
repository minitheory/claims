#!/usr/bin/php -q
<?php  
//debug  
#ini_set ("display_errors", "1");  
#error_reporting(E_ALL);  
   
//include email parser  
require_once('class/rfc822_addresses.php');  
require_once('class/mime_parser.php');  

$db = new PDO('mysql:host=localhost;dbname=minith_claims2','minith_admin','qwer1234');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// read email in from stdin  
$fd = fopen("php://stdin", "r");  
$email = "";  
while (!feof($fd)) {  
    $email .= fread($fd, 1024);  
}  
fclose($fd);  
   
//create the email parser class  
$mime=new mime_parser_class;  
$mime->ignore_syntax_errors = 1;  
$parameters=array(  
 'Data'=>$email,  
);  
   
$mime->Decode($parameters, $decoded); 

//mail('sheldon@minitheory.com','From my email pipe!','"' . $email . '"');

//---------------------- GET EMAIL HEADER INFO -----------------------// 

//get the name and email of the sender  
$fromName = $decoded[0]['ExtractedAddresses']['from:'][0]['name'];  
$fromEmail = $decoded[0]['ExtractedAddresses']['from:'][0]['address'];  
  
//get the name and email of the recipient  
$toEmail = $decoded[0]['ExtractedAddresses']['to:'][0]['address'];  
$toName = $decoded[0]['ExtractedAddresses']['to:'][0]['name'];  
  
 //get the subject  
$subject = $decoded[0]['Headers']['subject:'];  
  
$removeChars = array('<','>');  
  
//get the message id  
$messageID = str_replace($removeChars,'',$decoded[0]['Headers']['message-id:']);  
  
//get the reply id  
$replyToID = str_replace($removeChars,'',$decoded[0]['Headers']['in-reply-to:']);  



//-------------------- check if matches any user in DB --------------------//
$nameQuery = $db->query("SELECT * FROM users WHERE email = '$fromEmail'");
$result = $nameQuery->fetch();

$resultName = $result['name'];
//no existing user found
if ($resultName == ''){
    $userName = 'anonymous';
    $matched = false;
}
//existing user matched
else{ 
    $userName = $resultName;
    $matched = true;
} 


//------------------------ ATTACHMENTS ------------------------------------//  
  
//loop through email parts  
foreach($decoded[0]['Parts'] as $part){  
      
    //check for attachments  
    if($part['FileDisposition'] == 'attachment'){  
          
        //format file name (change spaces to underscore then remove anything that isn't a letter, number or underscore)  
        $filename = preg_replace('/[^0-9,a-z,\.,_]*/i','',str_replace(' ','_', $part['FileName'])); 
        $extension = end(explode(".", $filename));
        
        //write the data to the file
        $date = new  DateTime();
        $savedName = date_format($date, 'Ymd-His-').$userName.".".$extension;
        $savedURI = 'receipts/emailed/'.$savedName;
        $fp = fopen('../'.$savedURI, 'w'); 
        $written = fwrite($fp,$part['Body']); 
        fclose($fp); 
         
        //add file to attachments array 
        $attachments[] = $part['FileName'];  
  
    }  
  
}
try{
    if($matched == false){
        $insertAnonymous = $db->prepare("INSERT INTO anonymous (name,email,image,status) VALUES (:name,:email,:image,0)");
        $insertAnonymous->execute(array(':name'=>$fromName, ':email'=>$fromEmail, ':image'=>$savedURI));
    }

    elseif ($matched == true) {
        $updateItem = $db->prepare("UPDATE items SET image=:image WHERE userid=:userid");
        $updateItem->execute(array(':image'=>$savedURI,':userid'=>$result['id']));
    }    
} catch (PDOException $e){
    echo 'Query failed: ' . $e->getMessage();
}



  
//print out the attachments for debug  
//print_r($attachments);  


?>