<?php
    // Code for registering a user into the database.
    require("connect.php");

    $registerUsername = filter_input(INPUT_POST, 'registerUsername', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $registerPassword = filter_input(INPUT_POST, 'registerPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $emailaddress = filter_input(INPUT_POST, 'registerEmail', FILTER_SANITIZE_EMAIL);
    $hashPassword = password_hash($registerPassword, PASSWORD_DEFAULT);
    $registerUsers = "INSERT INTO users (userID, username, password, emailAddress, userType) VALUES (NULL, '$registerUsername', '$hashPassword', '$emailaddress', 'Authenticated User')";

    if(isset($_POST['register'])) {
        
        if($_POST['registerPassword'] == $_POST['securePassword']){
            $registerExecute = $db -> prepare($registerUsers);
            $registerExecute -> execute();
            header("Location: index.php");
        }
        else{
            header("Location: register.php");
        }
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
                <div id="registerForm">
                    <form action="register.php" method="post">
                        <h2>Register Account</h2>
                        <div id="registrationEmail" style="margin-top: 25px">
                            <input type="email" placeholder="Enter Email Address" id="registerEmail" name="registerEmail" required>
                        </div>
                        <div id="registrationUsername" style="margin-top: 10px">
                            <input type="text" placeholder="Create Username" id="registerUsername" name="registerUsername" required>
                        </div>
                        <div id="registrationPassword" style="margin-top: 10px">
                            <input type="password" placeholder="Create Password" id="registerPassword" name="registerPassword" required>
                        </div>
                        <div id="registrationPasswordSecure" style="margin-top: 10px">
                            <input type="password" placeholder="Retype Password" id="securePassword" name="securePassword" required>
                        </div><br>
                        <div class="elem-group">
                            <label for="captcha">Please Enter the Captcha Text</label></br>
                            <img src="captcha.php" alt="CAPTCHA" class="captcha-image">
                            <br>
                            <input type="text" id="captcha" name="captcha" pattern="[A-Z]{6}">
                        </div>
                        <div id="submit" style="margin-top: 20px">
                            <input type="submit" id="register" name="register" value="Register">
                            <input type="button" name="back" value="Back" onclick="window.location.href='index.php'">
                        </div>
                    </form>
                </div>
                <?php if(isset($_POST['register'])) :?>
                    <script>
                        document.querySelector('#register').onclick = registerUser();
                        
                        function registerUser() {
                            var password = document.querySelector("#registerPassword").value,
                                hashedPassword = document.querySelector("#securePassword").value;

                            if (password == hashedPassword) {
                                alert("Account is now registered.");
                            }
                            else if (password != hashedPassword) {
                                alert("Passwords do not match. Try again");
                                return false;
                            } 
                            return true;
                        }
                    </script>
                <?php endif ?>
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
