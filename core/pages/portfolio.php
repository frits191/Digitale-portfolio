<?php
$stmt = $db->prepare("SELECT layout FROM portfolio WHERE id = ?;");
$stmt->execute(array($portfolioID));
$i = 0;
$geselecteerd = false;
while($row = $stmt->fetch()){
    if($i == 0){
        $layout = $row["layout"];
    }
}

if(isset($_GET["projectid"])){
    $stmt4 = $db->prepare("SELECT * FROM file WHERE project_id = ? ORDER BY type ASC;");
    $stmt4->execute(array($_GET["projectid"]));
    $stmt5 = $db->prepare("SELECT * FROM project WHERE id = ?;");
    $stmt5->execute(array($_GET["projectid"]));
    $i = 0;
    $projecttitle = "";
    while($row = $stmt5->fetch()){
        if($i == 0){
            $projecttitle = $row["title"];
        }
        $i++;
    }
    $stmt5->closeCursor();
    if($layout == "list"){
    /* List style Layout */
        
    echo ""
            . " <div class='row'>"
            . "     <div class='col-sm-12'>"
            . "         <div class='panel panel-default'>"
            . "             <div class='panel-heading'>"
            . "             $projecttitle"
            . "             </div>"
            . "             <div class='panel-body'>";
    if($stmt4->rowCount() > 0){
         echo "                 <table class='table table-striped table-hover'>"
            . "                     <thead>"
            . "                         <tr>"
            . "                             <th>"
            . "                                 Type"
            . "                             </th>"
            . "                             <th>"
            . "                                 Titel"
            . "                             </th>"
            . "                             <th>"
            . "                                 Beschrijving"
            . "                             </th>"
            . "                         </tr>"
            . "                     </thead>"
            . "                     <tbody>"
            . "                                 ";
    while($row = $stmt4->fetch()){
        echo "<tr class='clickable' onclick=\"window.location.href='front-end/res/portfolios/" . $portfolioID . "/" . $row["project_id"] . "/" . $row["title"] . $row["type"] . "'\"><td><img height='40px' src='img/filetypes/" . str_replace(".", "", $row["type"]) . ".png' alt='" . $row["type"] . "'/></td>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["description"] . "</td></tr>";
    }
    echo      "                     </tbody>"
            . "                 </table>";
    } else {
    echo "<h2>Geen bestanden gevonden in dit project</h2>";
}
         echo "             </div>"
            . "         </div>"
            . "     </div>"
            . " </div>";
}else if($layout == "grid1"){
    /* Big grid style layout */
    echo "      <div class='page-header'>"
    . "             <h1>$projecttitle <small>" . ucwords(str_replace("_", " ", $name)) . "</small></h1>"
            . " </div>";
    if($stmt4->rowCount() > 0){
    echo "<div class='row'>";
    $j = 1;
    while($row = $stmt4->fetch()){
        echo ""
                . "     <div class='col-sm-4'>"
                . "         <div onclick=\"window.location.href='front-end/res/portfolios/" . $portfolioID . "/" . $row["project_id"] . "/" . $row["title"] . $row["type"] . "'\" class='panel panel-default clickable'>"
                . "             <div class='panel-heading'>"
                .                   $row["title"]
                . "             </div>"
                . "             <div class='panel-body'>"
                . "             <img class='img-responsive grid-img' src='img/filetypes/" . str_replace(".", "", $row["type"]) . ".png' alt='folder'/>"
                . "             </div>";
        if(!empty($row["description"])){
            echo  "             <div class='panel-footer'>"
                    .              $row["description"]
                    . "         </div>";
        }
            echo  "         </div>"
                . "     </div>";
        if($j == 3){
            echo "</div><div class='row'>";
            $j = 0;
        }
        $j++;
    }
    } else {
    echo "<h2>Geen bestanden gevonden in dit project</h2>";
}
}else if($layout == "grid2"){
    /* Small grid layout */
    echo "      <div class='page-header'>"
    . "             <h1>$projecttitle <small>" . ucwords(str_replace("_", " ", $name)) . "</small></h1>"
            . " </div>";
    if($stmt4->rowCount() > 0){
    echo "<div class='row'>";
    $j = 1;
    while($row = $stmt4->fetch()){
        echo ""
                . "     <div class='col-sm-3'>"
                . "         <div onclick=\"window.location.href='front-end/res/portfolios/" . $portfolioID . "/" . $row["project_id"] . "/" . $row["title"] . $row["type"] . "'\" class='panel panel-default clickable'>"
                . "             <div class='panel-heading'>"
                .                   $row["title"]
                . "             </div>"
                . "             <div class='panel-body'>"
                . "             <img class='img-responsive grid-img' src='img/filetypes/" . str_replace(".", "", $row["type"]) . ".png' alt='folder'/>"
                . "             </div>";
        if(!empty($row["description"])){
            echo  "             <div class='panel-footer'>"
                    .              $row["description"]
                    . "         </div>";
        }
            echo  "         </div>"
                . "     </div>";
        if($j == 4){
            echo "</div><div class='row'>";
            $j = 0;
        }
        $j++;
    }
}
} else {
    echo "<h2>Geen bestanden gevonden in dit project</h2>";
}
}else{
    $stmt4->execute(array($portfolioID));
    if($layout == "list"){
    /* List style Layout */
    echo ""
            . " <div class='row'>"
            . "     <div class='col-sm-12'>"
            . "         <div class='panel panel-default'>"
            . "             <div class='panel-heading'>"
            . "                 Projecten"
            . "             </div>"
            . "             <div class='panel-body'>";
    if($stmt4->rowCount() > 0){
         echo "                 <table class='table table-striped table-hover'>"
            . "                     <thead>"
            . "                         <tr>"
            . "                             <th>"
            . "                                 Titel"
            . "                             </th>"
            . "                             <th>"
            . "                                 Beschrijving"
            . "                             </th>"
            . "                         </tr>"
            . "                     </thead>"
            . "                     <tbody>";
    while($row = $stmt4->fetch()){
        echo "<tr class='clickable' onclick=\"window.document.location='?page=portfolio&projectid=" . $row["id"] . "'\"><td>" . $row["title"] . "</td>";
        echo "<td>" . $row["description"] . "</td></tr>";
    }
    echo      "                     </tbody>"
            . "                 </table>";
    }else{
        echo "<h2>Geen projecten gevonden in dit portfolio.</h2>";
    }
         echo "             </div>"
            . "         </div>"
            . "     </div>"
            . " </div>";
    
}else if($layout == "grid1"){
    /* Big grid style layout */
    echo "      <div class='page-header'>"
    . "             <h1>Projecten <small>" . ucwords(str_replace("_", " ", $name)) . "</small></h1>"
            . " </div>";
    if($stmt4->rowCount() > 0){
    echo "<div class='row'>";
    $j = 1;
    while($row = $stmt4->fetch()){
        echo ""
                . "     <div class='col-sm-4'>"
                . "         <div onclick=\"window.document.location='?page=portfolio&projectid=" . $row["id"] . "'\" class='clickable panel panel-default'>"
                . "             <div class='panel-heading'>"
                .                   $row["title"]
                . "             </div>"
                . "             <div class='panel-body'>"
                . "             <img class='img-responsive grid-img' src='img/filetypes/folder.png' alt='folder'/>"
                . "             </div>";
        if(!empty($row["description"])){
            echo  "             <div class='panel-footer'>"
                    .              $row["description"]
                    . "         </div>";
        }
            echo  "         </div>"
                . "     </div>";
        if($j == 3){
            echo "</div><div class='row'>";
            $j = 0;
        }
        $j++;
    }
    } else {
    echo "<h2>Geen projecten gevonden in dit portfolio</h2>";
}
}else if($layout == "grid2"){
    /* Small grid layout */
    echo "      <div class='page-header'>"
    . "             <h1>Projecten <small>" . ucwords(str_replace("_", " ", $name)) . "</small></h1>"
            . " </div>";
    if($stmt4->rowCount() > 0){
    echo "<div class='row'>";
    $j = 1;
    while($row = $stmt4->fetch()){
        echo ""
                . "     <div class='col-sm-3'>"
                . "         <div onclick=\"window.document.location='?page=portfolio&projectid=" . $row["id"] . "'\" class='clickable panel panel-default'>"
                . "             <div class='panel-heading'>"
                .                   $row["title"]
                . "             </div>"
                . "             <div class='panel-body'>"
                . "             <img class='img-responsive grid-img' src='img/filetypes/folder.png' alt='folder'/>"
                . "             </div>";
        if(!empty($row["description"])){
            echo  "             <div class='panel-footer'>"
                    .              $row["description"]
                    . "         </div>";
        }
            echo  "         </div>"
                . "     </div>";
        if($j == 4){
            echo "</div><div class='row'>";
            $j = 0;
        }
        $j++;
    }
}
} else {
    echo "<h2>Geen projecten gevonden in dit portfolio</h2>";
}
}
$stmt4->closeCursor();