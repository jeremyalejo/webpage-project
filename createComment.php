<?php
    ob_start();

    require("connect.php");
    
    if(session_status() !== PHP_SESSION_ACTIVE) 
    { 
        session_start(); 
    } 
    
    // if post is created and title and content fields are not empty
    if ($_POST && isset($_SESSION['userID']) && isset($_GET['newsID']) && !empty($_POST['title']) && !empty($_POST['content'])){
        require('connect.php');

        $newsID = filter_input(INPUT_GET, 'newsID', FILTER_SANITIZE_NUMBER_INT);
        $userID = $_SESSION['userID'];
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $query = "INSERT INTO comments (newsID, userID, title, content) VALUES (:newsID, :userID, :title, :content)";
        $statement = $db->prepare($query);

        $statement->bindValue(":newsID", $newsID);
        $statement->bindValue(":userID", $userID);
        $statement->bindValue(":title", $title);
        $statement->bindValue(":content", $content);
        
        $statement->execute();
        header("Location: showNews.php?newsID=".$_GET['newsID']);
        exit();
        ob_end_flush();
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
        <title>Create a comment</title>
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
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="profile.php">Profile</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link px-lg-3 py-3 py-lg-4" href="#" >Game Info<span class="fa fa-caret-down"></span></a>
                            <ul class="dropdown-menu">
                                <a class="nav-link px-lg-3 py-3 py-lg-3" href="agents.php"><span>Agents</span></a>
                                <a class="nav-link px-lg-3 py-3 py-lg-3" href="gamemodes.php"><span>Game Modes</span></a>
                                <a class="nav-link px-lg-3 py-3 py-lg-3" href="maps.php"><span>Maps</span></a>
                            </ul>   
                        </li>
                        <?php if($_SESSION['currentUser'] != "Guest") :?>
                            <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="logout.php">Logout</a></li>
                            <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4">Welcome, <?= $_SESSION['currentUser'] ?></a></li>
                        <?php else :?>
                            <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="logout.php">Login</a></li>
                            <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4">Welcome, <?= $_SESSION['currentUser'] ?></a></li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page Header-->
        <header class="masthead" style="background-image: url('assets/img/commentBG.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-10 col-xl-7">
                        <div class="site-heading">
                            <h1>Create a comment </h1>
                            <span class="subheading">Be nice.</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content-->
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <!-- Post preview-->
                    <div class = "form">
                        <form action="createComment.php?newsID=<?= $_GET['newsID']?>" method="post">
                            <fieldset>
                                <legend><h3>What's on your mind?</h3></legend>
                                    <p>
                                        <label for="title">Title</label><br>
                                        <input type = "text" name="title" id="title" size="50" />
                                    </p>
                                    <p>
                                        <label for="content">Content</label><br>
                                        <textarea name="content" id="content" rows="4" cols="70"></textarea>
                                    </p>
                                    <p>
                                        <input type="submit" name="submit" value="Create" />
                                    </p>
                            </fieldset>
                        </form>

                        <?php if(isset($_POST['submit'])) : ?>
                            <?php if(strlen($title) == 0 || strlen($content) == 0) : ?>
                                <h1>The title or content of the users post should have at least 1 character.</h1>
                                <a href="home.php">Return home</a>
                            <?php else : ?>
                                <?= header("Location: showNews.php?newsID=".$_GET['newsID']) ?>
                                <?= exit() ?>
                            <?php endif ?>
                        <?php endif ?>
		            </div>
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
