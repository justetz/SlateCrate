<?php
require 'resources/functions.php';
require 'resources/config.php';
require 'resources/rpiCAS.php';

if (!phpCAS::isAuthenticated()) {
// If they're not currently logged in, take them to the RPI CAS page
    phpCAS::forceAuthentication();
}

$conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);
?>
<!DOCTYPE html>
<html lang="en">
<?php require 'partials/head.partial.php'; ?>
<body>
<?php
require 'partials/navigation.partial.php';

// Set the page heading appropriately, depending on if the url specifies a prefix
if(isset($_GET["prefix"])) {
	$pageHeader = "Classes for " . $_GET["prefix"];
} else {
	$pageHeader = "All Classes";
}
/**
 * Checks the provided prefix and returns the needed HTML code to mark the list
 * item as active, only if there is a prefix passed to the page and it matches
 * the parameter
 * @param  string $prefix the prefix to check
 * @return boolean        whether or not it matches
 */
function determineIfActive($prefix) {
	if(isset($_GET["prefix"]) && $_GET["prefix"] == $prefix) {
		return "class='active'";
	}
	return "";
}

require 'partials/pageheader.partial.php';
?>
<div class="container mtb">
<?php
//add class if we need to
if(isset($_POST["className"])){
    try{
        $string = "'" . $_POST["className"] . "', '" . $_POST["inputCategory"] . "', '" . $_POST["user"] . "', " . "CURDATE()";

		$conn->query("INSERT INTO `categories` (`title`, `prefix`, `rcs_id`, `creation_date`)
            VALUES (" . $string . ");");

        echo "<div class='row'><div class='col-xs-12'>
			 <div class='alert alert-success alert-dismissible' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close><span aria-hidden='true'>&times;</span></button>
				<strong>Success!</strong> Your new class, entitled " . $_POST["className"] . ", was successfully added!
			 </div></div></div>";

    }catch(PDOException $e){
        echo "<div class='row'><div class='col-xs-12'>
			 <div class='alert alert-danger alert-dismissible' role='alert'>
				<button type='button' class='close' data-dismiss='alert' aria-label='Close><span aria-hidden='true'>&times;</span></button>
				<strong>Error!</strong> " . $e . "
			 </div></div></div>";
    }
}
if(isset($_POST["delete"])){
    $del = $conn->prepare("SELECT `link_id` FROM `links` WHERE `category_id` = " . $_POST["delete"]);
    $del->execute();
    while($result = $del->fetch(PDO::FETCH_ASSOC)){
        $conn->query("DELETE FROM `links` WHERE `link_id` = " . $result["link_id"]);
    }
    $conn->query("DELETE FROM `categories` WHERE `category_id` = " . $_POST["delete"]);
}
?>
    <div class="row">
        <div class="col-md-9">
            <?php
                try{
                    if(isset($_GET["prefix"])){
                        $p = "'" . $_GET["prefix"] . "'";
                        $var = $conn->prepare("SELECT * FROM `categories` WHERE `prefix` = $p ORDER BY `title`");
                    }else{ $var = $conn->prepare("SELECT * FROM `categories` ORDER BY `title`"); }
                    $var->execute();

                    //Check if current user is an admin
                    $admin = $conn->prepare("SELECT `isadmin` FROM `users` WHERE `rcs_id` = '" . phpCAS::getUser() . "'");
                    $admin->execute();
                    $isadmin = false;
                    while($result = $admin->fetch(PDO::FETCH_ASSOC)){
                        if($result["isadmin"] == 1){ $isadmin = true; }
                    }

                    $count = 0;
                    if(isset($_GET["page"])){
                        $p = $_GET["page"];
                    }else{ $p = 1; }

                    echo "<div class='row'>";
                    while($result = $var->fetch(PDO::FETCH_ASSOC)){
                        if($count >= ($p - 1) * 24 && $count < $p * 24){
                        echo "<a href='links.php?class=".$result["category_id"]."''>
							<div class='col-md-6'><div class='well well-sm well-hover'>
								<h6 class='text-muted'>".$result["prefix"]."</h6>
								<h4>".$result["title"]."</h4>
								<p>Contains ".$result["links"]." links.</p>
								<p class='text-muted small info-text'>
									<span class='pull-left'>submitted by ".$result["rcs_id"]."</span>
									<span class='pull-right'>".$result["creation_date"]."</span>";
                        if($isadmin){
                            echo "<form method=\"post\" action='classes.php' class=\"form-horizontal\">";
                            echo "<button type=\"submit\" class=\"btn btn-primary pull-right\" name=\"delete\" value=" . $result["category_id"] . ">Delete</button></form>";
                        }
                        $count++;
                    }

                    if($count == 0){
                        echo "<div class='col-xs-12'>
							 <div class='alert alert-info' role='alert'>
								No classes. You should add one!
							 </div></div>";
                    }
                }catch(PDOException $e){ echo $e; }
                echo "<div class=\"col-xs-12 centered\"><div class=\"btn-group\">";
                for ($button=1; $button < ($count / 24) + 1; $button++) {
                    $link = "?";
                    if(isset($_GET["prefix"])){ $link = $link . "prefix=". $_GET["prefix"] ."&"; }
                    $link .= "page=$button";
                    echo "<a href=\"$link\" class=\"btn btn-primary\">$button</a>";
                }
                echo "</div></div>";
            ?>
            </div>
        </div>
        <div class="col-md-3">
			<div class="btn-group btn-group-justified">
				<a class='btn btn-primary' href='addClass.php'>Add a class</a>
			</div>
			<br/>
			<ul class="nav nav-pills nav-stacked">
                <li role="presentation"><a href="?">All Prefixes</a></li>
				<?php
					/**
					 * This array contains all valid prefixes at RPI. These
					 * values will be used to populate the sidebar of the page.
					 * @var array
					 */
					$prefixes = [
						"ARCH", "ARTS", "ASTR", "BCBP", "BIOL", "BMED", "CHEM",
						"CISH", "CSCI", "DSES", "ECON", "ECSE", "ENGR", "ENVE",
						"ERTH", "ESCE", "IENV", "IHSS", "ISCI", "ITEC", "LANG",
						"LGHT", "LITR", "MANE", "MATH", "MATP", "MGMT", "MTLE",
						"PHIL", "PHYS", "PSYC", "STSH", "STSS", "USAF", "USAR",
						"USNA", "WRIT"
					];

					foreach ($prefixes as $p) {
						// Add another item to the list, calling the function
						// 'determineIfActive' to determine if the active class
						// should be included in the item
						echo "<li role='presentation' " . determineIfActive($p)
								. "><a href='?prefix=" . $p . "'>" . $p . "</a></li>";
					}
				?>
            </ul>
        </div>
    </div>
    <!--/row -->
</div>
<!--/container -->

<?php require 'partials/footer.partial.php'; ?>




</body>
</html>
