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
	if(isset($_GET["class"])) {
		$c = $_GET["class"];
		$var = $conn->prepare("SELECT `title` FROM `categories` WHERE `category_id` = $c");
		$var->execute();
		$result = $var->fetch(PDO::FETCH_ASSOC);
		$categoryTitle = $result["title"];

		$var = $conn->prepare("SELECT * FROM `links` WHERE `category_id` = $c");
		$var->execute();
	} else {
		$var = $conn->prepare("SELECT * FROM `links`");
		$var->execute();
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
//add class if we need to
if(isset($_POST["linkName"])){
    try{
        $string = "'" . $_POST["URL"] . "', " . $_POST["user"] . ", " . "CURDATE(), '" . $_POST["linkName"] . "'";

        $conn->query("INSERT INTO `links` (`link`, `rcs_id`, `category_id`, `creation_date`, `title`)
            VALUES (" . $string . ");");

			echo "<div class='row'><div class='col-xs-12'>
				 <div class='alert alert-success alert-dismissible' role='alert'>
					<button type='button' class='close' data-dismiss='alert' aria-label='Close><span aria-hidden='true'>&times;</span></button>
					<strong>Success!</strong> Your new link, entitled " . $_POST["linkName"] . ", was successfully added!
				 </div></div></div>";
    }catch(PDOException $e){
        echo $e;
    }
}
if(isset($_POST["delete"])){
    $conn->query("DELETE FROM `links` WHERE `link_id` = " . $_POST["delete"]);
}
?>

    <div class="row">
        <div class="col-md-12">
            <?php
                try{
                    //if(isset($_GET["class"])){
                        $count = 0;

                        echo "<div class='row'>";
                        while($result = $var->fetch(PDO::FETCH_ASSOC)){
                            echo "<a href='";
                            echo $result["link"];
                            echo "' target=\"_blank\"><div class='col-md-3'><div class='well well-sm well-hover'><h6 class='text-muted'>Link</h6><h4>";
                            echo $result["title"];
                            echo "</h4><p class='text-muted small'><span class='pull-left'>submitted by ";
                            echo $result["rcs_id"];
                            echo "</span><span class='pull-right'>";
                            echo $result["creation_date"];
                            echo "</span>";
                            if($isadmin){
                                echo "<form method=\"post\" action='links.php?class=" . $_GET["class"] . "' class=\"form-horizontal\">";
                                echo "<button type=\"submit\" class=\"btn btn-primary pull-right\" name=\"delete\" value=" . $result["link_id"] . ">Delete</button></form>";
                            }
                            echo "<span class='clearfix'></span></p></div></div></a>";
                            $count++;
                        }

                        if($count == 0){
                            echo "No links. You should add one.";
                        }else{
                            echo "Found $count links";
                        }
						if(isset($_GET["class"])){
                        	echo "<a href='addLink.php?class=$c'>Add a link</a>";
						}
                    // }
                    // else{
                    //     echo "Error, no class selected. Select a class at <a href='classes.php'>Posts</a>.";
                    // }
                }catch(PDOException $e){ echo $e; }
            ?>
                <div class="col-xs-12 centered">
                    <div class="btn-group">
                        <a href="" class="btn btn-primary">1</a>
                        <a href="" class="btn btn-primary">2</a>
                        <a href="" class="btn btn-primary">3</a>
                        <a href="" class="btn btn-primary">4</a>
                        <a href="" class="btn btn-primary">5</a>
                        <a href="" class="btn btn-primary">6</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/row -->
</div>
<!--/container -->

<?php require 'partials/footer.partial.php'; ?>




</body>
</html>
