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
    if($layout == "list"){
    /* List style Layout */
    echo ""
            . " <div class='row'>"
            . "     <div class='col-sm-12'>"
            . "         <div class='panel panel-default'>"
            . "             <div class='panel-heading'>"
            . "             $projecttitle"
            . "             </div>"
            . "             <div class='panel-body'>"
            . "                 <table class='table table-striped'>"
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
        echo "<tr><td>" . $row["type"] . "</td>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["description"] . "</td></tr>";
    }
    echo      "                     </tbody>"
            . "                 </table>"
            . "             </div>"
            . "             <div class='panel-footer'>"
            . "             </div>"
            . "         </div>"
            . "     </div>"
            . " </div>";
    
}else if($layout == "grid1"){
    /* Big grid style layout */
    
}else if($layout == "grid2"){
    /* Small grid layout */
    
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
            . "             <div class='panel-body'>"
            . "             </div>"
            . "             <div class='panel-footer'>"
            . "             </div>"
            . "         </div>"
            . "     </div>"
            . " </div>";
    
}else if($layout == "grid1"){
    /* Big grid style layout */
    
}else if($layout == "grid2"){
    /* Small grid layout */
    
}
}
if($layout == "list"){
    /* List style Layout */
    echo ""
            . " <div class='row'>"
            . "     <div class='col-sm-12'>"
            . "         <div class='panel panel-default'>"
            . "             <div class='panel-heading'>"
            . "             </div>"
            . "             <div class='panel-body'>"
            . "             </div>"
            . "             <div class='panel-footer'>"
            . "             </div>"
            . "         </div>"
            . "     </div>"
            . " </div>";
    
}else if($layout == "grid1"){
    /* Big grid style layout */
    
}else if($layout == "grid2"){
    /* Small grid layout */
    
}