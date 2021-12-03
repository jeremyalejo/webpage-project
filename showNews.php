<?php
    require('connect.php');
    
    if(session_status() !== PHP_SESSION_ACTIVE) 
    { 
        session_start(); 
    } 
	// retrieves posts to be edited, if id GET parameter is in URL. 
	if (isset($_GET['newsID'])) { 
        $newsID = filter_input(INPUT_GET, 'newsID', FILTER_SANITIZE_NUMBER_INT);
        
        $query = "SELECT * FROM news WHERE newsID = :newsID";
        $statement = $db->prepare($query);
        $statement->bindValue(':newsID', $newsID, PDO::PARAM_INT);
        
        $statement->execute();
        $row = $statement->fetch();

        $commentQuery = "SELECT * FROM comments WHERE newsID = :newsID ORDER BY date_updated DESC";
        $statement2 = $db->prepare($commentQuery);
        $statement2->bindValue(':newsID', $newsID, PDO::PARAM_INT);
        
        $statement2->execute();

        if($_SESSION['currentUser'] != "Guest"){
            $userID = $_SESSION['userID'];
            $userQuery = "SELECT * FROM users WHERE userID = :userID";
            $statement3 = $db->prepare($userQuery);
            $statement3->bindValue(':userID', $userID, PDO::PARAM_INT);
            
            $statement3->execute();
            $users = $statement3->fetch();
        }

    } 
    else {
        $newsID = false; 
    }
    $counter = 0;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?=$row['title']?></title>
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
        <header class="masthead" style="background-image: url('assets/img/newsBG.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-10 col-xl-7">
                        <div class="site-heading">
                            <h1>Hot NEWS</h1>
                            <span class="subheading">"<?= $row['title']?>"</span>
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
                    <div id = "newsForm">
                        <div class = "blog_post">
                            <h2><?= $row['title']?></h2>
                            <p>
                                <?php 
                                    $orgDate = $row['date_released'];  
                                    $newDate = date("F d, Y", strtotime($orgDate));  
                                ?>
                                <small><?= $newDate?> 
                                    <?php if($_SESSION['currentUser'] == "Admin jeremy") :?>
                                       - <a href="editNews.php?newsID=<?= $_GET['newsID']?>">edit</a>
                                    <?php endif ?>
                                </small>
                            </p>
                        </div>
                        
                        <div class='blog_content'>
                            <p><?= $row['content'] ?></p>
                        </div>
		            </div></br>

                    <div id = "commentSection">
                        <h3>Comments</h3>
                        <?php while($comments = $statement2->fetch()):?>
                            
                            <?php if($statement2 -> rowcount() > 0): ?>
                                <h4><?= $comments['title']?></h4>
                            <p>
                                <?php 
                                    $orgDate = $comments['date_updated'];  
                                    $newDate = date("F d, Y", strtotime($orgDate));  
                                ?>
                                <small><?= $newDate?> 
                                    <?php if($_SESSION['currentUser'] != "Guest") :?>
                                    - <a href="editComment.php?commentID=<?= $comments['commentID']?>&newsID=<?= $comments['newsID']?>">edit</a>
                                    <?php endif ?>
                                </small>
                            </p>
                            <p><?= $comments['content'] ?></p>
                            <?php endif?>
                        <?php endwhile?>
                        
                        <?php if($_SESSION['currentUser'] != "Admin jeremy" && $_SESSION['currentUser'] != "Guest") :?>
                                <?php if($statement2 -> rowcount() > 0): ?>
                                    <p><a href="createComment.php?newsID=<?= $_GET['newsID']?>">Add a comment.</a></p>

                                <?php else: ?>
                                    <p>No comments yet, <a href="createComment.php?newsID=<?= $_GET['newsID']?>">Add a comment.</a></p>
                                <?php endif?>
                        <?php endif ?>
                        
                        <?php if($_SESSION['currentUser'] == "Admin jeremy" || $_SESSION['currentUser'] == "Guest") : ?>
                            <?php if($statement2 -> rowcount() == 0): ?>
                                <p>No comments yet.</p>
                            <?php endif?>
                        <?php endif ?>
                        
                        
    
                    </div>
                    <?php if(empty($newsID))  :?>
                        <?= header("Location: home.php") ?>
                        <?= exit() ?>
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
