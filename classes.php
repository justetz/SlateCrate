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
if(isset($_POST["className"])){
    try{
        $string = "'" . $_POST["className"] . "', '" . $_POST["inputCategory"] . "', '" . $_POST["user"] . "', " . "CURDATE()";

        $conn->query("INSERT INTO `categories` (`title`, `prefix`, `rcs_id`, `creation_date`)
            VALUES (" . $string . ");");

        echo "<p>Class added!</p>";
    }catch(PDOException $e){
        echo $e;
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


<!-- *****************************************************************************************************************
 AGENCY ABOUT
 ***************************************************************************************************************** -->

<div class="container mtb">
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
                            echo "<a href='links.php?class=";
                            echo $result["category_id"];
                            echo "''><div class='col-md-4'><div class='well well-sm well-hover'><h6 class='text-muted'>";
                            echo $result["prefix"];
                            echo "</h6><h4>";
                            echo $result["title"];
                            echo "</h4><p>Contains ";
                            echo $result["links"];
                            echo " links.</p><p class='text-muted small'><span class='pull-left'>submitted by ";
                            echo $result["rcs_id"];
                            echo "</span><span class='pull-right'>";
                            echo $result["creation_date"];
                            echo "</span>";
                            if($isadmin){
                                echo "<form method=\"post\" action='classes.php' class=\"form-horizontal\">";
                                echo "<button type=\"submit\" class=\"btn btn-primary pull-right\" name=\"delete\" value=" . $result["category_id"] . ">Delete</button></form>";
                            }
                            echo "<span class='clearfix'></span></p></div></div></a>";
                        }
                        $count++;
                    }

                    if($count == 0){
                        echo "No classes.";
                    }else{
                        echo "Found $count classes";
                    }
                    echo "<a href='addClass.php'> You should add one.</a>";
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
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="?">All Classes</a></li>
                <li role="presentation" class="active"><a href="?prefix=ARCH">ARCH</a></li>
                <li role="presentation" class="active"><a href="?prefix=ARTS">ARTS</a></li>
                <li role="presentation" class="active"><a href="?prefix=ASTR">ASTR</a></li>
                <li role="presentation" class="active"><a href="?prefix=BCBP">BCBP</a></li>
                <li role="presentation" class="active"><a href="?prefix=BIOL">BIOL</a></li>
                <li role="presentation" class="active"><a href="?prefix=BMED">BMED</a></li>
                <li role="presentation" class="active"><a href="?prefix=CHEM">CHEM</a></li>
                <li role="presentation" class="active"><a href="?prefix=CISH">CISH</a></li>
                <li role="presentation" class="active"><a href="?prefix=CSCI">CSCI</a></li>
                <li role="presentation" class="active"><a href="?prefix=DSES">DSES</a></li>
                <li role="presentation" class="active"><a href="?prefix=ECON">ECON</a></li>
                <li role="presentation" class="active"><a href="?prefix=ECSE">ECSE</a></li>
                <li role="presentation" class="active"><a href="?prefix=ENGR">ENGR</a></li>
                <li role="presentation" class="active"><a href="?prefix=ENVE">ENVE</a></li>
                <li role="presentation" class="active"><a href="?prefix=ERTH">ERTH</a></li>
                <li role="presentation" class="active"><a href="?prefix=ESCE">ESCE</a></li>
                <li role="presentation" class="active"><a href="?prefix=IENV">IENV</a></li>
                <li role="presentation" class="active"><a href="?prefix=IHSS">IHSS</a></li>
                <li role="presentation" class="active"><a href="?prefix=ISCI">ISCI</a></li>
                <li role="presentation" class="active"><a href="?prefix=ITEC">ITEC</a></li>
                <li role="presentation" class="active"><a href="?prefix=ITWS">ITWS</a></li>
                <li role="presentation" class="active"><a href="?prefix=LANG">LANG</a></li>
                <li role="presentation" class="active"><a href="?prefix=LGHT">LGHT</a></li>
                <li role="presentation" class="active"><a href="?prefix=LITR">LITR</a></li>
                <li role="presentation" class="active"><a href="?prefix=MANE">MANE</a></li>
                <li role="presentation" class="active"><a href="?prefix=MATH">MATH</a></li>
                <li role="presentation" class="active"><a href="?prefix=MATP">MATP</a></li>
                <li role="presentation" class="active"><a href="?prefix=MGMT">MGMT</a></li>
                <li role="presentation" class="active"><a href="?prefix=MTLE">MTLE</a></li>
                <li role="presentation" class="active"><a href="?prefix=PHIL">PHIL</a></li>
                <li role="presentation" class="active"><a href="?prefix=PHYS">PHYS</a></li>
                <li role="presentation" class="active"><a href="?prefix=PSYCH">PSYC</a></li>
                <li role="presentation" class="active"><a href="?prefix=STSH">STSH</a></li>
                <li role="presentation" class="active"><a href="?prefix=STSS">STSS</a></li>
                <li role="presentation" class="active"><a href="?prefix=USAF">USAF</a></li>
                <li role="presentation" class="active"><a href="?prefix=USAR">USAR</a></li>
                <li role="presentation" class="active"><a href="?prefix=USNA">USNA</a></li>
                <li role="presentation" class="active"><a href="?prefix=WRIT">WRIT</a></li>
            </ul>
        </div>
    </div>
    <!--/row -->
</div>
<!--/container -->

<?php require 'partials/footer.partial.php'; ?>




</body>
</html>
