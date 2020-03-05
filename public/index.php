<?php
header ("Access-Control-Allow-Origin:*");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: origin, x-requested-with, content-type, authorization");
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT ; 

use config as dbconnect;

require __DIR__ . '/../vendor/autoload.php';
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$c = new \Slim\Container($configuration);               
$app = new \Slim\App($c);
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "path"=>["/slimapp/public"],
    "ignore"=>["/slimapp/public/index.php/api/users","/slimapp/public/index.php/register"],
    "secret" => "secret"
]));

//register 
    $app->post('/register', function(Request $request, Response $response)
        {
            $dbobj = new dbconnect\dbconnection();   
            $conn = $dbobj->connect();
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $vars = json_decode($request->getBody());
            $count = 0;            
            foreach($vars as $key) {
                $count++;
            }
            if( $count != 9) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"request body is not appropriate","count"=>$count]);
            }
            $Name = $vars->Name;
            $Address = $vars->Address;
            $Email = $vars->Email;
            $Password = $vars->Password;
            $Telephone = $vars->Telephone;
            $gender = $vars->gender;
            $Course = $vars->Course;   
            $stmt = $conn->prepare("INSERT INTO registration_data(Name ,Address,Email, Password,Telephone,Gender,Course)
            VALUES (:Name,:Address,:Email,  :Password,:Telephone,:Gender,:Course )"); 
            $stmt->bindParam(':Name', $Name);
            $stmt->bindParam(':Address',$Address);
            $stmt->bindParam(':Email',$Email);
            $stmt->bindParam(':Password', $Password);
            $stmt->bindParam(':Telephone', $Telephone);
            $stmt->bindParam(':Gender',$gender);
            $stmt->bindParam(':Course',$Course);
            $stu_sic = 0;
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $resstmt = $conn->prepare("SELECT * FROM registration_data WHERE Telephone = :Telephone");
                $resstmt->bindParam(':Telephone', $Telephone);
                $res = $resstmt->execute();
                // $sicresult = $sicstmt->fetch(PDO::FETCH_ASSOC);
                // $stu_sic = $sicresult["Telephone"];
                // return $response->withJson(['status'=>true]);
                $res = $resstmt->fetch(PDO::FETCH_ASSOC);
                $status = $res['Id'];
                $jwt = new config\jwt();
                $token = $jwt->jwttokenencryption( $status);
                $value = $jwt->jwttokendecryption($token);
                return $response->withJson(["status"=>true, "data"=>$value, "token"=>$token]);
            } else {
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['status'=>false, "stmt"=>$stmt]);
            }   
        });

    


//login 


    $app->post('/api/users', function(Request $request, Response $response)
{
    $jwt = new config\jwt();
    $dbobj = new dbconnect\dbconnection();
    $conn = $dbobj->connect();
    //$vars = json_decode($request->getBody());
    $phone = $request->getParsedBody()['login_Name'];
    $password = $request->getParsedBody()['login_Password'];
    echo $phone;
    echo $password;
    if($phone == null || $password == null) {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(['status'=>false, 'message'=>'Request body not appropriate']);
    }
    if(preg_match("/^[0-9]\d{9}$/", $phone) == false) {
        $newresponse = $response->withStatus(400);
        return $newresponse->withJson(["status"=>false, "message"=>"username is not valid"]);
    }
    //( preg_match("/(?=[a-z])/", $password) == false) or ( preg_match("/(?=[A-Z])/", $password) == false) or ( preg_match("/(?=[0-9])/", $password) == false) or 
    if(( strlen($password) < 8)) {
        $newresponse = $response->withStatus(400);
        return $newresponse->withJson(["status"=>false, "message"=>"password is not valid"]);
    } 
    $stmt = $conn->prepare("SELECT * FROM registration_data WHERE Telephone = :phone and Password = :password");
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $status = $result['Id'];
        $token = $jwt->jwttokenencryption( $status);
        $value = $jwt->jwttokendecryption($token);
        return $response->withJson(["status"=>true, "data"=>$value, "token"=>$token]);
    } else {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(["status"=>false, "message"=>"credentials dosent match each other"]);
    }
});


