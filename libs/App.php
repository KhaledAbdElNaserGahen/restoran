<?php
    class App{
        public $host=HOST;
        public $dbname=DBNAME;
        public $user=USER;
        public $pass=PASS;

        public $link;

        //create construct

        public function __construct(){
            $this->connect();
        }
        public function connect(){
            $this->link =new PDO("mysql:host=".$this->host.";dbname=".$this->dbname."",$this->user,$this->pass);
            
        }
         //select all data 
        public function selectAll ($query){
            $rows = $this->link->query($query);
            $rows->execute();

            $allRows = $rows->fetchAll(PDO::FETCH_OBJ);

            if($allRows){
                return $allRows;
            }else{
                return false;
            }
        }

        //validate cart
        public function validateCart($q){
            $row = $this->link->query($q);
            $row->execute();
            $count = $row->rowCount();
            return $count;
        }

        // Seclect one row
        public function selectOne ($query){
            $row = $this->link->query($query);
            $row->execute();

            $singleRow = $row->fetch(PDO::FETCH_OBJ);

            if($singleRow){
                return $singleRow;
            }else{
                return false;
            }
        }
        //Insert Function

        public function insert($query, $arr , $path){
            if($this->validate($arr) == "empty"){
                echo "<script>alert('one or more inputs are empty')</script>";
            }else {
                $insert_record = $this->link->prepare($query);
                $insert_record->execute($arr);

                echo "<script>window.location.href='".$path."'</script>";
            }
        }

        //update function

        public function update($query, $arr , $path){
            
            $update_record = $this->link->prepare($query);
            $update_record->execute($arr);

            header("location: ".$path."");
            
        }

        //dalete function

        public function dalete($query, $path){

                $dalete_record = $this->link->prepare($query);
                $dalete_record->execute();

                echo "<script>window.location.href='".$path."'</script>";
            
        }


        public function validate($arr) {
            if(in_array("",$arr)){
                echo "empty";
            }
        }
        // register function
        public function register($query, $arr , $path){
            if($this->validate($arr) == "empty"){
                echo "<script>alert('one or more inputs are empty')</script>";
            }else {
                $register_user = $this->link->prepare($query);
                $register_user->execute($arr);

                header("location: ".$path."");
            }
        }

        // login function
        public function login($query, $data , $path){
            //email
            $login_user = $this->link->prepare($query);
            $login_user->execute();

            $fetch = $login_user->fetch(PDO::FETCH_ASSOC);

            if($login_user->rowCount() > 0){
                
                if(password_verify($data['password'],$fetch['password'])){

                    // start session var
                    $_SESSION['email'] = $fetch['email'];
                    $_SESSION['username'] = $fetch['username'];
                    $_SESSION['user_id'] = $fetch['id'];

                    header("location: ".$path."");
                }
            }
        }

        // starting session
        public function startingSession(){
            session_start();
        }

        // validating session
        public function validateSession(){
            if(isset($_SESSION['user_id'])){
                echo "<script>window.location.href='".APPURL."'</script>";
            }
        }

        public function validateSessionAdmin(){
            if(isset($_SESSION['email'])){
                echo "<script>window.location.href='".ADMINURL."/index.php'</script>";
            }
        }

        public function validateSessionAdminInside(){
            if(!isset($_SESSION['email'])){
                echo "<script>window.location.href='".ADMINURL."/admins/login-admins.php'</script>";
            }
        }

    }



?>