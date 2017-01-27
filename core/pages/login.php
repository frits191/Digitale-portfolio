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
                        <input type="email" name="E-mailadres" class="form-control" placeholder="E-mail"/>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="text" name="Wachtwoord" class="form-control" placeholder="Wachtwoord"/>
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
    foreach ($_POST as $key => $value){
        if(empty($value)){
            $_SESSION["message"][$key] = "Dit veld is verplicht";
        }
    }
    $stmt4 = $db->prepare("SELECT `e-mail`, password, role FROM user WHERE `e-mail` = ?;");
        $stmt4 -> execute(array($_POST["user"]));
        $mail = $pass = $role = "";
        if($stmt4 -> rowCount() == 0){
        while($row = $stmt4->fetch()){
            $mail = $row["e-mail"];
            $pass = $row["password"];
            $role = $row["role"];
        }
        if(password_verify($pass, $_POST["pass"])){
                $_SESSION['loggedIn'] = true;
		$_SESSION["e-mail"] = $email;
		$_SESSION['role'] = $row["role"];
		$_SESSION['name'] = $row["firstName"] . " " . $row["lastName"];
		$_SESSION['id'] = $row["id"];
                $_SESSION["message"]["Succes!"] = $_SESSION["name"] . ". U bent nu ingelogd";
        }else{
            $_SESSION["message"]["Sorry"] = "Deze combinatie van gebruikersnaam en wachtwoord is incorrect.";
        }
    }else if($stmt4->rowCount() > 1){
        $_SESSION["message"]["0x0001"] = "Er zijn meerdere accounts met dit e-mailadres gevonden, contacteer alstublieft de administrator.";
    }else{
        $_SESSION["message"]["Sorry"] = "Deze combinatie van gebruikersnaam en wachtwoord is incorrect.";
    }
    if(isset($_SESSION["message"])){
        redirect("?page=login");
    }
}