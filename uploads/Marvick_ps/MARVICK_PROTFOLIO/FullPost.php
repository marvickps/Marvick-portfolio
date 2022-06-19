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

        <!--==================== UNICONS ====================-->
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
        
        <!--==================== SWIPER CSS ====================-->
        <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
        
        <!--==================== CSS ====================-->
        <link rel="stylesheet" href="assets/css/styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;900&display=swap" rel="stylesheet">

        <title>Marvick</title>
    </head>
    <body>
        <header class="header" id="header">
            <nav class="nav container">
                <a href="#" class="nav__logo">Marvick</a>
                <div class="nav__menu" id="nav-menu">
                    <ul class="nav__list grid">
                        <li class="nav__item">
                            <a href="home_page.php#home" class="nav__link ">
                                <i class="uil uil-estate nav__icon"></i> Home
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="home_page.php#about" class="nav__link">
                                <i class="uil uil-user nav__icon"></i> About  
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="home_page.php#skills" class="nav__link">
                                <i class="uil uil-file-alt nav__icon"></i> Skills
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="home_page.php#services" class="nav__link">
                                <i class="uil uil-briefcase-alt nav__icon"></i> Services
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="home_page.php#portfolio" class="nav__link">
                                <i class="uil uil-scenery nav__icon"></i> Protfolio
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="Blogs.php" class="nav__link active-link">
                                <i class="uil uil-monitor nav__icon"></i> Blogs
                            </a>
                        </li>
                        <li class="nav__item">
                            <a href="home_page.php#contact" class="nav__link">
                                <i class="uil uil-message nav__icon"></i> Contact us
                            </a>
                        </li>
                    </ul>
                    <i class="uil uil-times nav__close" id="nav-close"></i>
                </div>
                <div class="nav__btns">
                    <!-- Theme Change button -->
                    <i class="uil uil-moon change-theme" id="theme-button"></i>

                    <div class="nav__toggle" id="nav-toggle">
                        <i class="uil uil-apps"></i>
                    </div>
                </div>
            </nav>
        </header>
        <main class="main">
            <section class="Blogs section">
                <div class="blog__container container " >
                    <div class="blog__content">
                        <div class="blog__data">
                        <h1 class="blog__title">Welcome to My Blogging page</h1>
                        <h3 class="blog__subtitle">Where I keep my journal safe</h3>
                    </div>
                </div>
                
                <hr class="hr_1">
                <div class=" fp_container ">
                    <?php 
                    $sql = "SELECT *  FROM posts WHERE id='$PostIdFromURL'";
                    $stmt = $ConnectingDB->query($sql);

                    while($DataRows = $stmt->fetch()){
                    $PostId = $DataRows["id"];
                    $DateTime = $DataRows["datetime"];
                    $PostTitle = $DataRows["title"];
                    $Category = $DataRows["category"];
                    $Admin = $DataRows["author"];
                    $Image = $DataRows["image"];
                    $PostDescription = $DataRows["post"];
                    ?>
                    <div class="card">
                        <img src="Uploads/<?php echo htmlentities($Image); ?>" class="img-thumbnail card-img-top m-2" style="height:auto; max-width: 500px;" >
                        <div class="card-body " >

                            <h3 class="protfolio__title"><?php echo htmlentities($PostTitle); ?></h3>
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
                                <?php } ?>
                            </span>
                            <hr class="hr_2">
                            <p class="t__description" style="word-wrap: break-word;"><?php echo htmlentities($PostDescription); ?></p>

                        </div>
                    </div>
                    <?php } ?>
                    <!-- Comment Section html -->


                            <!-- fetching comments into the post -->
                            <div class="comment_dev">
                                <span class="comment_head">Comments</span>
                                <?php

                                    $sql = "SELECT * FROM comments WHERE post_id='$PostIdFromURL' AND status='ON' ";
                                    // $sql = "SELECT * FROM `marvick`.`posts` WHERE `id` = '$PostIdFromURL'";

                                    $stmt = $ConnectingDB->query($sql);

                                    while ($DataRows = $stmt->fetch()){
                                            $CommentDate = $DataRows['datetime'];
                                            $CommenterName = $DataRows['name'];
                                            $CommenterComment= $DataRows['comment'];

                                ?>
                                <div class=b-1>
                                    <div class="media mt-3">
                                        <div>
                                            <p class="small silent" style="float:right;"><?php echo htmlentities($CommentDate);?></p>
                                            <h6 class="media-body "><i class="uil uil-user-circle"></i>   <?php echo htmlentities($CommenterName);?></h6>
                                            <p class=" border">
                                                <div class="commenter " >
                                                    <?php echo htmlentities($CommenterComment); ?>
                                                </div>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                                        <?php } ?>
                                
                            </div>
                                        

                            <!-- fetching comments into the post END -->

        
                                <form class="contact__form grid" action="FullPost.php?id=<?php echo $PostIdFromURL ?>" method="post">
                                    <div class="comment">
                                        <div class="card-header">
                                            <h5 class="comment_head-2">Comment Here</h5>
                                        </div>

                                            <div class="contact__inputs ">
                                                <div style="display:grid; gap: 1.5em;">
                                                    <div class="contact__content">
                                                        <label for="" class="contact__label">Name</label>
                                                        <input class="contact__input" type="text" name="CommenterName" placeholder="" value="">
                                                        
                                                    </div>
                                                    <div class="contact__content">
                                                        <label for="" class="contact__label">Email</label>
                                                        <input class="contact__input" type="email" name="Email" placeholder="" value="">
                                                        
                                                    </div>
                                                    <div class="contact__content">
                                                        <label for="" class="contact__label">comment</label>
                                                        <textarea name="Comment" class="contact__input" row="5" cols="88"></textarea>
                                                    </div>
                                                </div>
                                                <button type="submit" name="Submit" class="btn btn-outline-dark mt-2" style="float:right; border-radius:.5rem"> Submit </button>
                                            </div>
                                    </div>
                                </form>
                            
                    </div>

                            <!-- Comment section end -->
                </div>
            </section>
        </main>
        <footer class="footer">
              <div class="footer__bg">
                  <div class="footer__container container grid">
                      <div>
                          <h1 class="footer__title">Marvick</h1>
                          <span class="footer__subtitle">Graphic designer</span>
                      </div>
                      <ul class="footer__links">
                          <li>
                              <a href="#services" class="footer__link">Services</a>
                          </li>
                          <li>
                              <a href="#protfolio" class="footer__link">Protfolio</a>
                          </li>
                          <li>
                              <a href="#contact" class="footer__link">Contactme</a>
                          </li>
                      </ul>
      
      
                      <div class="footer__socials">
                          <a href="https://www.instagram.com/marvick_ps/" target="_blank" class="footer__social">
                              <i class="uil uil-instagram"></i>
                          </a>
                      </div>
                  </div>
                  <p class="footer__copy">&#169; Marvick. All right reserved</p>
              </div>
        
        </footer>
          
        
          <!-- footer end -->
        
        
        
        
        <!--==================== SWIPER JS ====================-->
        <script src="assets/js/swiper-bundle.min.js"></script>

        <!--==================== MAIN JS ====================-->
        <script src="assets/js/main.js"></script>
    </body>
</html>