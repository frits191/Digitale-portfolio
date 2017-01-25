<?php

class Pages
{
	function home() {
		//HOME
		echo "<div class='col-lg-10'>";
			echo "Welkom " . $_SESSION["name"] . ".<br>";
			echo "Kies links in het menu een optie om verder te gaan.";
		echo "</div>";
	}

	function login() {
		//LOGIN
		global $functions;

		echo "<form method='post' action='backend.php?p=login'><br>";
			echo "Login:<br><br>";
			echo "<div class='input-group'>";
				echo '<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>';
				echo "<input type='e-mail' class='form-control' name='e-mail' placeholder='E-mail' id='loginEmail' required>";
			echo "</div><br>";
			echo "<div class='input-group'>";
				echo '<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>';
				echo "<input type='password' class='form-control' name='password' placeholder='Wachtwoord' id='loginPassword' required>";
			echo "</div><br>";
			echo "<button type='submit' name='submitLogin' class='btn btn-default'>Login</button>";
		echo "</form>";
		if (isset($_POST["submitLogin"])) {
			$functions->login();
		}
	}

	function logout() {
		//Logout
		session_unset();
		session_destroy();

		echo "U bent uitgelogd.";
		header('refresh:2;url=backend.php?p=home');
	}

	function info() {

	}

	function cijfers() {
		global $functions;

		echo "<table class='table table-hover'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Project</th>";
					echo "<th>Cijfer</th>";
					echo "<th>Gegeven door</th>";
					echo "<th>Opmerking</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";		

				$SQLString = "SELECT project.id, project.title, rating.grade, rating.giver_id, rating.remark FROM `project`, `rating` WHERE project.portfolio_id = " . $_SESSION["portfolio_id"] . " AND rating.project_id = project.id";
				$QueryResult = $functions->executeQuery($SQLString);
				$grades = mysqli_fetch_all($QueryResult);				
				if (mysqli_num_rows($QueryResult) != 0) {
					foreach ($grades as $index) {
						if (!empty($index[3])) {
							$SQLString = "SELECT firstName, lastName FROM user WHERE id = " . $index[3];
							$QueryResult = $functions->executeQuery($SQLString);
							$row = mysqli_fetch_all($QueryResult);

							$fname = $row[0][0];
							$lname = $row[0][1];
						} else {
							$fname = "-";
							$lname = "";
						}

						$Pname = $index[1];
						$grade = $index[2];
						$comment = $index[4];
						
						if (empty($Pname)) {						
							$Pname = "-";
						} elseif (empty($grade)) {
							$grade = "-";
						} elseif (empty($comment)) {
							$comment = "-";
						}

						echo "<tr>";
							echo "<td>" . $Pname . "</td>";
							echo "<td>" . $grade . "</td>";
							echo "<td>" . $fname . " " . $lname . "</td>";
							echo "<td>" . $comment . "</td>";
						echo "</tr>";
					}
				}
			echo "</tbody>";
		echo "</table>";
	}

