<?php

// Necessary phpCAS Setup files for RPI's system
require 'resources/functions.php';
require 'resources/config.php';
require 'resources/rpiCAS.php';

forceAuth();

// Establish a connection to the database for this page.
$conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);

function echoPostURL() {
    if(isset($_GET["class"])) {
        echo "links.php?class=" . $_GET["class"];
    } else {
        echo "links.php";
    }
}

require 'partials/head.partial.php';
require 'partials/navigation.partial.php';

$pageHeader = "Add a new link";

require 'partials/pageheader.partial.php';
?>

<div class="container mtb">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-12 col-sm-offset-0">
            <div id="alertLocation"></div>
            <div class="well well-lg">
                <form method="post" action="<?php echoPostURL() ?>" class="form-horizontal" id="linkAction">
                    <div class="form-group">
                        <label for="linkName" class="col-sm-3 control-label">
                            Link Name
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="linkName"
                                   id="linkName" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="URL" class="col-sm-3 control-label">
                            Link URL
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="URL"
                                   id="URL" placeholder="" value="http://">
                        </div>
                    </div>
                    <?php
                        if(!isset($_GET["class"])) {
                            echo "<div class='form-group'>
                                     <label for='URL' class='col-sm-3 control-label'>
                                           Class
                                     </label>

                                    <div class='col-sm-9'>
                                        <select id='classForAdd' class='form-control' name='classForAdd'>
                                            <option value='' disabled selected>Select a class (type to search)</option>";

                            $var = $conn->prepare("SELECT * FROM `categories` ORDER BY `prefix`");
                            $var->execute();
                            $results = $var->fetchAll(PDO::FETCH_ASSOC);
                            $prevPrefix = "";
                            foreach($results as $r) {
                                if($prevPrefix != $r['prefix'] || $prevPrefix == "") {
                                    if($prevPrefix != "") {
                                        echo "</optgroup>";
                                    }
                                    echo "<optgroup label='" . $r['prefix'] . "'>";
                                    $prevPrefix = $r['prefix'];
                                }
                                echo "<option value='" . $r["category_id"] . "'>" . $r["title"] . "</option>";
                            }
                            echo "</optgroup>";

                            echo       "</select>
                                    </div>
                                 </div>";
                        } else {
                            echo "<input id='classForAdd' name='classForAdd' type='hidden' value='" . $_GET["class"] . "'>";
                        }
                    ?>
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8 col-xs-6">
                            <a href="classes.php" class="btn btn-default pull-right">
                                Cancel
                            </a>
                        </div>
                        <div class="col-sm-2 col-xs-6">
                            <button type="submit" class="btn btn-primary pull-right" name="user">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'partials/footer.partial.php'; ?>
<script src="assets/js/linkaction.js"></script>

</body>
</html>
