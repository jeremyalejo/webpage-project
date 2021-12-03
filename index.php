<?php
    session_start();        
    require("connect.php");
    $loginID = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_NUMBER_INT);
    $loginUser = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $loginPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $alert = "";

    if(isset($_POST['login'])) {
        $findUser = "SELECT * FROM `users` WHERE `username` = '$loginUser'";
        $executeFind = $db -> prepare($findUser);
        $executeFind -> execute();
        $checkRow = $executeFind -> rowCount();
    
        if($_POST && $checkRow == 1){
            $userRow = $executeFind -> fetch();
            $verifiedPassword = $userRow['password'];

            if($userRow['userType'] == 'Admin'){
                if(password_verify($loginPassword, $verifiedPassword)){
                    $_SESSION['currentUser'] = 'Admin ' . $loginUser;
                    $_SESSION['userID'] = $userRow['userID'];
                    header("Location: home.php");
                }
                else{
                    $alert = "Login failed. Password was not verified.";
                        
                }
            }
            else{
                if(password_verify($loginPassword, $verifiedPassword)){
                    $_SESSION['currentUser'] = $loginUser;
                    $_SESSION['userID'] = $userRow['userID'];
                    header("Location: home.php");
                }
                else{
                    $alert = "Login failed. Password was not verified.";
                }
            }
            
        }
        else{
            $alert = "Login failed.";
        }
    }
    
    if(isset($_POST['guestLogin'])){
        $_SESSION['currentUser'] = "Guest";
        $_SESSION['userID'] = "Guest";
        $alert = "Logging in as guest";
        header("Location: home.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Welcome</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="home.php">JA Company</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                
            </div>
        </nav>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/homeBG.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="site-heading">
                            <h1>Welcome!</h1>
                            <span class="subheading">A Walkthrough to Valorant for "NEWBIES"</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
        <div class="container px-4 px-lg-5">
            <div class="row gx-3 gx-lg-4 justify-content-center">
                <div class="col-md-10 col-lg-10 col-xl-7">
                <div id="login">
                    <form action="index.php" method="post">
                        <fieldset>
                            <h2>Log in </h2>
                            <div id="username" style="margin-top: 25px">
                                <label for="username">Username: </label>
                                <input type="text" placeholder="Enter Username" name="username" id="username">
                            </div>
                            <div id="password" style="margin-top: 10px">
                                <label for="password">Password: </label>
                                <input type="password" placeholder="Enter Password" name="password" id="password">
                            </div>
                            <div id="register">
                                <a href="register.php">Not a user yet? Register!</a>
                            </div>
                            <div id="submit" style="margin-top: 20px">
                                <input type="submit" name="login" value="Log in">
                                <input type="submit" name="guestLogin" value="Guest Login">
                            </div>
                        </fieldset>
                    </form>
                    <?php if($alert) : ?>
                        <?= $alert ?>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <!-- Footer-->
        <footer class="border-top">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <ul class="list-inline text-center">
                            <li class="list-inline-item">
                                <a href="#!">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#!">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-instagram fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#!">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-discord fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <div class="small text-center text-muted fst-italic">Copyright &copy; Jeremy Alejo - 2021</div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
