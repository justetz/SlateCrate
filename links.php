<?php
// Configurations and includes for PHP
require 'resources/functions.php';
require 'resources/config.php';
require 'resources/rpiCAS.php';

if (!phpCAS::isAuthenticated()) {
	// If they're not currently logged in, take them to the RPI CAS page
    phpCAS::forceAuthentication();
}

// Establish a connection to the database for this page.
$conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);

require 'partials/head.partial.php';
require 'partials/navigation.partial.php';

try {
    if(isset($_POST["edit"])){
        $conn->query("UPDATE `links` SET `title` = '" . $_POST["linkName"] . "', `link` = '" . $_POST["URL"] . "' WHERE `link_id` = " . $_POST["edit"]);
    }

	if(isset($_GET["class"])) {
		$c = $_GET["class"];
		$var = $conn->prepare("SELECT `title` FROM `categories` WHERE `category_id` = $c");
		$var->execute();
		$result = $var->fetch(PDO::FETCH_ASSOC);
		$categoryTitle = $result["title"];
	}
	$admin = $conn->prepare("SELECT `isadmin` FROM `users` WHERE `rcs_id` = '" . phpCAS::getUser() . "'");
	$admin->execute();
	$isadmin = false;
	while($result = $admin->fetch(PDO::FETCH_ASSOC)){
		if($result["isadmin"] == 1){ $isadmin = true; }
	}
} catch(PDOException $e) {
	echo $e;
}

if(isset($_GET["class"])) {
	$pageHeader = "Links for " . $categoryTitle;
} else {
	$pageHeader = "Links for all classes";
}

require 'partials/pageheader.partial.php';
?>
<div class="container mtb">
<?php
//up or downvote
if(isset($_POST["upvote"])){
    $id = $_POST["upvote"];
    $vote = $conn->prepare("SELECT `score` FROM `links` WHERE `link_id` = " . $id);
    $vote->execute();
    $score = ($vote->fetch(PDO::FETCH_ASSOC));
    $score = $score["score"] + 1;
    $conn->query("UPDATE `links` SET `score` = " . $score . " WHERE `link_id` = " . $id);
}else if(isset($_POST["downvote"])){
    $id = $_POST["downvote"];
    $vote = $conn->prepare("SELECT `score` FROM `links` WHERE `link_id` = " . $id);
    $vote->execute();
    $score = ($vote->fetch(PDO::FETCH_ASSOC));
    $score = $score["score"] - 1;
    $conn->query("UPDATE `links` SET `score` = " . $score . " WHERE `link_id` = " . $id);
}