//delete
$app->delete('/api/users/{Id}', function(Request $request, Response $response, array $args) 
{   

    $id = $args['Id'];
    $dbobj = new dbconnect\dbconnection();
    $conn = $dbobj->connect();
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true); 
    $jwt = new config\jwt();
    //$vars = json_decode($request->getBody());
    if( $request->hasHeader("Authorization") == false) {
        $newresponse = $response->withStatus(400);
        return $newresponse->withJson(["message"=>"required jwt token is not recieved"]);
    }
    $header = $request->getHeader("Authorization");
    $vars =$header[0];
    $token = json_decode($jwt->jwttokendecryption($vars));
    if( $token->verification == "failed") {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(["message"=>"you are not authorized"]);
    } 
    // $vars = json_decode($request->getBody());
    // $Telephone = $vars->Telephone;   
    $sql = "DELETE FROM registration_data WHERE Id = $id";
    $stmt = $conn->prepare($sql);
    //$stmt->bindParam(':Telephone', $Telephone);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
        $newresponse = $response->withStatus(200);
        return $newresponse->withJson(['success'=>true]);
    } else {
        $newresponse =  $response->withStatus(404);
        return $newresponse->withJson(["success"=>false]);
    }
});


//Update
$app->put('/api/users/{Id}', function(Request $request, Response $response,array $args) 
        {  
            $jwt = new config\jwt();
            $vars = json_decode($request->getBody());
            if( $request->hasHeader("Authorization") == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"required jwt token is not recieved"]);
            }
            $header = $request->getHeader("Authorization");
            $vars =$header[0];
            $token = json_decode($jwt->jwttokendecryption($vars));
            if( $token->verification == "failed") {
                $newresponse = $response->withStatus(401);
                return $newresponse->withJson(["message"=>"you are not authorized"]);
            } 
            $dbobj = new dbconnect\dbconnection();
            $conn = $dbobj->connect();
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $vars = json_decode($request->getBody());
            $count = 0;
            foreach($vars as $key) {
                $count++;
            }
            if( $count != 5) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"request body is not appropriate"]);
            }   
            // $Id = $vars->Id;    
            $id = $args['Id'];     
            $Name = $vars->Name;
            $Address = $vars->Address;
            $Email = $vars->Email;
            //$Password = $vars->Password;
            //$Telephone = $vars->Telephone;
            $gender = $vars->gender;
            $Course = $vars->Course;   
            // $Id = $args['Id'];
           $stmt = $conn->prepare(" UPDATE registration_data SET Name=:Name,Address=:Address,Email=:Email,Gender=:Gender,Course=:Course  WHERE Id = $id");
           $stmt->bindParam(':Name', $Name);
            $stmt->bindParam(':Address',$Address);
            $stmt->bindParam(':Email',$Email);
            //$stmt->bindParam(':Password', $Password);
           // $stmt->bindParam(':Telephone', $Telephone);
            $stmt->bindParam(':Gender',$gender);
            $stmt->bindParam(':Course',$Course);
           $query = $conn->prepare("SELECT * from registration_data WHERE 'Id' =$id");
           //$query->bindParam(':Telephone', $Telephone);
         // if($query->rowCount()==1){
            if ($stmt->execute() and $stmt->rowCount() == 1) {
                $newresponse = $response->withStatus(200);
                return $newresponse->withJson(['success'=>true, "message"=>'record is successfully updated']);
            }
            else {
                $newresponse = $response->withStatus(401);
                return $newresponse->withJson(['success'=>false, "message"=>"record with this phone doesnot exists"]);
            }
        //}
        // else {
        //     $newresponse = $response->withJson(['success'=>false, "message"=>"record with this phone doesnot exists"]);
        //     return $newresponse;
        // }
});
        
//view a record
$app->get('/api/users/{Id}', function(Request $request, Response $response, array $args)
{ 

    $jwt = new config\jwt();
            
            if( $request->hasHeader("Authorization") == false) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"required jwt token is not recieved"]);
            }
            $header = $request->getHeader("Authorization");
            $vars = $header[0];
            $token = json_decode($jwt->jwttokendecryption($vars));
            if( $token->verification == "failed") {
                // header("location: index.html");
                $newresponse = $response->withStatus(401);
                return $newresponse->withJson(["message"=>"you are not authorized"]);
            }


    $Id = $args['Id'];
    if (preg_match("/^\d+$/",$Id) == false) {
        $newresponse = $response->withStatus(400);
        return $newresponse->withJson(["success"=>false, 'message'=>'id must be a number']);
    }
    $dbobj = new dbconnect\dbconnection();
    $conn = $dbobj->connect();
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    $stmt = $conn->prepare("SELECT * FROM registration_data WHERE Id = $Id");
    $stmt->execute();    
    if ($stmt->rowCount() == 1) {  
        $result = $stmt->fetch(PDO::FETCH_ASSOC);  
        return $response->withJson(['status'=>200, 'result'=>$result]);
    } else {
        $newresponse = $response->withStatus(404);
        return $newresponse->withJson(['status'=>404, 'message'=>'no records exists with this id']);
    }       
});
$app->run();
