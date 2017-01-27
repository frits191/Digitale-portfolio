<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Inloggen
            </div>
            <div class="panel-body">
                <form method='post' id="login" action='#'>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class=" glyphicon glyphicon-user"></span></span>
                            <input type="email" name="mail" class="form-control" placeholder="E-mail"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" name="Wachtwoord" class="form-control" placeholder="Wachtwoord"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <input type="submit" class="btn btn-primary submit pull-right" form="login" name="submitLogin"/>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST["submitLogin"])) {
    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            $_SESSION["message"][$key] = "Dit veld is verplicht";
        }
    }
    $stmt4 = $db->prepare("SELECT * FROM user WHERE `e-mail` = ?;");
    $stmt4->execute(array($_POST["mail"]));
    $mail = $pass = $role = $name = "";
    $id = 0;
    if ($stmt4->rowCount() == 1) {
        while ($row = $stmt4->fetch()) {
            $mail = $row["e-mail"];
            $pass = $row["password"];
            $role = $row["role"];
            $name = $row["firstName"] . " " . $row["lastName"];
            $id = $row["id"];
        }
        if (password_verify($_POST["Wachtwoord"], $pass)) {
            $_SESSION['loggedIn'] = true;
            $_SESSION["e-mail"] = $mail;
            $_SESSION['role'] = $role;
            $_SESSION['name'] = $name;
            $_SESSION['id'] = $id;
            $stmt7 = $db->prepare("SELECT id FROM portfolio WHERE owner_id = ?;");
            $stmt7 -> execute(array($id));
            while($row = $stmt7->fetch()){
                $_SESSION["portfolio_id"] = $row["id"];
            }
            $_SESSION["message"]["Succes!"] = $_SESSION["name"] . ". U bent nu ingelogd";
        } else {
            $_SESSION["message"]["Sorry"] = "Deze combinatie van gebruikersnaam en wachtwoord is incorrect.";
        }
    } else if ($stmt4->rowCount() > 1) {
        $_SESSION["message"]["0x0001"] = "Er zijn meerdere accounts met dit e-mailadres gevonden, contacteer alstublieft de administrator.";
    } else {
        $_SESSION["message"]["Sorry"] = "Deze combinatie van E-mailadres en Wachtwoord is incorrect.";
    }
    if (isset($_SESSION["message"])) {
        redirect("?page=home");
    }
}