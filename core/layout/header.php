<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="front-end/res/style.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
        <style>
            /*achtergrondkleur*/
            html, body {
                background-color: lightgrey !important;
            }
            /*navigatiebalk kleur*/
            /*pc*/
            #sidebar-wrapper {
                background: #004489;
            }
            .sidebar-nav li a {
                color: whitesmoke !important;
            }
            .sidebar-secondary {
                color: whitesmoke !important;
            }
            /*mobiel*/
            .navmenu {
                background: #004489 !important;
            }
            .navmenu-nav li a{
                color: white !important;
            }
            /*persoonlijke banner mobiel*/
            .navbar {
                background-image: url("res/img/background.png") !important;
            }
        </style>
    </head>
    <?php
    
    $stmt1 = $db->prepare("SELECT id, firstName FROM user WHERE role = 'student' ORDER BY firstName ASC;");
    $stmt1->execute(array());
    
    $id = "";
    if (isset($_GET["id"])) {
        $_SESSION["id"] = $_GET["id"];
    } else if(!isset($_SESSION["id"])){
        $i = 0;
        while ($row = $stmt1->fetch()) {
            if ($i == 0) {
                $_SESSION["id"] = $row["id"];
            }
            $i++;
        }
    }
    $stmt1->closeCursor();
    $stmt2 = $db->prepare("SELECT firstName, lastName FROM user WHERE id = ? ;");
    $stmt2->execute(array($_SESSION["id"]));
    $i = 0;
    $name="";
    while($row = $stmt2->fetch()){
        if($i == 0){
            $name = $row["firstName"] . "_" . $row["lastName"];
        }
        $i++;
    }
    $stmt2->closeCursor();
    $stmt = $db->prepare("SELECT firstName, lastName, id FROM user WHERE role='student' ORDER BY firstName ASC;")
    
    ?>
    <body>
        <div class="hidden-xs">
            <div id="wrapper">
                <div id="sidebar-wrapper" >
                    <ul class="sidebar-nav">
                        <li class="sidebar-brand">
                            <a href="?page=home">
                                <img src="front-end/res/img/header-logo-pc.png" alt="Logo" height="70px"/>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo ucwords(str_replace("_", " ", $name)); ?><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php
                                    $stmt->execute(array());
                                    while($row = $stmt->fetch()){
                                        $id1 = $row["id"];
                                        $name1 = $row["firstName"] . "_" . $row["lastName"];
                                        echo "<li><a href='?id=$id1'>" . str_replace("_", " ", $name1) . "</a></li>";
                                    }
                                    $stmt -> closeCursor();
                                            
                                ?>
                            </ul>
                        </li>
                        <li>
                            <?php
                            
                            $stmt1 = $db->prepare("SELECT id, title FROM portfolio WHERE owner_id = ?;");
                            $stmt1 -> execute(array($_SESSION["id"]));
                            while($row = $stmt1->fetch()){
                                $i = 0;
                                if($i == 0){
                                $portfolioID = $row["id"];
                                $portfolioNaam = $row["title"];
                                }
                                $i++;
                            }
                            
                            ?>
                            <a href="?page=portfolio"><?php echo $portfolioNaam; ?></a>
                            <ul class="sidebar-secondary">
                                <?php
                                
                                $stmt4 = $db->prepare("SELECT * FROM Project WHERE portfolio_id = ?;");
                                $stmt4->execute(array($portfolioID));
                                while($row = $stmt4->fetch()){
                                    echo "<li><a href='?page=portfolio&projectid=" . $row["id"] ."'>" . $row["title"] ."</li>";
                                }
                                
                                ?>
                            </ul>
                            
                        </li>
                    </ul>
                </div>
            </div>
        </div><div class="visible-xs-block">
            <nav id="myNavmenu" class="navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
                <a class="navmenu-brand" href="?page=home">
                    <img src="front-end/res/img/header-logo-pc.png" alt="Logo" height="70px"/>
                </a>
                <ul class="nav navmenu-nav">
                    <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo ucwords(str_replace("_", " ", $name)); ?><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <?php
                                $stmt->execute(array());
                                    while($row = $stmt->fetch()){
                                        $id1 = $row["id"];
                                        $name1 = $row["firstName"] . "_" . $row["lastName"];
                                        echo "<li><a href='?id=$id1'>" . str_replace("_", " ", $name1) . "</a></li>";
                                    }
                                    $stmt -> closeCursor();
                                            
                                ?>
                            </ul>
                        </li>
                    <li><a href="?page=projecten">Projecten</a></li>
                    <li><a href="?page=portfolio">Portfolio</a></li>
                </ul>
            </nav>

            <div class="navbar navbar-default navbar-fixed-top">
                <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
        </div>
        <!-- persoonlijke banner pc -->
        <div class="banner hidden-xs"><img src="front-end/res/img/banner.png" alt="Banner"/></div>
        <div id="container">

            <div id="content">
