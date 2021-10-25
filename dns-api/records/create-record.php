<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection and instantiate domain object
include_once '../config/database.php';
include_once '../objects/records.php';
  
$database = new Database();
$db = $database->getConnection();
  
$record = new Record($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->fqdn) &&
    !empty($data->type) &&
    !empty($data->name) &&
    !empty($data->val) &&
    !empty($data->ttl)
){
    // set DNS record property values
    $record->fqdn = $data->fqdn;
    $record->type = $data->type;
    $record->name = $data->name;
    $record->val = $data->val;
    $record->ttl = $data->ttl;
  
    // create the DNS record
    if($record->createRecord()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "DNS record was created/inserted."));
    }
  
    // if unable to create the record, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create/insert DNS record."));
    }
}
  
// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create DNS record. Data is incomplete."));
}
?>