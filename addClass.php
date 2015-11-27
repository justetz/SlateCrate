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
                <form method="post" action="resources/executeClass.php" class="form-horizontal">
                    <div class="form-group">
                        <label for="className" class="col-sm-3 control-label">
                            Class Name
                        </label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="className"
                                   id="className" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputCategory" class="col-sm-3 control-label">
                            Category
                        </label>

                        <div class="col-sm-9">
                            <select id="inputCategory" class="form-control">
                                <option>ARCH</option>
                                <option>ARTS</option>
                                <option>ASTR</option>
                                <option>BCBP</option>
                                <option>BIOL</option>
                                <option>BMED</option>
                                <option>CHEM</option>
                                <option>CISH</option>
                                <option>CSCI</option>
                                <option>DSES</option>
                                <option>ECON</option>
                                <option>ECSE</option>
                                <option>ENGR</option>
                                <option>ENVE</option>
                                <option>ERTH</option>
                                <option>ESCE</option>
                                <option>IENV</option>
                                <option>IHSS</option>
                                <option>ISCI</option>
                                <option>ITEC</option>
                                <option>LANG</option>
                                <option>LGHT</option>
                                <option>LITR</option>
                                <option>MANE</option>
                                <option>MATH</option>
                                <option>MATP</option>
                                <option>MGMT</option>
                                <option>MTLE</option>
                                <option>PHIL</option>
                                <option>PHYS</option>
                                <option>PSYC</option>
                                <option>STSH</option>
                                <option>STSS</option>
                                <option>USAF</option>
                                <option>USAR</option>
                                <option>USNA</option>
                                <option>WRIT</option>
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