//add class if we need to
if(isset($_POST["user"])){
    try{
        $string = "'" . $_POST["URL"] . "', '" . phpCAS::getUser() . "', " . $_POST["classForAdd"] . ", " . "CURDATE(), '" . $_POST["linkName"] . "'";

        $conn->query("INSERT INTO `links` (`link`, `rcs_id`, `category_id`, `creation_date`, `title`)
            VALUES (" . $string . ");");

			echo "<div class='row'><div class='col-xs-12'>" .
					successAlert("Your new link, entitled " . $_POST["linkName"] . ", was successfully added!") .
				"</div></div>";
    }catch(PDOException $e){
		echo "<div class='row'><div class='col-xs-12'>" .
				errorAlert($e) .
				"</div></div>";
    }
}
if(isset($_POST["delete"])){
	try {
    	$conn->query("DELETE FROM `links` WHERE `link_id` = " . $_POST["delete"]);

		echo "<div class='row'><div class='col-xs-12'>" .
				successAlert("The link was successfully deleted!") .
			"</div></div>";
	} catch(PDOException $e) {
		echo "<div class='row'><div class='col-xs-12'>" .
				errorAlert($e) .
			 "</div></div>";
    }
}

//sort
$sort = "`score`";
if(isset($_POST["sort"])){
    $sort = $_POST["sort"];
}

try {
    $search = "";
    if(isset($_POST["srch"])){ $search = $_POST["srch"]; }
	if(isset($_GET["class"])) {
		$var = $conn->prepare("SELECT * FROM `links` WHERE `category_id` = $c AND `title` LIKE '%$search%' ORDER BY $sort");
		$var->execute();
	} else {
		$var = $conn->prepare("SELECT * FROM `links` ORDER BY `score` WHERE `title` LIKE '%$search%' ORDER BY $sort");
		$var->execute();
	}
} catch(PDOException $e) {
	echo $e;
}
?>

    <div class="row">
		<div class="col-md-4 col-sm-3">
			<a href="classes.php" class="btn btn-primary">
				<?php
				if(isset($_GET["class"])) {
					echo "<span class=\"fa fa-chevron-left\"></span> Back to Classes";
				} else {
					echo "View Classes";
				}
				?>

			</a>
		</div>
		<div class="col-md-4 col-sm-6">
			<div class="form-group form-group-sm">
                <form method="post">
				    <input name="srch" value="" class="form-control" placeholder="Search Links" />
                </form>
			</div>
		</div>

        <!--sort by-->
        <form method="post">
            <button type="submit" class="btn btn-default" name="sort" value="`title`">Sort by name</button>
            <button type="submit" class="btn btn-default" name="sort" value="`score` DESC">Sort by likes</button>
            <button type="submit" class="btn btn-default" name="sort" value="`creation_date`">Sort by date</button>
        </form>

		<div class="col-md-4">
			<?php
				echo "<a href='addlink.php?";
				if(isset($_GET["class"])) {
					echo "class=" . $_GET["class"];
				}
				echo "' class='btn btn-primary pull-right'>
					<span class='fa fa-plus'></span>
					Add a link
				</a>";
			?>
		</div>
	</div>
	<br/>
	<div class="row">
        <div class="col-md-12">
            <?php
                try{
                    //if(isset($_GET["class"])){
                        $count = 0;
                        if(isset($_GET["page"])){
                            $p = $_GET["page"];
                        }else{ $p = 1; }

                        echo "<div class='row'>";
						$data = $var->fetchAll(PDO::FETCH_ASSOC);

						foreach($data as $result) {
                            if($count >= ($p - 1) * 16 && $count < $p * 16){
    							$categoryHTML = "<h6 class='text-muted'>";
    							if(!isset($_GET["class"])) {
    								$var = $conn->prepare("SELECT `title`,`prefix` FROM `categories` WHERE `category_id` = " . $result["category_id"]);
    								$var->execute();
    								$r2 = $var->fetch(PDO::FETCH_ASSOC);
    								$categoryHTML .= $r2['title'] . " (" . $r2['prefix'] . ")";
    							} else {
    								$categoryHTML .= "Link";
    							}
    							$categoryHTML.= "</h6>";

    					    	echo "<div class='col-md-3'>
    									<a href='".$result["link"]."' target=\"_blank\">
    										<div class='well well-sm well-hover'>"
    										. $categoryHTML
    										. "<h4>".$result["title"]."</h4>"
                                            . "<p>Score: ".$result["score"]."</p>"
    										. "<p class='text-muted small'>
    											<span class='pull-left'>
    												submitted by " . $result["rcs_id"] .
    											"</span>
    											<span class='pull-right'>" . $result["creation_date"] . "</span>
    											<span class='clearfix'></span>
    										   </p>
    									   </div>
    								   	 </a>";

                                    //for getting back to the same page you started on
                                    $class = "?";
                                    $page = "";
                                    if(isset($_GET["class"])) {
                                        $class .= "class=" . $_GET["class"];
                                    }
                                    if(isset($_GET["page"])){
                                        if($class == "?"){ $page = "?page=" . $_GET["page"]; }
                                        else{ $page = $class . "&page=" . $_GET["page"]; }
                                    }

                                    //if the user is an administrator, they can delete and edit
    								if($isadmin || $result["rcs_id"] == phpCAS::getUser()){
                                        //delete button
                                        echo "<form class='admin-panel' method=\"post\" action='links.php$class' class=\"form-horizontal\">";
                                        echo "<button type=\"submit\" class=\"btn btn-primary pull-right\" name=\"delete\" value=" . $result["link_id"] . ">Delete</button></form>";

                                        //edit button
                                        echo "<form class='admin-panel' method=\"post\" action='editlink.php" . $page . "' class=\"form-horizontal\">";
                                        echo "<button type=\"submit\" class=\"btn btn-primary pull-right\" name=\"edit\" value=" . $result["link_id"] . ">Edit</button></form>";
    				                }

                                    //for upvote and downvote
                                    echo "<form class='admin-panel' method=\"post\" action='links.php$page" . $page . "' class=\"form-horizontal\">";
                                    echo "<button type=\"submit\" class=\"btn btn-primary pull-right\" name=\"upvote\" value=" . $result["link_id"] . ">Upvote</button></form>";
                                    echo "<form class='admin-panel' method=\"post\" action='links.php$page" . $page . "' class=\"form-horizontal\">";
                                    echo "<button type=\"submit\" class=\"btn btn-primary pull-right\" name=\"downvote\" value=" . $result["link_id"] . ">Downvote</button></form>";
    								
                                    echo "</div>";
                                }

                            $count++;
                        }

                        if($count == 0){
                            echo "No links. You should add one.";
                        }


                    // }
                    // else{
                    //     echo "Error, no class selected. Select a class at <a href='classes.php'>Posts</a>.";
                    // }
                }catch(PDOException $e){ echo $e; }

				if(($count / 16) + 1 >= 2) {
					echo "<div class=\"clearfix\"></div><div class=\"col-xs-12 centered\"><hr/><div class=\"btn-group\">";

					for ($button = 1; $button < ($count / 16) + 1; $button++) {
						$link = "?";
						if (isset($_GET["class"])) {
							$link = $link . "class=" . $_GET["class"] . "&";
						}
						$link .= "page=$button";
						echo "<a href=\"$link\" class=\"btn btn-primary\">$button</a>";
					}
					echo "</div></div>";
				}
            ?>
            </div>
        </div>
    </div>
    <!--/row -->
</div>
<!--/container -->

<?php require 'partials/footer.partial.php'; ?>




</body>
</html>
