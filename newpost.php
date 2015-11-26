<?php

// Necessary phpCAS Setup files for RPI's system
require 'resources/rpiCAS.php';

if (!phpCAS::isAuthenticated()) {
// If they're not currently logged in, take them to the RPI CAS page
    phpCAS::forceAuthentication();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php require 'partials/head.partial.php'; ?>
<body>
<?php
require 'partials/navigation.partial.php';

$pageHeader = "Add a new link";

require 'partials/pageheader.partial.php';
?>

<div class="container mtb">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-12 col-sm-offset-0">
            <div class="well well-lg">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="inputURL" class="col-sm-3 control-label">
                            Link URL
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="url"
                                   id="inputURL" placeholder="http://">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputTitle" class="col-sm-3 control-label">
                            Title
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="title"
                                   id="inputTitle" placeholder="Title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputDesc" class="col-sm-3 control-label">
                            Description
                        </label>

                        <div class="col-sm-9">
                            <textarea class="form-control" name="desc"
                                   id="inputDesc" placeholder="Description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputCategory" class="col-sm-3 control-label">
                            Category
                        </label>

                        <div class="col-sm-9">
                            <select id="inputCategory" class="form-control">
                                <option>Computer Science</option>
                                <option>Information Technology & Web
                                        Science</option>
                                <option>Electrical Engineering</option>
                                <option>Games Simulation Arts &
                                        Sciences</option>
                                <option>Computer and Systems
                                        Engineering</option>
                                <option>Economics</option>
                            </select>
                            <p class="help-block">Note: Spam links will be deleted.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary pull-right">
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