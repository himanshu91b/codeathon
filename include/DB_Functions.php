<?php



class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $uuid, $name, $email, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }
    
    public function getnc()
    {
            $stmt = $this->conn->prepare("SELECT * FROM nc where status = 0");
            $stmt->execute();
            $nc = array();
            $result = $stmt->get_result();
            
            while($record = $result->fetch_assoc()){
                
                array_push($nc, $record);
            }
            $stmt->close();
            return $nc;
            
    }
    
    
    public function update($help_id)
    {
        $stmt = $this->conn->prepare("UPDATE help SET status = 1 WHERE help_id = $help_id ");
        $stmt->execute();
        $stmt->close();
        return true;
        
    }
    
    public function update_ask($ask_id)
    {
        $stmt = $this->conn->prepare("UPDATE askhelp SET status = 1 WHERE ask_id = $ask_id ");
        $stmt->execute();
        $stmt->close();
        return true;
        
    }

        public function addhelp($nc_id, $provider_id, $p_cap, $comment) {
        // echo "INSERT INTO help(nc_id, provider_id, p_cap, comment) VALUES($nc_id, $provider_id ,'".$p_cap."', '".$comment."')";
        $stmt = $this->conn->prepare("INSERT INTO help(nc_id, provider_id, p_cap, comment) VALUES($nc_id, $provider_id ,'".$p_cap."', '".$comment."')");
        $result = $stmt->execute();
        $stmt->close();

         return true;
  }  
    
  
  public function askhelp($nc_id, $provider_id, $comment) {
        // echo "INSERT INTO help(nc_id, provider_id, p_cap, comment) VALUES($nc_id, $provider_id ,'".$p_cap."', '".$comment."')";
        $stmt = $this->conn->prepare("INSERT INTO askhelp(nc_id, provider_id, comment) VALUES($nc_id, $provider_id, '".$comment."')");
        $result = $stmt->execute();
        $stmt->close();

         return true;
  }  
    
     public function addNc($name, $location, $affected_area, $intensity) {
       
         
        $stmt = $this->conn->prepare("INSERT INTO nc(name, location, affected_area, intensity) VALUES('".$name."', '".$location."' , '".$affected_area."', $intensity)");
        $result = $stmt->execute();
        $stmt->close();

         return true;
    }
    
   
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");

        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}

?>
