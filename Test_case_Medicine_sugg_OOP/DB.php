<?php

if (!class_exists('DB')) {
   class DB{
        private $dbHost     = "localhost";
        private $dbUsername = "root";
        private $dbPassword = "";
        private $dbName     = "medicine information management system";
        public $var = '0';

        public function __construct(){
            
            if(!isset($this->db)){
                // Connect to the database
                try{
                    $conn = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword);
                    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->db = $conn;
                    $this->var = '1';   
                    //echo $var;
                }catch(PDOException $e){
                    die("Failed to connect with MySQL: " . $e->getMessage());
                }
            }
        }

        public function checkDBConnection(){
            if($this->var == '0'){
                return false;
            }
            else{
                return true;
            }
        }
        
        /*
         * Returns rows from the database based on the conditions
         * @param string name of the table
         * @param array select, where, order_by, limit and return_type conditions
         */
        public function getRows($table,$conditions = array()){
            $sql = 'SELECT ';
            $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
            $sql .= ' FROM '.$table;
            if(array_key_exists("where",$conditions)){
                $sql .= ' WHERE ';
                $i = 0;
                foreach($conditions['where'] as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $sql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            
            if(array_key_exists("order_by",$conditions)){
                $sql .= ' ORDER BY '.$conditions['order_by'];
            }
            
            if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
                $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
            }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
                $sql .= ' LIMIT '.$conditions['limit']; 
            }
            
            $query = $this->db->prepare($sql);
            $query->execute();
            
            if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
                switch($conditions['return_type']){
                    case 'count':
                        $data = $query->rowCount();
                        break;
                    case 'single':
                        $data = $query->fetch(PDO::FETCH_ASSOC);
                        break;
                    default:
                        $data = '';
                }
            }else{
                if($query->rowCount() > 0){
                    $data = $query->fetchAll();
                }
            }
            return !empty($data)?$data:false;
        }
        
        /*
         * Insert data into the database
         * @param string name of the table
         * @param array the data for inserting into the table
         */
        public function insert($table,$data){
            if(!empty($data) && is_array($data)){
                $columns = '';
                $values  = '';
                $i = 0;

                // if(!array_key_exists('created',$data)){
                //     $data['created'] = date("Y-m-d H:i:s");
                // }
                // if(!array_key_exists('modified',$data)){
                //     $data['modified'] = date("Y-m-d H:i:s");
                // }

                $columnString = implode(',', array_keys($data));
                $valueString = ":".implode(',:', array_keys($data));
                $sql = "INSERT INTO ".$table." (".$columnString.") VALUES (".$valueString.")";
                $query = $this->db->prepare($sql);
                foreach($data as $key=>$val){
                     $query->bindValue(':'.$key, $val);
                }
                $insert = $query->execute();
                return $insert?$this->db->lastInsertId():false;
            }
            else{
                return false;
            }
        }
        
        /*
         * Update data into the database
         * @param string name of the table
         * @param array the data for updating into the table
         * @param array where condition on updating data
         */
        public function update($table,$data,$conditions){
            if(!empty($data) && is_array($data)){
                $colvalSet = '';
                $whereSql = '';
                $i = 0;
                if(!array_key_exists('modified',$data)){
                    $data['modified'] = date("Y-m-d H:i:s");
                }
                foreach($data as $key=>$val){
                    $pre = ($i > 0)?', ':'';
                    $colvalSet .= $pre.$key."='".$val."'";
                    $i++;
                }
                if(!empty($conditions)&& is_array($conditions)){
                    $whereSql .= ' WHERE ';
                    $i = 0;
                    foreach($conditions as $key => $value){
                        $pre = ($i > 0)?' AND ':'';
                        $whereSql .= $pre.$key." = '".$value."'";
                        $i++;
                    }
                }
                $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
                $query = $this->db->prepare($sql);
                $update = $query->execute();
                return $update?$query->rowCount():false;
            }else{
                return false;
            }
        }
        
        /*
         * Delete data from the database
         * @param string name of the table
         * @param array where condition on deleting data
         */
        /*public function delete($table,$conditions){
            $whereSql = '';
            if(!empty($conditions)&& is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            $sql = "DELETE FROM ".$table.$whereSql;
            $delete = $this->db->exec($sql);
            return $delete?$delete:false;
        }*/


        public function showInfo($getCompany, $getGenre){

            if(!empty($getCompany) AND empty($getGenre) ) {
              $stmt = $this->db->prepare("SELECT * FROM medicine WHERE maker_company = '$getCompany'");
              //$sql = "SELECT count(*) FROM `medicine` WHERE maker_company = '$getCompany'"; 
            }
            else if(!empty($getGenre) AND empty($getCompany) ) {
              $stmt = $this->db->prepare("SELECT * FROM medicine WHERE genre = '$getGenre'");
              //$sql = "SELECT count(*) FROM `medicine` WHERE genre = '$getGenre'";
            }
            else if(!empty($getGenre) AND !empty($getCompany) ) {
              $stmt = $this->db->prepare("SELECT * FROM medicine WHERE maker_company = '$getCompany' AND genre = '$getGenre'");
              //$sql = "SELECT count(*) FROM `medicine` WHERE maker_company = '$getCompany' AND genre = '$getGenre'";
            }
            else if(empty($getGenre) AND empty($getCompany) ) {
              echo "Please Select At Least One Field !!";
              //$nRows = 0;
              exit;
            }

           // $query  = $this->db->prepare($stmt);
            
            return $stmt;
            /*$result = $this->db->prepare($sql); 
            $result->execute(); 
            $nRows = $result->fetchColumn();
            return array($nRows, $stmt);*/
        }


        public function medInfo($getCompany, $getGenre){

            if(!empty($getCompany) AND empty($getGenre) ) {
              //$stmt = $this->db->prepare("SELECT * FROM medicine WHERE maker_company = '$getCompany'");
              $sql = "SELECT count(*) FROM `medicine` WHERE maker_company = '$getCompany'"; 
            }
            else if(!empty($getGenre) AND empty($getCompany) ) {
              //$stmt = $this->db->prepare("SELECT * FROM medicine WHERE genre = '$getGenre'");
              $sql = "SELECT count(*) FROM `medicine` WHERE genre = '$getGenre'";
            }
            else if(!empty($getGenre) AND !empty($getCompany) ) {
              //$stmt = $this->db->prepare("SELECT * FROM medicine WHERE maker_company = '$getCompany' AND genre = '$getGenre'");
              $sql = "SELECT count(*) FROM `medicine` WHERE maker_company = '$getCompany' AND genre = '$getGenre'";
            }
            else if(empty($getGenre) AND empty($getCompany) ) {
              echo "Please Select At Least One Field !!";
              $nRows = 0;
              exit;
            }

           // $query  = $this->db->prepare($stmt);
            

            $result = $this->db->prepare($sql); 
            $result->execute(); 
            $nRows = $result->fetchColumn();
            return $nRows;
        }

        public function suggestMedicine($disease){
            if(isset($_POST['search'])) {

                  $stmt = $this->db->prepare("SELECT * FROM medicine WHERE disease_name LIKE '%$disease%'");
                  $stmt->execute();
                  $results = $stmt->fetchAll();
                  $num_rows = mysql_num_rows($result);
                  return $num_rows;

            }
        }

        public function login($username, $password){
            try {
                $stmt = $this->db->prepare('SELECT * FROM user WHERE username = :username');
                $stmt->execute(array(
                  ':username' => $username
                  ));
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if($data == false){
                  $errMsg = "User $username not found.";
                }
                else {
                  if($password == $data['password']) {
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['password'] = $data['password'];

                    header('Location: dashboard.php');
                    exit;
                  }
                  else
                    $errMsg = 'Password not match.';
                }
              }
            catch(PDOException $e) {
                $errMsg = $e->getMessage();
            }
        }

        public function delete($id){

            $stmt = $this->db->prepare("DELETE FROM medicine WHERE med_id='$id'");
            $stmt->execute();
            header('Location: dashboard.php?action=removed');
            exit;
        }

        public function checkEmpty($genre, $name, $med_type, $company, $disease_name, $description){
            
            $isEmpty = '0';
            
            if(empty($genre) || empty($name) || empty($med_type) || empty($company) || empty($disease_name) || empty($description)){
               
                $isEmpty = '1';
                //echo $isEmpty;
                //exit;
            }
            return $isEmpty;
        }

        public function checkNumeric($genre, $name, $med_type, $company, $disease_name, $description){
            
            $isNumeric = '0';
            if(ctype_digit($genre) || ctype_digit($name) || ctype_digit($med_type) || ctype_digit($company) || ctype_digit($disease_name) || ctype_digit($description)){
                  
                  $isNumeric = '1';
              }
              return $isNumeric;
        }
    }
}

?>