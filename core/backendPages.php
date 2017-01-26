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

	function search() {
		global $functions;
		global $project;

		if (isset($_POST['submit'])) {
			if (empty($_POST['search'])){
				echo "U moet een zoekterm invullen.";
			} else {
				$search = htmlspecialchars($_POST['search']);
				$SQLString = 'SELECT id, title, description FROM project WHERE portfolio_id = ' . $_SESSION['portfolio_id'] . ' AND title LIKE "%' . $search . '%"';
				$QueryResult = $functions->executeQuery($SQLString);
				$row = mysqli_fetch_all($QueryResult);
				for ($i = count($row) - 1;$i >= 0;$i--) {
		            echo "<div class='fileblock'>";
		                echo "<div class='file' onclick='location.href = \"backend.php?p=projecten&project=" . $row[$i][0] . "\";'>";
		                    echo "<button type='button' class='btn-link'>";
		                        echo "<span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>";
		                    echo "</button>";
		                echo "</div>";
		                echo "<div class='filemenu'>";
							echo "<div class='FolderTitle'>" . $row[$i][1] . "</div>";
		                    echo "<div class='btn-group'>";
		                        echo "<button type='button' class='btn btn-link'>";
		                            echo "<span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit' data-toggle='modal' data-target='#EditFolder" . $row[$i][0] . "'></span>";
		                        echo "</button>";
		                        echo "<button type='button' class='btn btn-link'>";
		                            echo "<span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete' data-toggle='modal' data-target='#DeleteFolder" . $row[$i][0] . "'></span>";
		                        echo "</button>";
		                    echo "</div>";
		                echo "</div>";	
					echo "</div>";

					//Edit Folder modal
					echo '<div class="modal fade" id="EditFolder' . $row[$i][0] . '" role="dialog">';
						echo '<div class="modal-dialog">';
							echo '<div class="modal-content">';
								echo '<div class="modal-header">';
									echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
									echo '<h4 class="modal-title">Bewerk deze map.</h4>';
								echo '</div>';
								echo '<div class="modal-body">';
									echo '<p>';
										echo "<form action='backend.php?p=projecten' method='POST'>";
											echo "<input type='hidden' name='folderID' value='" . $row[$i][0] . "'>";
											echo "<div class='form-group'>";
												echo "<label for='EditFolderName'>Bestands Naam:</label><br>";
												echo "<input type='text' class='form-control' id='EditFolderName' name='folderName' value='" . $row[$i][1] . "' placeholder='Name' required>";
											echo "</div>";
											echo "<div class='form-group'>";
												echo "<label for='EditFolderDesc'>Beschrijving:</label><br>";
												echo "<textarea name='folderDesc' class='form-control' id='EditFolderDesc' maxlength='500' placeholder='Description'>" . $row[$i][2] . "</textarea>";
											echo "</div>";
											echo "</div>";
											echo '<div class="modal-footer">';
											echo "<button type='submit' class='btn btn-default' name='folderEdit'>Bewerk</button></form>";
									echo "</p>";
								echo "</div>";				
							echo "</div>";
						echo "</div>";
					echo "</div>";

					//Delete Folder modal
					echo '<div class="modal fade" id="DeleteFolder' . $row[$i][0] . '" role="dialog">';
						echo '<div class="modal-dialog">';
							echo '<div class="modal-content">';
								echo '<div class="modal-header">';
									echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
									echo '<h4 class="modal-title">Weet u zeker dat u de map: \'' . $row[$i][1] . '\' wilt verwijderen?`</h4>';
								echo'</div>';
								echo '<div class="modal-body">';
									echo '<p>';
										echo '<h5><strong>Waarschuwing:</strong> alle onderliggende bestanden worden ook verwijderd.</h5>';
										echo "<form action='backend.php?p=projecten' method='POST'>";
											echo "<input type='hidden' name='folderToDelete' value='" . $row[$i][0] . "'><br><br>";
									echo "</p>";
								echo "</div>";
								echo '<div class="modal-footer">';
									echo "<button type='submit' class='btn btn-default' name='folderDelete'>Verwijder</button></form>";
									echo "</p>";						
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}
				$SQLString = "SELECT 
							  FILE.`id`,
							  FILE.`title`,
							  `type`
							FROM FILE
							JOIN
							  project ON `project_id` = project.`id`
							WHERE
							  portfolio_id = '" . $_SESSION['portfolio_id'] . "' AND FILE.`title` LIKE '%" . $search .  "%'";
				$queryresult = $functions->executeQuery($SQLString);
				if (mysqli_num_rows($QueryResult) == 0 && mysqli_num_rows($queryresult) == 0) {
					echo "Er zijn geen resultaten voor de zoekterm: " . $search;
				}
				$row = mysqli_fetch_all($queryresult);
				for ($i = count($row) - 1;$i >= 0;$i--) {
					$SQLString = "SELECT project_id FROM file WHERE id = " . $row[$i][0];
					$Query = $functions->executeQuery($SQLString);
					$rowProject = mysqli_fetch_assoc($Query);
					$project = $rowProject['project_id'];

		            echo "<div class='fileblock'>";
		                echo "<div class='file' onclick='window.open(\"front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/" . $row[$i][1] . $row[$i][2] . "\")'>";
		                    echo "<button type='button' class='btn-link'>";
		                        echo "<span class='glyphicon glyphicon-file' aria-hidden='true'></span>";
		                    echo "</button>";
		                echo "</div>";
		                echo "<div class='filemenu'>";
							echo "<div class='FolderTitle'>" . $row[$i][1] . $row[$i][2] . "</div>";
		                    echo "<div class='btn-group'>";
								echo "<button type='button' class='btn btn-link'>";
									echo "<span class='glyphicon glyphicon-open' aria-hidden='true' title='Download' onclick='window.open(\"front-end/res/portfolios/" . $_SESSION["portfolio_id"] . "/" . $project . "/" . $row[$i][1] . $row[$i][2] . "\")'></span>";
								echo "</button>";
		                        echo "<button type='button' class='btn btn-link'>";
		                            echo "<span class='glyphicon glyphicon-pencil' aria-hidden='true' title='Edit' data-toggle='modal' data-target='#EditFile" . $row[$i][0] . "'></span>";
		                        echo "</button>";
		                        echo "<button type='button' class='btn btn-link'>";
		                            echo "<span class='glyphicon glyphicon-trash' aria-hidden='true' title='Delete' data-toggle='modal' data-target='#DeleteFile" . $row[$i][0] . "'></span>";
		                        echo "</button>";
		                    echo "</div>";
		                echo "</div>";	
					echo "</div>";

					//Edit File modal
					echo '<div class="modal fade" id="EditFile' . $row[$i][0] . '" role="dialog">';
						echo '<div class="modal-dialog">';
							echo '<div class="modal-content">';
								echo '<div class="modal-header">';
									echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
									echo '<h4 class="modal-title">Bewerk dit bestand.</h4>';
								echo'</div>';
								echo '<div class="modal-body">';
									echo '<p>';
										echo "<form action='backend.php?p=projecten&project=" . $project . "' method='POST'>";
											echo "<input type='hidden' name='fileID' value='" . $row[$i][0] . "'>";
											echo "<input type='hidden' name='projectID' value='" . $project . "'>";
											echo "<div class='input-group'>";
												echo "<label for='EditFileName'>Bestands Naam:</label><br>";
												echo "<input type='text' class='form-control' id='EditFileName' name='fileName' value='" . $row[$i][1] . "' placeholder='Name' required><br><br>";
											echo "</div>";
											echo "<div class='input-group'>";
												echo "<label for='EditFileDesc'>Beschrijving:</label><br>";
												echo "<input type='text' class='form-control' id='EditFileDesc' name='fileDesc' value='" . $row[$i][3] . "' placeholder='Description'>";
											echo "</div>";
									echo "</p>";
								echo "</div>";
								echo '<div class="modal-footer">';
									echo "<button type='submit' class='btn btn-default' name='fileEdit'>Bewerken</button></form>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";

					//Delete File modal
					echo '<div class="modal fade" id="DeleteFile' . $row[$i][0] . '" role="dialog">';
						echo '<div class="modal-dialog">';
							echo '<div class="modal-content">';
								echo '<div class="modal-header">';
									echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
									echo '<h4 class="modal-title">Weet u zeker dat u het bestand: \'' . $row[$i][1] . $row[$i][2] . '\' wilt verwijderen?</h4>';
								echo'</div>';
								echo '<div class="modal-body">';
									echo '<p>';
										echo "<form action='backend.php?p=projecten&project=" . $project . "' method='POST'>";
											echo "<input type='hidden' name='fileToDelete' value='" . $row[$i][0] . "'><br><br>";
											echo "<input type='hidden' name='projectID' value='" . $project . "'>";
								echo "</div>";
								echo '<div class="modal-footer">';	
										echo "<button type='submit' class='btn btn-default' name='fileDelete'>Verwijder</button></form>";
									echo "</p>";								
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}
			}
		} else {
			echo "U moet een zoekterm invullen.";
		}
	}

	function info() {
		global $functions;

		if (isset($_POST["info_submit"])) {
			$functions->submitInfo();
		}

		echo "<h4>Bewerk je profiel:</h4>";
		echo "<div class='infoBlock'>";
			echo "<form method='post' action='backend.php?p=info'>";
				$SQLString = "SELECT title, layout, bg_color, font_color, owner_id FROM portfolio WHERE id = " . $_SESSION["portfolio_id"];
				$QueryResult = $functions->executeQuery($SQLString);
				$rowPort = mysqli_fetch_assoc($QueryResult);

				$SQLString = "SELECT Opleiding, Interesses, Werkervaring, Hobbies, Info FROM persoonlijkeinfo WHERE user_id = " . $rowPort["owner_id"];
				$QueryResult = $functions->executeQuery($SQLString);
				$rowInfo = mysqli_fetch_assoc($QueryResult);

				echo "<div class='input-group'>";
					echo "<label for='info_titel'>Portfolio titel:</label><br>";
					echo "<input type='text' class='form-control' value='" . $rowPort["title"] . "' name='info_title' id='info_title' placeholder='Portfolio titel' required>";
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo "<label for='info_color_bg'>Achtergrond kleur:</label><br>";
					echo "<input type='color' name='info_color_bg' value='" . $rowPort["bg_color"] . "' class='form-control' id='info_color_bg' required>";
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo "<label for='info_color_font'>Font kleur:</label><br>";
					echo "<input type='color' name='info_color_font' value='" . $rowPort["font_color"] . "' class='form-control' id='info_color_font' required>";
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo '<label class="radio-inline"><input type="radio" value="list" name="info_layout" ' . ($rowPort["layout"] == "list" ? 'checked' : '') . ' required>List</label>';
					echo '<label class="radio-inline"><input type="radio" value="grid1" name="info_layout" ' . ($rowPort["layout"] == "grid1" ? 'checked' : '') . ' required>Big grid</label>';
					echo '<label class="radio-inline"><input type="radio" value="grid2" name="info_layout" ' . ($rowPort["layout"] == "grid2" ? 'checked' : '') . ' required>Small grid</label>';
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo "<label for='info_study'>Opleiding:</label><br>";
					echo "<input type='text' name='info_study' value='" . $rowInfo["Opleiding"] . "' class='form-control' id='info_study'>";
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo "<label for='info_interests'>Interesses:</label><br>";
					echo "<input type='text' name='info_interests' value='" . $rowInfo["Interesses"] . "' class='form-control' id='info_interests'>";
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo "<label for='info_experience'>Werkervaring:</label><br>";
					echo "<input type='text' name='info_experience' value='" . $rowInfo["Werkervaring"] . "' class='form-control' id='info_experience'>";
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo "<label for='info_hobby'>Hobbies:</label><br>";
					echo "<input type='text' name='info_hobby' value='" . $rowInfo["Hobbies"] . "' class='form-control' id='info_hobby'>";
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo "<label for='info_description'>Beschrijving:</label><br>";
					echo "<textarea name='info_description' class='form-control' id='info_description'>" . $rowInfo["Info"] . "</textarea>";
				echo "</div><br>";
				echo "<div class='input-group'>";
					echo "<button type='submit' name='info_submit' class='btn btn-default'>Bewerken</button>";
				echo "</div>";
			echo "</form>";
		echo "</div>";
	}

	function cijfers() {
		global $functions;

		if (isset($_POST["submitCijfer"])) {
			$functions->EditCijfer();
		}

		echo "<table class='table table-hover'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Project</th>";
					echo "<th>Cijfer</th>";
					echo "<th>Gegeven door</th>";
					echo "<th>Opmerking</th>";
					echo "<th></th>";
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

							$role = $_SESSION['role'];

							echo "<td>";
							if ($role == "admin" || $role == "docent" || $role == "SLB") {
								echo "<button type='button' class='btn btn-default' data-toggle='modal' data-target='#EditGrade" . $index[0] . "'>Verander cijfer</button>"; 
								echo '<div class="modal fade" id="EditGrade' . $index[0] . '" role="dialog">';
								echo '<div class="modal-dialog">';
									echo '<div class="modal-content">';
										echo '<div class="modal-header">';
											echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
											echo '<h4 class="modal-title">Pas het cijfer aan.</h4>';
										echo'</div>';
										echo '<div class="modal-body">';
											echo '<p>';
												echo "<form action='backend.php?p=cijfers' method='POST'>";
													echo "<div class='form-group'>";
														echo "<input type='hidden' name='projectID' value='" . $index[0] . "'>";
														echo "<label for='cijfer'>Cijfer</label>";
														echo "<input type='number' name='cijfer' class='form-control' id='cijfer' min='1' max='10' step='0.1' value='" . $grade . "' required>";
													echo "</div>";
													echo "<div class='form-group'>";
														echo "<label for='cijferOpmerking'>Opmerking:</label>";
														echo "<textarea name='cijferOpmerking' placeholder='Opmerking' class='form-control' maxlength='500' id='cijferOpmerking'>" . $index[4] . "</textarea>";
													echo "</div>";
											echo "</p>";
										echo "</div>";
										echo '<div class="modal-footer">';
											echo "<button type='submit' class='btn btn-default' value='Verzend' name='submitCijfer'>Verzend</button></form>";
										echo "</div>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						}
						echo "</td></tr>";				
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

	function opmerkingen() {
        //comments
        global $functions;
 
        //new comment
        if(!empty($_POST['commentContent'])){
            $commentContent = addslashes(htmlspecialchars($_POST['commentContent']));
            $placer_id = $_SESSION['id'];
            $portId = $_SESSION['portfolio_id'];
            $SQLString = "INSERT INTO comment(message, placer_id, portfolio_id) VALUES('$commentContent','$placer_id','$portId')";
            $QueryResult = $functions->executeQuery($SQLString);
            if ($QueryResult) {
                echo "<p>Je opmerking is geplaatst</p>";
            }else {
                echo "<p>Er was een fout bij het plaatsen van je opmerking.</p>";
            }
        }
 
        //get placed comments
        $portId = $_SESSION['portfolio_id'];
        $SQLString = "SELECT comment.placement_date, comment.message, user.firstName, user.lastName FROM comment INNER JOIN user ON comment.placer_id=user.id WHERE portfolio_id = '$portId' ORDER BY placement_date desc";
        $QueryResult = $functions->executeQuery($SQLString);
        $row = mysqli_fetch_all($QueryResult);
        if (mysqli_num_rows($QueryResult) > 0) {
            $comments = $row;
        }
 
        echo "
            <form method='POST' id='commentForm' action='backend.php?p=opmerkingen'>
                <h4>Plaats opmerking</h4>
                <textarea maxlength='255' name='commentContent'></textarea>
                <button class='btn btn-default' type='submit'>plaatsen</button>
            </form>
            <h4>Opmerkingen geplaatst op deze portfolio</h4>
        ";
        if(!empty($comments)){
            foreach($comments as $i => $comment){
                echo "
                <div class='comment'>
                    <p >Geplaatst door: {$comment[2]} {$comment[3]} op {$comment[0]}</p>
                    <div class='commentContent'>
                        <p>{$comment[1]}</p>
                    </div>
                </div>
                ";
            }
        }else{
            echo "<p>Er zijn hier nog geen opmerkingen.</p>";
        }
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