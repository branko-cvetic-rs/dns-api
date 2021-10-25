<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here
// include database and object files
include_once '../config/database.php';
include_once '../objects/domains.php';
  
// instantiate database and domain object
$database = new Database();
$db = $database->getConnection();
  
// initialize object
$domain = new Domain($db);


// read domains will be here
// query domains
$stmt = $domain->list();
$num = $stmt->rowCount();
  
// check if more than 0 domain found
if($num>0){
  
    // domains array
    $domains_arr=array();
    $domains_arr["domains"]=array();
  
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $domain_item=array(
            "id" => $id,
            "fqdn" => $fqdn
        );
  
        array_push($domains_arr["domains"], $domain_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show domains data in json format
    echo json_encode($domains_arr);
}
  
// no domains found will be here
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No domain found.")
    );
}