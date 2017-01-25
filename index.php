<?php
session_start();
require 'core/database.php';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "home";
}

require ('core/layout/header.php');

if (file_exists("core/pages/" . $page . ".php")) {
    require ("core/pages/" . $page . ".php");
} else {
    echo "<img class='img-responsive center-block' src='img/404.png' alt='page not found'>";
}
?>
    </div>
</div>
        <?php
        require ('core/layout/footer.php');
        ?>
