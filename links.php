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

$pageHeader = "Links";

require 'partials/pageheader.partial.php';

//add class if we need to
if(isset($_POST["linkName"])){
    try{
        $string = "'" . $_POST["URL"] . "', " . $_POST["user"] . ", " . "CURDATE(), '" . $_POST["linkName"] . "'";

        $conn->query("INSERT INTO `links` (`link`, `rcs_id`, `category_id`, `creation_date`, `title`)
            VALUES (" . $string . ");");

        echo "<p>Link added!</p>";
    }catch(PDOException $e){
        echo $e;
    }
}
?>


<!-- *****************************************************************************************************************
 AGENCY ABOUT
 ***************************************************************************************************************** -->

<div class="container mtb">
    <div class="row">
        <div class="col-md-9">
            <?php
                try{
                    if(isset($_GET["class"])){
                        $c = $_GET["class"];
                        $var = $conn->prepare("SELECT * FROM `links` WHERE `category_id` = $c");
                        $var->execute();

                        $count = 0;

                        echo "<div class='row'>";
                        while($result = $var->fetch(PDO::FETCH_ASSOC)){
                            echo "<a href='";
                            echo $result["link"];
                            echo "' target=\"_blank\"><div class='col-md-4'><div class='well well-sm well-hover'><h6 class='text-muted'>Link</h6><h4>";
                            echo $result["title"];
                            echo "</h4><p class='text-muted small'><span class='pull-left'>submitted by ";
                            echo $result["rcs_id"];
                            echo "</span><span class='pull-right'>";
                            echo $result["creation_date"];
                            echo "</span><span class='clearfix'></span></p></div></div></a>";
                            $count++;
                        }

                        if($count == 0){
                            echo "No links. You should add one.";
                        }else{
                            echo "Found $count links";
                        }
                        echo "<a href='addLink.php?class=$c'>Add a link</a>";
                    }
                    else{
                        echo "Error, no class selected. Select a class at <a href='posts.php'>Posts</a>.";
                    }
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
