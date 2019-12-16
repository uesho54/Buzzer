<?php
include 'classes/User.php';

session_start();

$User = new User;

if(isset($_POST["register"])){

    $acname = $_POST["acname"];
    $uname = $_POST["uname"];
    $email = $_POST["email"];
    $pword = md5($_POST["pword"]);

    $User->addLogin_User($acname,$email,$uname,$pword);

}elseif(isset($_POST["login"])){

    $uname = $_POST["uname"];
    $pword = md5($_POST["pword"]);

    $id = $User->login($uname,$pword);

    if($id == TRUE){
        $_SESSION['login_id'] = $id;
        header('location: home.php'); 
    }else{
        echo "USER DOESN'T EXIST";
    }

}elseif(isset($_POST["buzz"])){

    $userid = $_POST["user"];
    $text = $_POST["text"];

    $User->addTweet($userid,$text);

}elseif(isset($_POST["follow"])){

    $userid = $_POST["user_id"];
    $followid = $_POST["follow_id"];

    $User->followUser($userid,$followid);
}elseif(isset($_POST["unfollow"])){

    $userid = $_POST["user_id"];
    $followid = $_POST["follow_id"];

    $User->unfollowUser($userid,$followid);

}elseif(isset($_POST["icon"])){

    $userid = $_POST["userid"];
    $name = $_FILES["picture"]["name"];

    $User->uploadIcon($userid,$name);

}elseif(isset($_POST["edit"])){

    $userid = $_POST["userid"];
    $acname = $_POST["acname"];
    $uname = $_POST["uname"];

    $User->editUser($userid,$acname,$uname);

}elseif(isset($_POST["unfav"])){

    $userid = $_POST["user_id"];
    $tweetid = $_POST["tweet_id"];

    $User->unFavorite($userid,$tweetid);

}elseif(isset($_POST["fav"])){

    $userid = $_POST["user_id"];
    $tweetid = $_POST["tweet_id"];

    $User->favorite($userid,$tweetid);

}




?>