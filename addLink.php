<?php

// Necessary phpCAS Setup files for RPI's system
require 'resources/rpiCAS.php';

if (!phpCAS::isAuthenticated()) {
// If they're not currently logged in, take them to the RPI CAS page
    phpCAS::forceAuthentication();
}

require 'partials/head.partial.php';
require 'partials/navigation.partial.php';

$pageHeader = "Add a new link";

require 'partials/pageheader.partial.php';
?>

<div class="container mtb">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-12 col-sm-offset-0">
            <div class="well well-lg">
                <form method="post" action=<?php echo "'links.php?class=" . $_GET["class"] . "'";  ?> class="form-horizontal">
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
                                   id="URL" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary pull-right" name="user" value=<?php echo "'\"" . phpCAS::getUser() . "\", " . $_GET["class"] . "'"; ?> >
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
</body>
</html>
