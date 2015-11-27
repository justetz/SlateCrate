<?php
require 'resources/functions.php';
require '../config.php';
require 'resources/rpiCAS.php';

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
?>


<!-- *****************************************************************************************************************
 AGENCY ABOUT
 ***************************************************************************************************************** -->

<div class="container mtb">
    <div class="row">
        <div class="col-md-9">
            <?php
                try{
                    $var = $conn->prepare("SELECT * FROM `categories` ORDER BY `title`");
                    $var->execute();

                    $count = 0;

                    echo "<div class='row'>";
                    while($result = $var->fetch(PDO::FETCH_ASSOC)){
                        echo "<div class='col-md-4'><div class='well well-sm well-hover'><h6 class='text-muted'>Category</h6><h4><a href=''>";
                        echo $result["title"];
                        echo "</a></h4><p>Contains ";
                        echo $result["links"];
                        echo " links.</p><p class='text-muted small'><span class='pull-left'>submitted by ";
                        echo $result["user_id"];
                        echo "</span><span class='pull-right'>";
                        echo $result["creation_date"];
                        echo "</span><span class='clearfix'></span></p></div></div>";
                        $count++;
                    }

                    if($count == 0){
                        echo "No classes. You should add one.";
                    }else{
                        echo "Found $count classes";
                    }
                    echo "<a href='addClass.php'>Add a class</a>";
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
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="">All Links</a></li>
                <li role="presentation"><a href="">Computer Science</a></li>
                <li role="presentation"><a href="">Information Technology & Web
                        Science</a></li>
                <li role="presentation"><a href="">Electrical Engineering</a>
                </li>
                <li role="presentation"><a href="">Games Simulation Arts &
                        Sciences</a></li>
                <li role="presentation"><a href="">Computer and Systems
                        Engineering</a></li>
                <li role="presentation"><a href="">Economics</a></li>
                <li role="presentation"><a href="">Test</a></li>
                <li role="presentation"><a href="">Test</a></li>
                <li role="presentation"><a href="">Test</a></li>
            </ul>
        </div>
    </div>
    <!--/row -->
</div>
<!--/container -->

<?php require 'partials/footer.partial.php'; ?>




</body>
</html>
