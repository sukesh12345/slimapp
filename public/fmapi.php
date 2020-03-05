<?php
header ("Access-Control-Allow-Origin:*");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: origin, x-requested-with, content-type, authorization");
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT ; 

use config as dbconnection;

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
    "ignore"=>["/slimapp/public/fmapi.php/login"],
    "secret" => "secret"
]));

//register 
    $app->post('/register', function(Request $request, Response $response)
        {
            $dbobj = new dbconnection\dbconnection();   
            $conn = $dbobj->connect();
            // $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $vars = json_decode($request->getBody());
            $count = 0;            
            foreach($vars as $key) {
                $count++;
            }
            if( $count != 7) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"request body is not appropriate","count"=>$count]);
            }
             
            $values = array( "Name" =>$request->getParsedBody()['Name'],
                "Address" => $request->getParsedBody()['Address'],
                "Email" => $request->getParsedBody()['Email'],
                "Password" =>$request->getParsedBody()['Password'],
                "Telephone" => $request->getParsedBody()['Telephone'],
                "Age" => "21",
                "Gender" => $request->getParsedBody()['gender'],
                "Course" => $request->getParsedBody()['Course']
            );
            $stmt = $conn->createRecord('users', $values);
            $result = $stmt->commit();
            if (FileMaker::isError($result)) {
                $findError = 'Find Error: '. $result->getMessage(). ' (' . $result->code. ')';
                $newresponse = $response->withStatus(404);
                return $newresponse->withJson(['success'=>false, "message"=>$findError]);
                
            }
            else{
                $newresponse = $response->withStatus(200);
                return $newresponse->withJson(['success'=>true, "message"=>$Telephone]);
            }
        });

    


//login 


    $app->post('/api/users', function(Request $request, Response $response)
{
    $dbobj = new dbconnection\dbconnection();
    $fm = $dbobj->connect();
    $jwt = new config\jwt();
    $vars = json_decode($request->getBody());
    if( $request->getParsedBody()['login_Name'] == null) {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(['status'=>false, 'message'=>'username is required ']);
    }
    if( $request->getParsedBody()['login_Password'] == null) {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(['status'=>false, 'message'=>'password is required']);
    }
    $phone = $request->getParsedBody()['login_Name'];
    $password = $request->getParsedBody()['login_Password'];
    if(preg_match("/^[0-9]\d{9}$/", $phone) == false) {
        $newresponse = $response->withStatus(400);
        return $newresponse->withJson(["status"=>false, "message"=>"username is not valid"]);
    }
    //( preg_match("/(?=[a-z])/", $password) == false) or ( preg_match("/(?=[A-Z])/", $password) == false) or ( preg_match("/(?=[0-9])/", $password) == false) or 
    if(( strlen($password) < 8)) {
        $newresponse = $response->withStatus(400);
        return $newresponse->withJson(["status"=>false, "message"=>"password is not valid"]);
    } 
    $findCommand = $fm->newFindCommand('users');
    $findCommand->addFindCriterion('Telephone', $phone);
    $findCommand->addFindCriterion('Password', $password);
    $result=$findCommand->execute();   
    if (FileMaker::isError($result)) {
        if ($result->code = 401) {
        $findError = 'There are no Records that match that request: '. ' (' .
        $result->code . ')';
        } else {
        $findError = 'Find Error: '. $result->getMessage(). ' (' . $result->code
        . ')';
        }
        $newresponse =  $response->withStatus(404);
        return $newresponse->withJson(["success"=>false,"message"=>"credentials dosent match each other"]);
        }  
    $ph=$result->getRecords()[0]->_impl->_fields['Telephone'][0];
    $id=$result->getRecords()[0]->_impl->_fields['Id'][0];
    if(count($result->getRecords())==1){
        $token = $jwt->jwttokenencryption( $ph);
    return $response->withJson(["status"=>true,"data"=>$id , "token"=>$token]);
    } else {
        $newresponse = $response->withStatus(401);
        return $newresponse->withJson(["status"=>false, "message"=>"credentials  dosent match each other"]);
    }
});


//delete
$app->delete('/api/users/{Id}', function(Request $request, Response $response, array $args) 
{   
    // $rec_ID = $args['Id'];
    $dbobj = new dbconnection\dbconnection();
    $fm = $dbobj->connect();
    $jwt = new config\jwt();
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
    $rec_ID = json_decode($jwt->jwttokendecryption($vars))->status;
    $findCommand = $fm->newFindCommand('users');
    $findCommand->addFindCriterion('Telephone', $rec_ID);
    $result=$findCommand->execute(); 
    if (FileMaker::isError($result)) {
        if ($result->code = 401) {
        $findError = 'There are no Records that match that request: '. ' (' .
        $result->code . ')';
        } else {
        $findError = 'Find Error: '. $result->getMessage(). ' (' . $result->code
        . ')';
        }
        $newresponse =  $response->withStatus(404);
        return $newresponse->withJson(["success"=>false]);
        }   
    $ph=$result->getRecords()[0];
    $ph->delete();
    if(count($result->getRecords())==1){
        $newresponse = $response->withStatus(200);
        return $newresponse->withJson(['success'=>true,'message'=>'record deleted successfully']);
    } else {
        $newresponse =  $response->withStatus(404);
        return $newresponse->withJson(["success"=>false]);
    }
});


//Update
$app->put('/api/users/{id}', function(Request $request, Response $response,array $args) 
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
            $fm = new dbconnection\dbconnection();
            $fm = $fm->connect();
            // $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $vars = json_decode($request->getBody());
            $count = 0;
            foreach($vars as $key) {
                $count++;
            }
            if( $count != 6) {
                $newresponse = $response->withStatus(400);
                return $newresponse->withJson(["message"=>"request body is not appropriate"]);
            }   
            $Id = $vars->Id;         
            $Name = $vars->Name;
            $Address = $vars->Address;
            $Email = $vars->Email;
            $gender = $vars->gender;
            $Course = $vars->Course;   
            $findCommand = $fm->newFindCommand('users');
            $findCommand->addFindCriterion('Id', $Id);
            $result=$findCommand->execute(); 
            $findCommand=$result->getRecords()[0];
            $findCommand->setField('Name', $Name);
            $findCommand->setField('Email', $Email);
            $findCommand->setField('Address', $Address);
            $findCommand->setField('Gender', $gender);
            $findCommand->setField('Course', $Course);
            $result = $findCommand->commit();
});
        
//view a record
$app->get('/api/users/{id}', function(Request $request, Response $response, array $args)
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
    $Id = $args['id'];
    $dbobj = new dbconnection\dbconnection();
    $fm = $dbobj->connect();
    $findCommand = $fm->newFindCommand('users');
    $findCommand->addFindCriterion('Id', $Id);
    $result=$findCommand->execute(); 
    if (FileMaker::isError($result)) {
        if ($result->code = 401) {
        $findError = 'There are no Records that match that request: '. ' (' .
        $result->code . ')';
        } else {
        $findError = 'Find Error: '. $result->getMessage(). ' (' . $result->code
        . ')';
        }
        $newresponse =  $response->withStatus(404);
        return $newresponse->withJson(["success"=>false]);
        }   
    $ph=$result->getRecords()[0]->_impl->_fields;
    if(count($result->getRecords())==1){
        $newresponse = $response->withStatus(200);
        //print_r($ph);
        return $newresponse->withJson(['success'=>true, 'data'=>$ph]);
    } else {
        $newresponse =  $response->withStatus(404);
        return $newresponse->withJson(["success"=>false]);
    }
});
$app->run();
