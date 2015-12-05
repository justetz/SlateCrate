<?php
require 'resources/functions.php';
require 'resources/config.php';
require 'resources/rpiCAS.php';

if (!phpCAS::isAuthenticated()) {
// If they're not currently logged in, take them to the RPI CAS page
    phpCAS::forceAuthentication();
}

$conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);

require 'partials/head.partial.php';
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
//edit if we need to
if(isset($_POST["edit"])){
    try{
        $conn->query("UPDATE `categories` SET `title` = '" . $_POST["className"] . "', `prefix` = '" . $_POST["inputCategory"] . "' WHERE `category_id` = " . $_POST["edit"]);
    }catch(PDOException $e){}
}

//add class if we need to
if(isset($_POST["user"])){
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


$search = "";
if(isset($_POST["srch"])){
    $search = $_POST["srch"];
}
$sort = "`title`";
if(isset($_POST["sort"])){
    $sort = $_POST["sort"];
}
?>

    <div class="row">
        <div class="col-md-4 col-sm-6">
            <form method="post">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-default" name="sort" value="`title`">Sort by name</button>
                    <button type="submit" class="btn btn-default" name="sort" value="`prefix`">Sort by prefix</button>
                    <button type="submit" class="btn btn-default" name="sort" value="`creation_date`">Sort by date</button>
                </div>
            </form>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="form-group form-group-sm">
                <form method="post">
                    <input name="srch" value="<?php echo $search; ?>" class="form-control" id="classSearch"
                           placeholder="Search Classes" />
                </form>
            </div>
        </div>
        <div class="col-md-4 col-sm-2">
            <div class="btn-group pull-right">
                <a class='btn btn-primary' href='addclass.php'>Add a class</a>
            </div>
        </div>
        <br>
    </div>

    <div class="row">
        <div class="col-md-9 col-sm-10">
            <?php
                try{
                    if(isset($_GET["prefix"])){
                        $p = "'" . $_GET["prefix"] . "'";
                        $var = $conn->prepare("SELECT * FROM `categories` WHERE `prefix` = $p AND `title` LIKE '%$search%' ORDER BY $sort");
                        $count = $conn->query("SELECT `title` FROM `categories` WHERE `prefix` = $p `links` AND `title` LIKE '%$search%' WHERE `prefix` = $p")->fetchColumn();
                    }else{
                        $var = $conn->prepare("SELECT * FROM `categories` WHERE `title` LIKE '%$search%' ORDER BY $sort");
                        $count = $conn->query("SELECT `title` FROM `categories` WHERE `title` LIKE '%$search%'")->fetchColumn();
                    }
                    if($count == NULL){ $count = 0; }
                    $var->execute();

                    //Check if current user is an admin
                    $admin = $conn->prepare("SELECT `isadmin` FROM `users` WHERE `rcs_id` = '" . phpCAS::getUser() . "'");
                    $admin->execute();
                    $isadmin = false;
                    while($result = $admin->fetch(PDO::FETCH_ASSOC)){
                        if($result["isadmin"] == 1){ $isadmin = true; }
                    }

                    $c = 0;
                    if(isset($_GET["page"])){
                        $p = $_GET["page"];
                    }else{ $p = 1; }

                    echo "<div class='row'>";
                    while($result = $var->fetch(PDO::FETCH_ASSOC)){
                        if($c >= ($p - 1) * 16 && $c < $p * 16){

                            //to get the number of links
                            $l = $conn->query("SELECT COUNT(link_id) FROM `links` WHERE `category_id` = '" . $result["category_id"] . "'")->fetchColumn();
                            if($l == NULL){ $l = 0; }

                            //print out the boxes
                            echo "<div class='col-md-6'>
								<a href='links.php?class=".$result["category_id"]."''>
									<div class='well well-sm well-hover'>
    								<h6 class='text-muted'>".$result["prefix"]."</h6>
    								<h4>".$result["title"]."</h4>
    								<p>Contains ".$l.($l > 1 ? " links" : " link").".</p>
    								<p class='text-muted small info-text'>
    									<span class='pull-left'>submitted by ".$result["rcs_id"]."</span>
    									<span class='pull-right'>".$result["creation_date"]."</span>";
                            echo "<span class='clearfix'></span></p></div></a>";

							if($isadmin || $result["rcs_id"] == phpCAS::getUser()) {
                                echo "<form method=\"post\" action='classes.php";
								if(isset($_GET["prefix"])){
									echo "?prefix=".$_GET["prefix"];
								}
								echo "' class=\"admin-panel delete-form\">";
                                echo "<button type=\"submit\" class=\"btn btn-default pull-right\" name=\"delete\" value=" . $result["category_id"] . ">Delete</button></form>";
                                echo "<form method=\"post\" action='editClass.php' class=\"admin-panel\">";
                                echo "<button type=\"submit\" class=\"btn btn-default\" name=\"edit\" value=" . $result["category_id"] . ">Edit</button></form>";
                            }
							echo "</div>";
                        }else if($c >= $p * 16){ break; }
                        ++$c;
                    }

                    if($c == 0){
                        echo "<div class='col-xs-12'>
							 <div class='alert alert-info' role='alert'>
								No classes. You should add one!
							 </div></div>";
                    }
                }catch(PDOException $e){ echo $e; }

                if(($count / 16) + 1 >= 2) {
                    echo "<div class=\"col-xs-12 centered\"><hr/><div class=\"btn-group\">";
                    for ($button = 1; $button < ($count / 16) + 1; $button++) {
                        $link = "?";
                        if (isset($_GET["prefix"])) {
                            $link .= "prefix=" . $_GET["prefix"] . "&";
                        }
                        $link .= "page=$button";
                        echo "<a href=\"$link\" class=\"btn btn-primary\">$button</a>";
                    }
                    echo "</div></div>";
                }
            ?>
            </div>
        </div>
        <div class="col-md-3 col-sm-2">
			<ul class="nav nav-pills nav-stacked">

				<?php
					if(!isset($_GET["prefix"])) {
						echo "<li role='presentation' class='active'><a href='classes.php'>All Prefixes</a></li>";
					} else {
						echo "<li role='presentation'><a href='classes.php'>All Prefixes</a></li>";
					}

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
<script type="text/javascript">
    $('#classSearch').tooltip({'trigger':'focus', 'title': 'Hit ENTER to search'});

    $(".delete-form").submit(function() {
        return confirm('Are you sure you want to delete this item?');
    });
</script>

</body>
</html>
