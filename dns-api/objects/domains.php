<?php
    class Domain{
    
        // database connection and table name
        private $conn;
        private $table_name = "domain";
    
        // record properties
        public $id;
        public $fqdn;
    
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }

        // Read DNS records
        public function list(){
        
            // select all query
            $query = "
                SELECT
                    d.id AS id,
                    d.fqdn AS fqdn
                FROM
                    " . $this->table_name . " AS d
                ORDER BY
                    d.fqdn ASC
            ";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            return $stmt;
        }


        // Create/Insert domain by FQDN
        function create(){
        
            // query to insert record
            $query = "INSERT INTO " . $this->table_name . " SET fqdn=:fqdn";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->fqdn=htmlspecialchars(strip_tags($this->fqdn));
        
            // bind values
            $stmt->bindParam(":fqdn", $this->fqdn);
        
            // execute query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }
    }
?>