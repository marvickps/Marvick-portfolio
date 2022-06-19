<?php require_once("includes/DB.php"); ?>
<?php require_once("includes/Function.php"); ?>
<?php require_once("includes/Sessions.php"); ?>

<?php
    $PostIdFromURL = $_GET["id"];

    // comment section

    if(isset($_POST["Submit"])){
    $Name = $_POST["CommenterName"];
    $Email = $_POST["Email"];
    $Comment = $_POST["Comment"];
    $Admin = $_SESSION["UserName"];
    date_default_timezone_set("Asia/Calcutta");
    $CurrentTime=time();
    $DateTime=strftime("%B-%d-%Y %H:%M:%S", $CurrentTime);

    if(empty($Name)||empty($Email)||empty($Comment)){
        $_SESSION["ErrorMessage"]= "Connot be empty";
        Redirect_to("FullPost.php?id=$PostIdFromURL");
    }

    elseif(strlen($Name)<3){
        $_SESSION["ErrorMessage"]= "Name should be greater than 2 characters";
        Redirect_to("FullPost.php?id=$PostIdFromURL");
    }

    elseif(strlen($Name)>49){
        $_SESSION["ErrorMessage"]= "Name should be less than 50 characters";
        Redirect_to("FullPost.php?id=$PostIdFromURL");
    }
    elseif(strlen($Email)<3){
        $_SESSION["ErrorMessage"]= "Email should be greater than 2 characters";
        Redirect_to("FullPost.php?id=$PostIdFromURL");
    }

    elseif(strlen($Email)>59){
        $_SESSION["ErrorMessage"]= "Email should be less than 60 characters";
        Redirect_to("FullPost.php?id=$PostIdFromURL");
    }
    elseif(strlen($Comment)<3){
        $_SESSION["ErrorMessage"]= "Email should be greater than 2 characters";
        Redirect_to("FullPost.php?id=$PostIdFromURL");
    }

    elseif(strlen($Comment)>499){
        $_SESSION["ErrorMessage"]= "Email should be less than 500 characters";
        Redirect_to("FullPost.php?id=$PostIdFromURL");
    }
    else{
        //query
        $sql = "INSERT INTO comments(datetime,name,email,comment,approvedby,status,post_id)";
        $sql .= "VALUES(:datetime,:name,:email,:comment, 'Pending', 'OFF',:postIdFromUrl)";
        $stmt = $ConnectingDB->prepare($sql);
        $stmt-> bindValue(':datetime', $DateTime);
        $stmt-> bindValue(':name', $Name);
        $stmt-> bindValue(':email', $Email);
        $stmt-> bindValue(':comment', $Comment);
        $stmt-> bindValue(':postIdFromUrl', $PostIdFromURL);
        $Execute=$stmt->execute();


        if($Execute){
            $_SESSION["SuccessMessage"]="Comment with Added Successfully";
            Redirect_to("FullPost.php?id=$PostIdFromURL");
        }
        else{
            $_SESSION["ErrorMessage"]="Something went wrong !";
            Redirect_to("FullPost.php?id=$PostIdFromURL");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Blog Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    </head>

    <body>

       <!-- NAVBAR -->

       <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-dark p-4">
          <h5 class="text-white h4">Collapsed content</h5>
          <span class="text-muted">Toggleable via the navbar brand.</span>
        </div>
      </div>
       <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a href="#" class="navbar-brand">
                <img src="images/logo.svg" alt="" width="45" height="45" class="d-inline-block align-middle"> MARVICK</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarcollapseMD" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- list -->
                <div class="collapse navbar-collapse" id="navbarcollapseMD">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="Blogs.php" class="nav-link link-light">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link link-light">About Us</a>
                </li>
                <li class="nav-item">
                    <a href="Blogs.php" class="nav-link link-light">Blogs</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link link-light">Contact us</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link link-light">Features</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <form action="Blogs.php" class="d-flex" >
                    <input type="text" name="Search" placeholder="Search" value="">
                    <button class="btn btn-outline-light ms-2" name="SearchButton">Search</button>
                </form>
            </ul>
        </div>
        </div>
       </nav>
        <!-- navbar end -->

        <!-- header -->


            <div class="container">
                <div class="row mt-2">
                    <!-- main area   -->
                    <div class="col-sm-9 ">
                    <?php
                echo ErrorMessage();
                echo SuccessMessage();
                ?>
                    <h1 class="display-5 mb-3">Marvick Blog Post</h1>
                    <?php
                    // sql when search button is active
                    if(isset($_GET["SearchButton"])){
                        $Search = $_GET["Search"];
                        $sql = "SELECT * FROM posts
                        WHERE title LIKE :search
                        OR category LIKE :search
                        OR post LIKE :search";
                        $stmt = $ConnectingDB->prepare($sql);
                        $stmt->bindValue(':search','%'.$Search.'%');
                        $stmt->execute();
                    }



                    //default sql query
                        else{

                            if(!isset($PostIdFromURL)){
                                $_SESSION["ErrorMessage"]="Bad Request !";
                                Redirect_to("Blogs.php");
                            }

                        $sql = "SELECT *  FROM posts WHERE id='$PostIdFromURL'";
                        $stmt = $ConnectingDB->query($sql);
                        }
                        while($DataRows = $stmt->fetch()){
                            $PostId = $DataRows["id"];
                            $DateTime = $DataRows["datetime"];
                            $PostTitle = $DataRows["title"];
                            $Category = $DataRows["category"];
                            $Admin = $DataRows["author"];
                            $Image = $DataRows["image"];
                            $PostDescription = $DataRows["post"];
                            ?>
                    <div class ="card mt-2 " > <!-- style="width: 20rem;" -->
                    <img src="Uploads/<?php echo htmlentities($Image); ?>" class="img-thumbnail card-img-top m-2" style="height:auto; max-width: 500px;" >
                        <div class="card-body " >

                            <h3 class="card-title mt-2"><?php echo htmlentities($PostTitle); ?></h3>
                            <small class="text-muted">written by <?php echo htmlentities($Admin); ?> On
                            <?php echo htmlentities($DateTime); ?></small>
                            <span class="badge alert-dark" style="float:right;"><?php 
                            $Total=ApproveCommentsAccordingtoPost($PostId);
                            if($Total>0){
                            ?>
                            Comments:
                    
                        <?php    
                        echo $Total;
                     ?>
                     <?php } ?></span>
                            <hr>
                            <p class="" style="word-wrap: break-word;"><?php echo htmlentities($PostDescription); ?></p>

                        </div>
                    </div>
                    <br>
                    <?php } ?>

                            <!-- Comment Section html -->
                            <!-- fetching comments into the post -->
                                        <div>
                                            <span class="display-6 mt-2 mb-4">Comments</span>
                                <?php

                                    $sql = "SELECT * FROM comments WHERE post_id='$PostIdFromURL' AND status='ON' ";
                                    // $sql = "SELECT * FROM `marvick`.`posts` WHERE `id` = '$PostIdFromURL'";

                                    $stmt = $ConnectingDB->query($sql);

                                    while ($DataRows = $stmt->fetch()){
                                            $CommentDate = $DataRows['datetime'];
                                            $CommenterName = $DataRows['name'];
                                            $CommenterComment= $DataRows['comment'];

                                    ?>
                                            <div class="media mt-3 ms-3 bg">
                                                <div>
                                                    <p class="small silent" style="float:right;"><?php echo htmlentities($CommentDate);?></p>
                                                    <h6 class="media-body ml-2"><i class="fas fa-user-circle " ></i>   <?php echo htmlentities($CommenterName);?></h6>
                                                    <p class=" border"><div class="mr-2 ms-2 " ><?php echo htmlentities($CommenterComment); ?></div></p>

                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        



                            <!-- fetching comments into the post END -->


                            <div class="mt-3">
                                <form class="" action="FullPost.php?id=<?php echo $PostIdFromURL ?>" method="post">
                                    <div class="card-mb-3">
                                        <div class="card-header">
                                            <h5 class="Display-4">Comment Here</h5>
                                        </div>

                                            <div class="card-body">
                                                <div class="form-group">



                                                    <div class="input-group mt-2">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text"><span><i class="fas fa-user"></i></span></span>
                                                        </div>
                                                        <input class="form-control" type="text" name="CommenterName" placeholder="Name" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group mt-2">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text"><span><i class="fas fa-envelope"></i></span></span>
                                                        </div>
                                                        <input class="form-control" type="text" name="Email" placeholder="Email" value="">
                                                    </div>
                                                </div>

                                                <div class="form-group mt-2">
                                                <textarea name="Comment" class="form-control" row="5" cols="88"></textarea>
                                                </div>
                                                <button type="submit" name="Submit" class="btn btn-outline-dark mt-2" style="float:right;">Submit</button>
                                            </div>
                                    </div>
                                </form>
                            </div>
                    </div>

                            <!-- Comment section end -->

                     <!-- slide area -->
                    <div class="col-sm-3">
                    
                    <br><br><br><br>
                    <div class="card">
                      <div class="card-header bg-dark text-light">
                        <h2 class="lead">Categories</h2>
                        </div>
                        <div class="card-body">
                          <?php
                          global $ConnectingDB;
                          $sql = "SELECT * FROM category ORDER BY id desc";
                          $stmt = $ConnectingDB->query($sql);
                          while ($DataRows = $stmt->fetch()) {
                            $CategoryId = $DataRows["id"];
                            $CategoryName=$DataRows["title"];
                           ?>
                          <a class="text-dark" href="Blog.php?category=<?php echo $CategoryName; ?>"> <span class="heading"> <?php echo $CategoryName; ?></span> </a><br>
                         <?php } ?>
                      </div>
                    </div>
                    <br>



                    </div>

                </div>
            </div>



        <!-- header end -->

        <!-- footer -->
        <div class="container">
            <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
              <div class="col-md-4 d-flex align-items-center">
                <a class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
                    <img src="images/logo.svg" alt="" width="45" height="45" class="d-inline-block align-middle">
                </a>
                <span class="text-muted">© MARVICK, 2021</span>
              </div>

              <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
                <li class="ms-1"><a class="text-muted" href="https://www.instagram.com/marvick_ps/"><img src="images/instagram-brands.svg" class="bi" alt="" width="24" height="24" class="d-inline-block align-middle"></a></li>
              </ul>
            </footer>
          </div>

          <!-- footer end -->




       <script src="https://kit.fontawesome.com/656d645cb5.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>

</html>
