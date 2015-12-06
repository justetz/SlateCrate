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
                    <button type="submit" class="btn btn-default<?php echo ($sort == "`title`" ? " active" : ""); ?>" name="sort" value="`title`">Sort by name</button>
                    <button type="submit" class="btn btn-default<?php echo ($sort == "`prefix`" ? " active" : ""); ?>" name="sort" value="`prefix`">Sort by prefix</button>
                    <button type="submit" class="btn btn-default<?php echo ($sort == "`creation_date` DESC" ? " active" : ""); ?>" name="sort" value="`creation_date` DESC">Sort by date</button>
                </div>
            </form>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="form-group form-group-sm">
                <form method="post">
                    <input name="srch" value="<?php echo $search; ?>" class="form-control" id="search"
                           placeholder="Search Classes" />
                </form>
            </div>
        </div>
        <div class="col-md-4 col-sm-2">
            <div class="btn-group pull-right">
                <a class='btn btn-primary' href='addclass.php'>
                    <span class="fa fa-plus"></span>
                    Add a class
                </a>
            </div>
        </div>
        <br>
    </div>  

    <div class="row">
        <div class="col-md-9 col-sm-10">
            <?php
                populateData($conn, isset($_GET["prefix"]) ? $_GET["prefix"] : "", $search, $sort,
                               isset($_GET["page"]) ? $_GET["page"] : "", $isAdmin);
            ?>
            </div>
        </div>
        <div class="col-md-3 col-sm-2" id="scroll">
			<ul class="nav nav-pills nav-stacked">
				<?php populateSidebar(isset($_GET["prefix"]) ? $_GET["prefix"] : ""); ?>
            </ul>
        </div>
    </div>
</div>

<?php require 'partials/footer.partial.php'; ?>
<script src="assets/js/listings.js"></script>


</body>
</html>
