    <?php
    session_start();
    require ('core/database.php');
    if(isset($_GET['page']))
    {
    $page = $_GET['page'];
    }else{
    $page = "home";
    }
    ?>

    <?php   require ('layout/header.php');?>
    <div id="container">
    <div id="content">
    <?php
    if(file_exists("pages/$page.php"))
    {
    require ("pages/$page.php");
    }else{
    echo "<img class='img-responsive center-block' src='img/404.png' alt='page not found'>";
    }
    ?>
    </div></div>
<?php require ('layout/footer.php');?>