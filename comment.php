<?php
include 'action.php';

$now = $_SESSION['login_id'];
$nowuser = $User->getCurrentUser($now);
$nowlogin = $User->getCurrentLogin($now);
$img = $nowuser["image"];

$tweetuserid = $_GET["user_id"];
$tweetid = $_GET["tweet_id"];
$tweetuser = $User->getUser($tweetuserid);
$tweet = $User->getoneTweet($tweetid);

$comments = $User->displayComments($tweetid);
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Buzzer</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/815a8e1e0a.js" crossorigin="anonymous"></script>
    <style>
    *{
      box-sizing: border-box;
      padding: 0;
      margin: 0;
    }
    .sidebar{
      height: 100vh;
    }
    </style>
  </head>
  <body>
      <div class="row">
        <div class="col-md-3 bg-danger sidebar">
          <div class="text-center mt-3 mb-3">
            <h3 class="text-light">MENU</h3>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-4 text-center">
              <?php
              if(empty($img)){
                echo '<i class="fas fa-user fa-5x text-light"></i>';
              }else{
                echo '<img src="uploads/'.$img.'" height="90" width="90" class="rounded-circle">';
              }
              ?>
            </div>
            <div class="col-md-7">
              <h4 class="text-light"><?php echo $nowuser['account_name']; ?></h4>
              <p class="text-light small">@<?php echo $nowlogin['username']; ?></p>
            </div>
          </div>
          <hr>
          <form action="" method="post">
            <div class="form-group">
              <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-7">
                  <input type="text" name="search" class="form-control" placeholder="SEARCH">
                </div>
                <div class="col-md-4">
                  <button type="submit" name="search" class="btn btn-outline-light btn-block">SEARCH</button>
                </div>
              </div>
            </div>
          </form>
          <hr>
          <div class="mt-3">
            <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-11">
                <ul style="list-style: none;">
                  <li><a href="home.php" class="text-light">HOME</a></li>
                  <br>
                  <li><a href="favorite.php" class="text-light">FAVORITES</a></li>
                  <br>
                  <li><a href="message.php" class="text-light">MESSAGE</a></li>
                  <br>
                  <li><a href="userlist.php" class="text-light">USERLIST</a></li>
                  <br>
                  <li><a href="profile.php" class="text-light">PROFILE</a></li>
                </ul>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-11">
                <ul style="list-style: none;">
                  <li><a href="" class="text-light">SETTING</a></li>
                  <br>
                  <li><a href="" class="text-light">HELP</a></li>
                  <br>
                  <li><a href="logout.php" class="text-dark">LOGOUT</a></li>
                </ul>
              </div>
            </div>
          </div>
          <hr>
          <div class="text-center">
            <p class="small text-light pt-3">BUZZER</p>
          </div>
        </div>

        <div class="col-md-4 mt-5">
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header bg-dark">
                        <div class="row">
                            <div class="col-md-4 text-center">
                            <?php
                            $image = $tweetuser["image"];
                            if(empty($image)){
                                echo '<i class="fas fa-user fa-5x text-light"></i>';
                            }else{
                                echo '<img src="uploads/'.$image.'" height="90" width="90" class="rounded-circle">';
                            }
                            ?>
                            </div>
                            <div class="col-md-8">
                                <h4 class="text-light"><?php echo $tweetuser['account_name']; ?></h4>
                                <h4 class="text-light small">@<?php echo $tweetuser['username']; ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="border" style="height: 25vh;">
                            <?php
                            echo $tweet["text"];
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 border-left" style="position: relative;">
            <h5 class="display-4 text-danger">Comments</h5>
            <hr>
            <div style="height: 75vh; overflow: scroll;">
                <?php
                foreach($comments as $key=>$comment){
                    $commenterid = $comment["user_id"];
                    $commenter = $User->getUser($commenterid);

                    if($commenterid == $now){
                        echo '<div class="alert alert-danger">';
                            echo '<strong>'.$commenter["account_name"].'</strong>';
                            echo '<br>';
                            echo $comment["text"];
                        echo '</div>';
                    }else{
                        echo '<div class="alert alert-dark">';
                        echo '<strong>'.$commenter["account_name"].'</strong>';
                            echo '<br>';
                            echo $comment["text"];
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <form action="action.php" method="post" style="position: absolute; bottom: 0; width: 90%">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-8">
                            <input type="text" name="text" class="form-control">
                            <input type="hidden" name="now_id" value="<?php echo $now; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $tweetuserid; ?>">
                            <input type="hidden" name="tweet_id" value="<?php echo $tweetid; ?>">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="comment" class="btn btn-danger btn-block w-50 mx-auto">COM</button>
                        </div>
                    </div>
                </div>
                <br>
            </form>
        </div>
      </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>