<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection and instantiate domain object
include_once '../config/database.php';
include_once '../objects/domains.php';
  
$database = new Database();
$db = $database->getConnection();
  
$domain = new Domain($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure json data that is sent is not empty!
if(!empty($data->fqdn)){

    // set domain property values (only FQDN)
    $domain->fqdn = $data->fqdn;
  
    // create the domain
    if($domain->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Domain was created/inserted with FQDN."));
    }
  
    // if unable to create the domain, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create domain."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create domain. Data is incomplete."));
}
?>