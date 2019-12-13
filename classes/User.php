<?php
include 'Database.php';

class User extends Database{

    public function addLogin_User($acname,$email,$uname,$pword){
        $loginsql = "INSERT INTO login(username,password)VALUES('$uname','$pword')";
        $loginResult = $this->con->query($loginsql);

        if($loginResult == TRUE){
            $logid = $this->con->insert_id;

            $usersql = "INSERT INTO users(account_name,email,login_id)VALUES('$acname','$email','$logid')";
            $userResult =$this->con->query($usersql);

            if($userResult == TRUE){
                $ownid = $this->con->insert_id;

                $followsql = "INSERT INTO followed_users(user_id,follow_id)VALUES('$ownid','$ownid')";
                $followResult = $this->con->query($followsql);

                if($followResult == FALSE){
                    echo "error following self".$this->con->connect_error;
                }else{
                    header('location: index.php');
                }

            }else{
                echo "error adding user";
            }
        }else{
            echo "error adding login";
        }
    }

    public function login($uname,$pword){
        $sql = "SELECT * FROM login WHERE username = '$uname' AND password = '$pword' ";
        $result = $this->con->query($sql);

        if($result->num_rows==1){
            $row = $result->fetch_assoc();
            return $row['id'];
        }else{
            return FALSE;
        }
    }

    public function addTweet($id,$text){
        $sql = "INSERT INTO tweets(user_id,text)VALUES('$id','$text')";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot buzz'.$this->con->connect_error);
        }else{
            header('location: home.php');
        }
    }

    public function getCurrentUser($id){
        $sql = "SELECT * FROM users WHERE id = '$id'";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot get user'.$this->con->connect_error);
        }else{
            return $result->fetch_assoc();
        }
    }

    public function getCurrentLogin($id){
        $sql = "SELECT * FROM login WHERE id = '$id'";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot get login'.$this->con->connect_error);
        }else{
            return $result->fetch_assoc();
        }
    }

    public function displayMyBuzz($id){
        $sql = "SELECT * FROM tweets WHERE user_id = '$id' ORDER BY id DESC";
        $result = $this->con->query($sql);

        if($result->num_rows>0){
            $row = array();
            while($rows = $result->fetch_assoc()){
                $row[] = $rows;
            }

            return $row;
        }else{
            return FALSE;
        }
    }

    public function displayFFBuzz($id){
        $sql = "SELECT * FROM tweets INNER JOIN followed_users ON tweets.user_id = followed_users.follow_id INNER JOIN users ON tweets.user_id = users.id WHERE followed_users.user_id = '$id' ORDER BY tweets.id DESC";
        $result = $this->con->query($sql);

        if($result->num_rows>0){
            $row = array();
            while($rows = $result->fetch_assoc()){
                $row[] = $rows;
            }

            return $row;
        }else{
            return FALSE;
        }
    }

    public function displayUsers(){
        $sql = "SELECT * FROM login INNER JOIN users ON login.id = users.login_id";
        $result = $this->con->query($sql);

        if($result->num_rows>0){
            $row = array();
            while($rows = $result->fetch_assoc()){
                $row[] = $rows;
            }

            return $row;
        }else{
            return FALSE;
        }
    }

    public function deleteMyBuzz($id){
        $sql = "DELETE FROM tweets WHERE id = '$id'";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot delete tweet'.$this->con->connect_error);
        }else{
            header('location: profile.php');
        }
    }

    public function followUser($userid,$followid){
        $sql = "INSERT INTO followed_users(user_id,follow_id) VALUES ('$userid','$followid')";
        $result = $this->con->query($sql);
    
        if($result == FALSE){
            die('cannot follow'.$this->con->connect_error);
        }else{
            header('location: userlist.php');
        }

    }

    public function editUser($id,$acname,$uname){
        $sql = "UPDATE users INNER JOIN login ON users.login_id = login.id SET users.account_name = '$acname', login.username = '$uname' WHERE users.id = '$id'";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot edit user'.$this->con->connect_error);
        }else{
            header('location: profile.php');
        }
    }

    public function uploadIcon($id,$name){
        $name = $_FILES["picture"]["name"];
        $target_dir = "uploads/";
        $target_file = $target_dir.basename($_FILES["picture"]["name"]);

        $sql = "UPDATE users SET image = '$name' WHERE id = '$id'";

        $result = $this->con->query($sql);

        if($result == FALSE){
            die('unable to upload photo'.$this->con->connect_error);
        }else{
            move_uploaded_file($_FILES["picture"]["tmp_name"],$target_file);
            header('location: profile.php');
        }
    }


}




?>