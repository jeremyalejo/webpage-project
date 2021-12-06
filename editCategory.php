<?php
    if(session_status() !== PHP_SESSION_ACTIVE) 
    { 
        session_start(); 
    } 
    require('connect.php');
    
    $categoryID = filter_input(INPUT_POST, 'categoryID', FILTER_SANITIZE_NUMBER_INT);
    
    // DELETE post if delete button is pressed
    if($_POST && !empty($_POST['btnDelete'])){
    	$query     = "DELETE FROM categories WHERE categoryID = :categoryID";
    	$statement = $db->prepare($query);
    	$statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);

    	$statement->execute();

    	header("Location: home.php");
        exit();
    } 
    // UPDATE post if name, description and id are present in POST.
    else if ($_POST && !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['categoryID'])) {
        $name  = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

 		$query     = "UPDATE categories SET name = :name, description = :description WHERE categoryID = :categoryID";
        $statement = $db->prepare($query);
	   	$statement->bindValue(':name', $name);       
	   	$statement->bindValue(':description', $description);
	   	$statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
	       
    	$statement->execute();

        header("Location: home.php");
        exit();
    } 
    else if (!empty($_GET['categoryID'])) { 
        $categoryID = filter_input(INPUT_GET, 'categoryID', FILTER_SANITIZE_NUMBER_INT);
       
        $query = "SELECT * FROM categories WHERE categoryID = :categoryID";
        $statement = $db->prepare($query);
        $statement->bindValue(':categoryID', $categoryID, PDO::PARAM_INT);
        
        $statement->execute();

        $row = $statement->fetch();
    } 
    // if we are not UPDATING or SELECTING.
    else {
        $categoryID = false; 
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Edit a comment</title>
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
        <header class="masthead" style="background-image: url('assets/img/category.jpg')">
            <div class="container position-relative px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-10 col-xl-7">
                        <div class="site-heading">
                            <h1>Edit categories</h1>
                            <span class="subheading">Fix it nicely.</span>
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
                        <form action="editCategory.php" method="post">
                            <fieldset>
                                <legend><h3>Edit a category</h3></legend>
                                <p>
                                    <label for="name">Category name</label><br>
                                    <select name="name" id="name" class="form-control">
                                        <option value="<?= $row['categoryID'] ?>"><?= $row['name'] ?></option>
                                    </select>
                                </p>
                                <p>
                                    <label for="description">Description</label><br>
                                    <textarea name="description" id="description" rows="4" cols="70"><?= $row['description'] ?></textarea>
                                </p>
                                <p>
                                    <input type="submit" name="submit" value="Update" />
                                    <input type="submit" name="btnDelete" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" />
                                </p>
                            </fieldset>
                        </form>
		            </div>

                    <?php if(isset($_POST['btnUpdate'])) : ?>
                        <?= header("Location: validate_category.php") ?>
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
