<?php
    $pageResults = 5;

    $query = "SELECT * FROM news";
    $statement = $db->prepare($query);
    $statement->execute(); 	
    $results = $statement->rowCount();

    $totalPages = ceil($results / $pageResults);

    if (!isset($_GET['page'])) {
        $page = 1;
    }
    else {
        $page = $_GET['page'];
    }

    $firstPageResult = ($page - 1) * $pageResults;

    // Retrieve selected results from database and display them on page
    $query = "SELECT * FROM news JOIN categories ON news.categoryID=categories.categoryID ORDER BY date_released DESC LIMIT " . $firstPageResult . ',' . $pageResults;
    $statement = $db->prepare($query);
    $statement->execute(); 	

    $counter=0;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
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
        <!-- Main Content-->
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <h1> Featured News </h1>
                    <!-- Post preview-->
                    <?php while(($row = $statement->fetch()) && ($counter < 10)): ?> 
                        <div class = "newsForm">
                            <h2><a href="showNews.php?newsID=<?= $row['newsID']?>&p=<?=(str_replace(' ', '-', strtolower($row['title'])))?>"><?= $row['title']?></a></h2>
                            <p> Category: <?= $row['name'] ?></p>
                            <p>
                                <?php 
                                    $orgDate = $row['date_released'];  
                                    $newDate = date("F d, Y", strtotime($orgDate));  
                                    $counter++; // counts tweets, inserted it here because putting it in a php block is much better
                                ?>
                                <small><?= $newDate?> 
                                    <?php if($_SESSION['currentUser'] == "Admin jeremy") :?>
                                        - <a href="editNews.php?newsID=<?= $row['newsID']?>">edit</a>
                                    <?php endif ?>
                                </small>
                            </p>
                            <?php if(strlen($row['content']) > 200) :?>
                                <p><?= substr($row['content'], 0, 200) . "..." ?></p>
                                <p><a href="showNews.php?newsID=<?= $row['newsID']?>&p=<?=(str_replace(' ', '-', strtolower($row['title'])))?>">Read Full Post</a></p>
                            <?php else :?>
                                <p><?= $row['content'] ?></p>
                            <?php endif?>
                        </div>
                    <?php endwhile ?>

                    <?php if($_SESSION['currentUser'] == "Admin jeremy") :?>
                    <div class="row row gx-4 gx-lg-2 justify content-center">
                        <a href="createNews.php">
                            <button class="btnCreate" style="width:250px" type="submit">Create a post</button>
                        </a>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="text-center">
            <ul class="pagination pagination-lg justify-content-center">
                <?php 
                    for ($page_no = 1; $page_no <= $totalPages; $page_no++) {
                        if ($page == $page_no) {
                            $state = '"active"';
                        }
                        else {
                            $state = "";
                        }
                        echo '<li class="' . $state . '"><a href="home.php?page=' . $page_no . '">' . $page_no . '</a></li>';
                    }           
                ?>
            </ul>
        </nav>  
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
