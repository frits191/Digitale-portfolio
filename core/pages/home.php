<?php

$stmt = $db->prepare("SELECT * FROM persoonlijkeInfo WHERE user_id = ?;");
$stmt->execute(array($_SESSION["userid"]));
$opleiding = $interesses = $werkervaring = $hobbies = $verder = "";
while($row=$stmt->fetch()){
    $opleiding = $row["Opleiding"];
    $interesses = $row["Interesses"];
    $werkervaring = $row["Werkervaring"];
    $hobbies = $row["Hobbies"];
    $verder = $row["Info"];
}
$stmt->closeCursor();
?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Iets over mij
            </div>
            <div class="panel-body center">
                <?php echo ucfirst($verder); ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Persoonlijke informatie:
            </div>
            <div class="panel-body">
                <table class='table table-striped'>
                    <tbody>
                        <tr>
                            <td>
                                Naam
                            </td>
                            <td>
                                <?php echo ucwords(str_replace("_", " ", $name)); ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                Opleiding
                            </td>
                            <td>
                                <?php echo ucfirst($opleiding); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Interesses
                            </td>
                            <td>
                                <?php echo ucfirst($interesses); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Werkervaring
                            </td>
                            <td>
                                <?php echo ucfirst($werkervaring); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Hobby's
                            </td>
                            <td>
                                <?php echo ucfirst($hobbies); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>