<?php
require 'resources/rpiCAS.php';
require 'resources/functions.php';
require 'resources/config.php';

forceAuth();

if(!isset($_POST["edit"])) {
    header('location: ./addclass.php');
}

$conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);
$edit = $conn->prepare("SELECT * FROM `categories` WHERE `category_id` = " . $_POST["edit"]);
$edit->execute();
$edit = $edit->fetch(PDO::FETCH_ASSOC);

$pageHeader = "Editing <i>" . $edit["title"] . "</i>";

require 'partials/head.partial.php';
require 'partials/navigation.partial.php';
require 'partials/pageheader.partial.php';
?>

<div class="container mtb">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-12 col-sm-offset-0">
            <div id="alertLocation"></div>
            <div class="well well-lg">
                <form method="post" action="classes.php" class="form-horizontal" id="classAction">
                    <div class="form-group">
                        <label for="className" class="col-sm-3 control-label">
                            Class Name
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="className"
                                   id="className" value=<?php echo "'" . $edit["title"] . "'"; ?> >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputCategory" class="col-sm-3 control-label">
                            Category
                        </label>

                        <div class="col-sm-9">
                            <select id="inputCategory" class="form-control" name="inputCategory">
                                <option value="" disabled selected>Select a prefix (type to search)</option>
                                <?php
                                    require_once 'resources/prefixes.php';
                                    populatePrefixSelect($prefixes, $edit["prefix"]);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-8 col-xs-6">
                            <a href="classes.php" class="btn btn-default pull-right">
                                Cancel
                            </a>
                        </div>
                        <div class="col-sm-2 col-xs-6">
                            <button type="submit" class="btn btn-primary pull-right" name="edit" value="<?php echo $_POST["edit"]; ?>">
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
<script src="assets/js/classaction.js"></script>

</body>
</html>
