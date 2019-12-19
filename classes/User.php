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

    public function getUser($id){
        $sql = "SELECT * FROM users INNER JOIN login ON users.login_id = login.id WHERE users.id = '$id'";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot get user'.$this->con->connect_error);
        }else{
            return $result->fetch_assoc();
        }
    }

    public function displayMyBuzz($id){
        $sql = "SELECT * FROM tweets WHERE user_id = '$id' ORDER BY tweet_id DESC";
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

    public function displayUserBuzz($id){
        $sql = "SELECT * FROM tweets WHERE user_id = '$id' ORDER BY tweet_id DESC";
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
        $sql = "SELECT * FROM tweets INNER JOIN followed_users ON tweets.user_id = followed_users.follow_id INNER JOIN users ON tweets.user_id = users.id WHERE followed_users.user_id = '$id' ORDER BY tweets.tweet_id DESC";
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
        $sql = "DELETE FROM tweets WHERE tweet_id = '$id'";
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

    public function unfollowUser($userid,$followid){
        $sql = "DELETE FROM followed_users WHERE user_id = '$userid' AND follow_id = '$followid'";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot unfollow'.$this->con->connect_error);
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

    public function validateFollow($now,$user){
        $sql = "SELECT * FROM followed_users WHERE user_id = '$now' AND follow_id = '$user'";
        $result = $this->con->query($sql);

        if($result->num_rows == 1){
            return "unfollow";
        }else{
            return "follow";
        }
    }

    public function favorite($userid,$tweetid){
        $sql = "INSERT INTO favorites(user_id,tweet_id)VALUES('$userid','$tweetid')";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot favorite'.$this->con->connect_error);
        }else{
            header('location: home.php');
        }
    }

    public function unFavorite($userid,$tweetid){
        $sql = "DELETE FROM favorites WHERE user_id = '$userid' AND tweet_id = '$tweetid'";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot unfavorite'.$this->con->connect_error);
        }else{
            header('location: home.php');
        }
    }

    public function validateFav($userid,$tweetid){
        $sql = "SELECT * FROM favorites WHERE user_id = '$userid' AND tweet_id = '$tweetid'";
        $result = $this->con->query($sql);

        if($result->num_rows == 1){
            return "favorite";
        }else{
            return "not";
        }
    }

    public function countFav($id){
        $sql = "SELECT id FROM favorites WHERE tweet_id = '$id'";
        $result = $this->con->query($sql);

        if($result->num_rows>0){
            $row = array();
            while($rows = $result->fetch_assoc()){
                $row[] = $rows;
            }

            return count($row);
        }else{
            return 0;
        }
    }

    public function displayFav($id){
        $sql = "SELECT * FROM favorites INNER JOIN tweets ON favorites.tweet_id = tweets.tweet_id INNER JOIN users ON tweets.user_id = users.id WHERE favorites.user_id = '$id' ORDER BY tweets.tweet_id DESC";
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

    public function displayFollowedUsers($id){
        $sql = "SELECT * FROM users INNER JOIN login ON users.login_id = login.id INNER JOIN followed_users ON users.id = followed_users.follow_id WHERE followed_users.user_id = '$id'";
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

    public function sendDM($userid,$followid,$text){
        $sql = "INSERT INTO messages(user_id, follow_id, text)VALUES('$userid','$followid','$text')";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot send DM'.$this->con->connect_error);
        }
    }

    public function displayDM($userid,$followid){
        $sql = "SELECT * FROM messages WHERE (user_id = '$userid' AND follow_id = '$followid') OR (user_id = '$followid' AND follow_id = '$userid')";
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

    public function getoneTweet($id){
        $sql = "SELECT * FROM tweets WHERE tweet_id = '$id'";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot get buzz'.$this->con->connect_error);
        }else{
            return $result->fetch_assoc();
        }
    }

    public function sendComment($userid,$tweetid,$text){
        $sql = "INSERT INTO comments(user_id, tweet_id, text)VALUES('$userid','$tweetid','$text')";
        $result = $this->con->query($sql);

        if($result == FALSE){
            die('cannot send comment'.$this->con->connect_error);
        }
    }

    public function displayComments($id){
        $sql = "SELECT * FROM comments WHERE tweet_id = '$id'";
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

    public function countCom($id){
        $sql = "SELECT comment_id FROM comments WHERE tweet_id = '$id'";
        $result = $this->con->query($sql);

        if($result->num_rows>0){
            $row = array();
            while($rows = $result->fetch_assoc()){
                $row[] = $rows;
            }

            return count($row);
        }else{
            return 0;
        }
    }


}




?>