	function projecten() {
		global $functions;

		if (isset($_GET["project"])) {
			$project = htmlspecialchars($_GET["project"]);
			if (is_int($project !== true)) {
				header('Location: backend.php?p=home');
				exit();
			} 
			$SQLString = "SELECT portfolio_id FROM project WHERE id = " . $project;
			$QueryResult = $functions->executeQuery($SQLString);
			$row = mysqli_fetch_all($QueryResult);
			if (mysqli_num_rows($QueryResult) > 0) {
				if ($row[0][0] != $_SESSION["portfolio_id"]) {
					header('Location: backend.php?p=home');
					exit();
				}
			} else {
				header('Location: backend.php?p=home');
				exit();
			}
		}

		if (isset($_POST["submitFolder"])) {	
			$functions->createFolder();
		}
		if (isset($_POST["submitFile"])) {
			$functions->uploadFile();
		}
		if (isset($_POST["folderEdit"])) {
			$functions->editFolder();
		}		
		if (isset($_POST["folderDelete"])) {
			$functions->deleteFolder();
		}		
		if (isset($_POST["fileEdit"])) {
			$functions->editFile();
		}		
		if (isset($_POST["fileDelete"])) {
			$functions->deleteFile();
		}	

		if (isset($_GET["project"])) {
			echo "<a href='backend.php?p=projecten'><button type='button' class='btn btn-default'>Terug</button></a>";
		}

		echo "<div class='files'>";
			if (isset($_GET["project"])) {
				$functions->getfiles();
			} else {
				$functions->getFolders();
			}			
		echo "</div>";

		//Folder creation modal
		echo '<div class="modal fade" id="AddFolder" role="dialog">';
            echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                        echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                        echo '<h4 class="modal-title">Maak een nieuw map aan.</h4>';
                    echo'</div>';
                    echo '<div class="modal-body">';
                        echo '<p>';
                            echo "<form action='backend.php?p=projecten' method='POST'>";
								echo "<div class='form-group'>";
									echo "<label for='folderCreateTitle'>Titel:</label>";
									echo "<input type='text' name='folderName' placeholder='Titel' class='form-control' id='folderCreateTitle' required>";
								echo "</div>";
								echo "<div class='form-group'>";
									echo "<label for='folderCreateDesc'>Beschrijving:</label>";
									echo "<textarea name='folderDesc' placeholder='Beschrijving' class='form-control' maxlength='500' id='folderCreateDesc'></textarea>";
								echo "</div>";
                        echo "</p>";
                    echo "</div>";
                    echo '<div class="modal-footer">';
                        echo "<button type='submit' class='btn btn-default' value='Verzend' name='submitFolder'>Verzend</button></form>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";

		//File uploading modal
		echo '<div class="modal fade" id="AddFile" role="dialog">';
            echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                        echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
                        echo '<h4 class="modal-title">Voeg een bestand toe.</h4>';
                    echo'</div>';
                    echo '<div class="modal-body">';
                        echo '<p>';                        

							?>
								<!-- The file upload form used as target for the file upload widget -->
								<form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data" data-ng-app="demo" data-ng-controller="DemoFileUploadController" data-file-upload="options" data-ng-class="{'fileupload-processing': processing() || loadingFiles}">
									<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
									<div class="row fileupload-buttonbar">
										<div class="col-lg-7">
											<!-- The fileinput-button span is used to style the file input field as button -->
											<span class="btn btn-success fileinput-button" ng-class="{disabled: disabled}">
												<i class="glyphicon glyphicon-plus"></i>
												<span>Add files...</span>
												<input type="file" name="files[]" multiple ng-disabled="disabled">
											</span>
											<button type="button" class="btn btn-primary start" data-ng-click="submit()">
												<i class="glyphicon glyphicon-upload"></i>
												<span>Start upload</span>
											</button>
											<!-- The global file processing state -->
											<span class="fileupload-process"></span>
										</div>
										<!-- The global progress state -->
										<div class="col-lg-5 fade" data-ng-class="{in: active()}">
											<!-- The global progress bar -->
											<div class="progress progress-striped active" data-file-upload-progress="progress()"><div class="progress-bar progress-bar-success" data-ng-style="{width: num + '%'}"></div></div>
											<!-- The extended global progress state -->
											<div class="progress-extended">&nbsp;</div>
										</div>
									</div>
									<!-- The table listing the files available for upload/download -->
									<table class="table table-striped files ng-cloak">
										<tr data-ng-repeat="file in queue" data-ng-class="{'processing': file.$processing()}">
											<td data-ng-switch data-on="!!file.thumbnailUrl">
												<div class="preview" data-ng-switch-when="true">
													<a data-ng-href="{{file.url}}" title="{{file.name}}" download="{{file.name}}" data-gallery><img data-ng-src="{{file.thumbnailUrl}}" alt=""></a>
												</div>
												<div class="preview" data-ng-switch-default data-file-upload-preview="file"></div>
											</td>
											<td>
												<p class="name" data-ng-switch data-on="!!file.url">
													<span data-ng-switch-when="true" data-ng-switch data-on="!!file.thumbnailUrl">
														<a data-ng-switch-when="true" data-ng-href="{{file.url}}" title="{{file.name}}" download="{{file.name}}" data-gallery>{{file.name}}</a>
														<a data-ng-switch-default data-ng-href="{{file.url}}" title="{{file.name}}" download="{{file.name}}">{{file.name}}</a>
													</span>
													<span data-ng-switch-default>{{file.name}}</span>
												</p>
												<strong data-ng-show="file.error" class="error text-danger">{{file.error}}</strong>
											</td>
											<td>
												<p class="size">{{file.size | formatFileSize}}</p>
												<div class="progress progress-striped active fade" data-ng-class="{pending: 'in'}[file.$state()]" data-file-upload-progress="file.$progress()"><div class="progress-bar progress-bar-success" data-ng-style="{width: num + '%'}"></div></div>
											</td>
											<td>
												<button type="button" class="btn btn-primary start" data-ng-click="file.$submit()" data-ng-hide="!file.$submit || options.autoUpload" data-ng-disabled="file.$state() == 'pending' || file.$state() == 'rejected'">
													<i class="glyphicon glyphicon-upload"></i>
													<span>Start</span>
												</button>
												<button type="button" class="btn btn-warning cancel" data-ng-click="file.$cancel()" data-ng-hide="!file.$cancel">
													<i class="glyphicon glyphicon-ban-circle"></i>
													<span>Cancel</span>
												</button>
												<button data-ng-controller="FileDestroyController" type="button" class="btn btn-danger destroy" data-ng-click="file.$destroy()" data-ng-hide="!file.$destroy">
													<i class="glyphicon glyphicon-trash"></i>
													<span>Delete</span>
												</button>
											</td>
										</tr>
									</table>
								</form>	
								<?php

                        echo "</p>";
                    echo "</div>";
                    echo '<div class="modal-footer">';
						echo "<form action='backend.php?p=projecten&project=" . $project . "' method='POST' enctype='multipart/form-data'>";
							echo "<input class='btn btn-default' type='submit' value='Submit' name='submitFile'>";
						echo "</form>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";	
	}

