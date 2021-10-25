<?php
    class Record{
    
        // database connection and table name
        private $conn;
        private $table_name = "record";
        private $referencing_table_name = "domain";
    
        // DNS record properties
        public $id;
        public $type;
        public $domain;
        public $name;
        public $val;
        public $ttl;

        public $fqdn;
        public $domain_name;
    
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }

        // read DNS records
        public function get(){
        
            // select all query
            $query = "
                SELECT
                    r.id AS id,
                    r.type AS type,
                    d.fqdn AS domain,
                    r.name AS name,
                    r.val AS val,
                    r.ttl AS ttl
                FROM
                    " . $this->table_name . " AS r
                    INNER JOIN domain AS d
                        ON (r.domain = d.id)
                ORDER BY
                    d.fqdn ASC,
                    r.type ASC
            ";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            return $stmt;
        }

        // search records according to given keyword
        function search($keyword){
        
            // select all record that corespond to given keywords - can find domains which don't have associated DNS records
            $query = "
                SELECT
                    d.fqdn AS fqdn,
                    r.name AS domain_name
                FROM
                    " . $this->table_name . " AS r
                    RIGTH OUTER JOIN domain AS d
                        ON (r.domain = d.id)
                WHERE
                    d.fqdn LIKE ? OR r.name LIKE ?
                ORDER BY
                    d.fqdn ASC,
                    r.name ASC
            ";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $keyword=htmlspecialchars(strip_tags($keyword));
            $keyword = "%{$keyword}%";
        
            // bind
            $stmt->bindParam(1, $keyword);
            $stmt->bindParam(2, $keyword);
        
            // execute query
            $stmt->execute();
        
            return $stmt;
        }


        // create DNS record (if FQDN exist use that ID, otherwise insert FQDN and DNS record)
        function createRecord(){

            $query_fqdn = "
                SELECT
                    d.id AS id,
                    d.fqdn AS fqdn
                FROM
                    " . $this->referencing_table_name . " AS d
                WHERE
                    d.fqdn = '" . $this->fqdn . "'
                ORDER BY
                    d.id DESC
            ";
        
            $stmt_domain = $this->conn->prepare($query_fqdn);
            $stmt_domain->execute();

            //  If there isn't already in domain table given FDQN then insert it, otherwise get ID for DNS record insert
            if ($stmt_domain->rowCount() == 0){
                // query to insert record
                $query_insert = "INSERT INTO " . $this->referencing_table_name . " SET fqdn=:fqdn";
            
                // prepare query
                $stmt_insert = $this->conn->prepare($query_insert);
            
                // sanitize
                $this->fqdn=htmlspecialchars(strip_tags($this->fqdn));
            
                // bind values
                $stmt_insert->bindParam(":fqdn", $this->fqdn);
            
                // execute query
                $stmt_insert->execute();

                //  set last inserted ID into domain for DNS record
                $this->domain = $this->conn->lastInsertId();
            }
            //  I ALSO NEED TO IMPLEMENRT CASE WHERE FQDN ALREADY EXIST IN domain TABLE (id from SELECT previous SELECT ), BC 25.10.2021.


            /* DNS record */
            // query to insert DNS record
            $query = "
                INSERT INTO " . $this->table_name . "
                SET type=:type, domain=:domain, name=:name, val=:val, ttl=:ttl
            ";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->type=htmlspecialchars(strip_tags($this->type));
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->val=htmlspecialchars(strip_tags($this->val));
            $this->ttl=htmlspecialchars(strip_tags($this->ttl));
        
            // bind values
            $stmt->bindParam(":type", $this->type);
            $stmt->bindParam(":domain", $this->domain); //  domain doesnt come from GET directly, but ID from domain table
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":val", $this->val);
            $stmt->bindParam(":ttl", $this->ttl);
        
            // execute query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }

    }
?>