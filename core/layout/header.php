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
        <?php
        function redirect($url) {
            $string = '<script type="text/javascript">';
            $string .= 'window.location = "' . $url . '"';
            $string .= '</script>';

            echo $string;
        }

        $stmt1 = $db->prepare("SELECT id, firstName FROM user WHERE role = 'student' ORDER BY firstName ASC;");
        $stmt1->execute(array());

        $id = "";
        if (isset($_GET["id"])) {
            $_SESSION["userid"] = $_GET["id"];
        } else if (!isset($_SESSION["userid"])) {
            $i = 0;
            while ($row = $stmt1->fetch()) {
                if ($i == 0) {
                    $_SESSION["userid"] = $row["id"];
                }
                $i++;
            }
        }
        $stmt1->closeCursor();
        $stmt2 = $db->prepare("SELECT firstName, lastName FROM user WHERE id = ? ;");
        $stmt2->execute(array($_SESSION["userid"]));
        $i = 0;
        $name = "";
        while ($row = $stmt2->fetch()) {
            if ($i == 0) {
                $name = $row["firstName"] . "_" . $row["lastName"];
            }
            $i++;
        }
        $stmt2->closeCursor();
        $stmt = $db->prepare("SELECT firstName, lastName, id FROM user WHERE role='student' ORDER BY firstName ASC;");

        $stmt6 = $db->prepare('SELECT bg_color, font_color FROM portfolio WHERE owner_id = ?');
        $stmt6->execute(array($_SESSION["userid"]));
        $bgc = $fc = "";
        while ($row = $stmt6->fetch()) {
            $bgc = $row["bg_color"];
            $fc = $row["font_color"];
        }
        ?>
        <style>
            /*achtergrondkleur*/
            html, body {
                background-color: lightgrey !important;
            }
            /*panel header en footer kleuren*/
            .panel-heading ,
            .panel-footer {
                background: <?php echo $bgc; ?> !important;
                color: <?php echo $fc; ?> !important;
            }
            /*navigatiebalk kleur*/
            /*pc*/
            #sidebar-wrapper {
                background: <?php echo $bgc; ?>;
            }
            .sidebar-nav li a {
                color: <?php echo $fc; ?> !important;
            }
            .sidebar-secondary {
                color: <?php echo $fc; ?> !important;
            }
            .navbottom {
                color: <?php echo $fc; ?> !important;
            }
            /*mobiel*/
            .navmenu {
                background: <?php echo $bgc; ?> !important;
            }
            .navmenu-nav li a{
                color: <?php echo $fc; ?> !important;
            }
            /*persoonlijke banner mobiel*/
            .navbar {
                background-image: url("front-end/res/img/background.png") !important;
            }
        </style>
    </head>
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
                                while ($row = $stmt->fetch()) {
                                    $id1 = $row["id"];
                                    $name1 = $row["firstName"] . "_" . $row["lastName"];
                                    echo "<li><a href='?id=$id1'>" . ucwords(str_replace("_", " ", $name1)) . "</a></li>";
                                }
                                $stmt->closeCursor();
                                ?>
                            </ul>
                        </li>
                        <li>
                            <?php
                            $stmt1 = $db->prepare("SELECT id, title FROM portfolio WHERE owner_id = ?;");
                            $stmt1->execute(array($_SESSION["userid"]));
                            while ($row = $stmt1->fetch()) {
                                $i = 0;
                                if ($i == 0) {
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
                                while ($row = $stmt4->fetch()) {
                                    echo "<li><a href='?page=portfolio&projectid=" . $row["id"] . "'>" . $row["title"] . "</a></li>";
                                }
                                ?>
                            </ul>

                        </li>
                    </ul>
                    <?php
                    if ($_SESSION["loggedIn"]) {
                        echo "      <div class='navbottom'>"
                        . "     <p class='unspan pull-right'><b>" . $_SESSION["name"] . "</b></p> &nbsp;"
                        . "     <div class='btn-group pull-right'>"
                        . "         <button class='btn btn-primary navbtn' onclick=\"window.location.href='backend.php'\">Back-end</button>"
                        . "         <button class='btn btn-primary navbtn' onclick=\"window.location.href='?page=logout'\">Uitloggen</button>"
                        . "     </div>"
                        . " </div>";
                    } else {
                        echo "<button class='btn btn-primary navbtn navbottom' onclick=\"window.location.href='?page=login'\">Inloggen</button>";
                    }
                    ?>
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
                            while ($row = $stmt->fetch()) {
                                $id1 = $row["id"];
                                $name1 = $row["firstName"] . "_" . $row["lastName"];
                                echo "<li><a href='?id=$id1'>" . ucwords(str_replace("_", " ", $name1)) . "</a></li>";
                            }
                            $stmt->closeCursor();
                            ?>
                        </ul>
                    </li>
                    <li>
                        <a href="?page=portfolio">Portfolio</a>
                        <ul class="nav navmenu-secondary">
                            <?php
                            $stmt4 = $db->prepare("SELECT * FROM Project WHERE portfolio_id = ?;");
                            $stmt4->execute(array($portfolioID));
                            while ($row = $stmt4->fetch()) {
                                echo "<li><a href='?page=portfolio&projectid=" . $row["id"] . "'>" . $row["title"] . "</a></li>";
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
                <?php
                if ($_SESSION["loggedIn"]) {
                    echo "      <div class='navbottom'>"
                    . "     <p class='unspan pull-right'><b>" . $_SESSION["name"] . "</b></p> &nbsp;"
                    . "     <div class='btn-group pull-right'>"
                    . "         <button class='btn btn-primary navbtn' onclick=\"window.location.href='backend.php'\">Back-end</button>"
                    . "         <button class='btn btn-primary navbtn' onclick=\"window.location.href='?page=logout'\">Uitloggen</button>"
                    . "     </div>"
                    . " </div>";
                } else {
                    echo "<button class='btn btn-primary navbtn navbottom' onclick=\"window.location.href='?page=login'\">Inloggen</button>";
                }
                ?>
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
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        if (isset($_SESSION["message"])) {
                            foreach ($_SESSION["message"] as $key => $value) {
                                if ($key == "Succes!") {
                                    echo "<div class='alert alert-success'><b>$key</b> $value</div>";
                                } else {
                                    echo "<div class='alert alert-warning'><b>$key</b> | $value</div>";
                                }
                                unset($_SESSION["message"]);
                            }
                        }
                        ?>
                    </div>
                </div>