	function stages() {

	}

	function portfolio() {

	}

	function opmerkingen() {

	}

	function gebruikers() {
		global $functions;

		if (isset($_POST["submitRegister"])) {
			$functions->register();
		}
		if (isset($_POST["EditUser"])) {
			$functions->editUser();
		}
		if (isset($_POST["DeleteUser"])) {
			$functions->deleteUser();
		}

		echo "<table class='table table-hover'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>ID</th>";
					echo "<th>E-mail</th>";
					echo "<th>Role</th>";
					echo "<th>Voornaam</th>";
					echo "<th>Achternaam</th>";
					echo "<th>Telefoon nummer</th>";
					echo "<th>Toegang tot:</th>";
					echo "<th></th>";
					echo "<th></th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
				$SQLString = "SELECT * FROM user ORDER BY id ASC";
				$QueryResult = $functions->executeQuery($SQLString);
				$row = mysqli_fetch_all($QueryResult);

				foreach ($row as $index) {
					echo "<tr>";
						echo "<td>" . $index[0] . "</td>";
						echo "<td>" . $index[1] . "</td>";
						echo "<td>" . $index[3] . "</td>";
						echo "<td>" . $index[4] . "</td>";
						echo "<td>" . $index[5] . "</td>";
						echo "<td>" . $index[6] . "</td>";
						echo "<td>" . $index[7] . "</td>";	
						echo "<td><button type='button' class='btn btn-default' data-toggle='modal' data-target='#EditUser" . $index[0] . "' id='EditUser'>Bewerken</button></td>";
						if ($_SESSION["e-mail"] == $index[1]) {
							echo "<td><button type='button' class='btn btn-danger' disabled>Verwijderen</button></td>";
						} else {
							echo "<td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#DeleteUser" . $index[0] . "' id='Deleteuser'>Verwijderen</button></td>";
							//Delete user modal
							echo '<div class="modal fade" id="DeleteUser' . $index[0] . '" role="dialog">';
							echo '<div class="modal-dialog">';
								echo '<div class="modal-content">';
									echo '<div class="modal-header">';
										echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
										echo '<h4 class="modal-title">Weet u zeker dat u de gebruiker: \'' . $index[4] . ' ' . $index[5] . '\' wilt verwijderen?`</h4>';
									echo'</div>';
									echo '<div class="modal-body">';
										echo '<p>';
										echo '<h5><strong>Waarschuwing:</strong> de onderliggende portfolio/bestanden worden ook verwijderd.</h5>';
											echo "<form action='backend.php?p=gebruikers' method='POST'>";
												echo "<input type='hidden' name='userID' value='" . $index[0] . "'><br><br>";
									echo "</div>";
									echo '<div class="modal-footer">';
										echo "<button type='submit' class='btn btn-default' name='DeleteUser'>Verwijder</button></form>";
										echo "</p>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
						}
					echo "</tr>";

					//Edit user modal
					echo '<div class="modal fade" id="EditUser' . $index[0] . '" role="dialog">';
						echo '<div class="modal-dialog">';
							echo '<div class="modal-content">';
								echo '<div class="modal-header">';
									echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
									echo '<h4 class="modal-title">Bewerk deze gebruiker</h4>';
								echo '</div>';
								echo '<div class="modal-body">';
									echo '<p>';
										echo "<form action='backend.php?p=gebruikers' method='POST'>";
											echo "<input type='hidden' value='" . $index[0] . "' name='userID'>";
											echo "<div class='input-group'>";
												echo "<label for='AddEmail'>E-mail:</label><br>";
												echo "<input type='email' class='form-control' name='email' id='AddEmail' value='" . $index[1] . "' placeholder='E-mail' required>";
											echo "</div><br>";
											echo "<div class='input-group'>";
												echo "<label for='Addfname'>Voornaam:</label><br>";
												echo "<input type='text' name='fname' class='form-control' id='Addfname' value='" . $index[4] . "' placeholder='Voornaam' required>";
											echo "</div><br>";
											echo "<div class='input-group'>";
												echo "<label for='Addlname'>Achternaam:</label><br>";
												echo "<input type='text' name='lname' class='form-control' id='Addlname' value='" . $index[5] . "' placeholder='Achternaam' required>";
											echo "</div><br>";
											echo "<div class='input-group'>";
												echo "<label for='AddPhone'>Telefoon nummer:</label><br>";
												echo "<input type='number' name='phone' class='form-control' id='AddPhone' value='" . $index[6] . "' maxlength='16' placeholder='Telefoon nummer'>";
											echo "</div><br>";
											echo "<div class='input-group'>";
												echo "<label for='AddRole'>Rol:</label>";
												if ($_SESSION["e-mail"] == $index[1]) {
													echo "<input type='hidden' name='role'>";
													echo "<select class='form-control' id='AddRole' disabled>";							
												} else {
													echo "<select class='form-control' id='AddRole' name='role'>";
												}
														echo "<option value='student'" . ($index[3] == "student" ? 'selected=\'selected\'' : '') . ">Student</option>";
														echo "<option value='docent'" . ($index[3] == "docent" ? 'selected=\'selected\'' : '') . ">Docent</option>";
														echo "<option value='SLB'" . ($index[3] == "SLB" ? 'selected=\'selected\'' : '') . ">SLB'er</option>";
														echo "<option value='admin'" . ($index[3] == "admin" ? 'selected=\'selected\'' : '') . ">Administrator</option>";
													echo "</select>";
												
											echo "</div><br><b>Waarschuwing: </b>Als de rol van een student verandert wordt de gehele portfolio en alle onderliggende bestanden ook verwijderd.<br><br>";
											if ($index[3] == "docent" || $index[3] == "SLB") {
												echo "<label for='EditFollowButton'>Docent toegang lijst:</label><br>";
												echo "<button type='button' data-toggle='collapse' data-target='#EditFollow" . $index[0] . "' id='EditFollowButton' class='btn btn-default'>Pas aan</button>";
												echo "<div id='EditFollow" . $index[0] . "' class='collapse'><br>";
													echo "Pas de leerlingen die bij deze docent horen aan:<br><br>";

													$SQLString = "SELECT firstName, lastName, id FROM user WHERE role = 'student'";
													$QueryResult = $functions->executeQuery($SQLString);
													$UserList = mysqli_fetch_all($QueryResult);

													$followingArray = explode(',', $index[7]);

													foreach ($UserList as $index => $value) {
														echo "<label class='checkbox-inline'><input type='checkbox' name='following[]' value='" . $value[2] . "' " . (in_array($value[2], $followingArray) ? 'checked' : '') . ">" . $value[0] . " " . $value[1] . "</label>";	
													}
											
												echo "</div>";	
											}							
									echo "</p>";
								echo "</div>";
								echo '<div class="modal-footer">';
									echo "<button class='btn btn-default' type='submit' name='EditUser'>Bewerk</button></form>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}
			echo "</tbody>";
		echo "</table>";

		echo "<button type='button' class='btn btn-default' id='addUserButton' data-toggle='modal' data-target='#AddUser'>Voeg gebruiker toe</button>";

		//Add user modal
		echo '<div class="modal fade" id="AddUser" role="dialog">';
		echo '<div class="modal-dialog">';
			echo '<div class="modal-content">';
				echo '<div class="modal-header">';
					echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
					echo '<h4 class="modal-title">Voeg een nieuwe gebruiker toe:</h4>';
				echo'</div>';
				echo '<div class="modal-body">';
					echo '<p>';
					echo "<form action='backend.php?p=gebruikers' method='post'>";
						echo "<div class='input-group'>";
							echo "<label for='AddEmail'>E-mail:</label><br>";
							echo "<input type='email' class='form-control' name='email' id='AddEmail' placeholder='E-mail' required>";
						echo "</div><br>";
						echo "<div class='input-group'>";
							echo "<label for='AddPassword'>Wachtwoord:</label><br>";
							echo "<input type='password' name='password' class='form-control' id='AddPassword' placeholder='Password' required>";
						echo "</div><br>";
						echo "<div class='input-group'>";
							echo "<label for='Addfname'>Voornaam:</label><br>";
							echo "<input type='text' name='fname' class='form-control' id='Addfname' placeholder='First name' required>";
						echo "</div><br>";
						echo "<div class='input-group'>";
							echo "<label for='Addlname'>Achternaam:</label><br>";
							echo "<input type='text' name='lname' class='form-control' id=Addlname placeholder='Last name' required>";
						echo "</div><br>";
						echo "<div class='input-group'>";
							echo "<label for='AddPhone'>Telefoon nummer:</label><br>";
							echo "<input type='number' name='phone' class='form-control' id='AddPhone' maxlength='16' placeholder='Phone Number'>";
						echo "</div><br>";
						echo "<div class='input-group'>";
							echo "<label for='AddRole'>Rol:</label><br>";
							echo "<select class='form-control' id='AddRole' name='role'><br><br>";
								echo "<option value='student'>Student</option>";
								echo "<option value='docent'>Docent</option>";
								echo "<option value='SLB'>SLB'er</option>";
								echo "<option value='admin'>Administrator</option>";
							echo "</select>";
						echo "</div>";
				echo "</div>";
				echo '<div class="modal-footer">';
					echo "<button class='btn btn-default' type='submit' name='submitRegister'>Verzenden</button></form>";
					echo "</p>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";		
	}
}