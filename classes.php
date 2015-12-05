<?php
require 'resources/config.php';
require 'resources/functions.php';
require 'resources/rpiCAS.php';

// Execute backend actions that relate to classes
require 'resources/classfunctions.php';

require 'partials/head.partial.php';
require 'partials/navigation.partial.php';
require 'partials/pageheader.partial.php';
?>

<div class="container mtb">
    <?php populateAlertRow($alertType, $alertMessage); ?>
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

							if($isAdmin || $result["rcs_id"] == phpCAS::getUser()) {
                                echo "<form method=\"post\" action='classes.php";
								if(isset($_GET["prefix"])){
									echo "?prefix=".$_GET["prefix"];
								}
								echo "' class=\"admin-panel delete-form\">";
                                echo "<button type=\"submit\" class=\"btn btn-default pull-right\" name=\"delete\" value=" . $result["category_id"] . ">Delete</button></form>";
                                echo "<form method=\"post\" action='editclass.php' class=\"admin-panel\">";
                                echo "<button type=\"submit\" class=\"btn btn-default\" name=\"edit\" value=" . $result["category_id"] . ">Edit</button></form>";
                            }
							echo "</div>";
                        }else if($c >= $p * 16){ break; }
                        ++$c;
                    }

                    if($c == 0){
                        echo "<div class='col-xs-12'>" . infoAlert("No classes. You should add one!") . "</div>";
                    }
                }catch(PDOException $e){ echo $e; }

                populatePagination($count, isset($_GET["prefix"]) ? $_GET["prefix"] : "");
            ?>
            </div>
        </div>
        <div class="col-md-3 col-sm-2">
			<ul class="nav nav-pills nav-stacked">
				<?php populateSidebar(isset($_GET["prefix"]) ? $_GET["prefix"] : ""); ?>
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
