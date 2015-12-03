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
                <form method="post" action="classes.php" class="form-horizontal">
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
                            <select id="inputCategory" class="form-control" name="inputCategory">
								<?php
									/**
									 * This array contains all valid prefixes at RPI. These
									 * values will be used to populate the sidebar of the page.
									 * @var array
									 */
									$prefixes = [
										"ARCH", "ARTS", "ASTR", "BCBP", "BIOL", "BMED", "CHEM",
										"CISH", "CSCI", "DSES", "ECON", "ECSE", "ENGR", "ENVE",
										"ERTH", "ESCE", "IENV", "IHSS", "ISCI", "ITEC", "LANG",
										"LGHT", "LITR", "MANE", "MATH", "MATP", "MGMT", "MTLE",
										"PHIL", "PHYS", "PSYC", "STSH", "STSS", "USAF", "USAR",
										"USNA", "WRIT"
									];

									foreach ($prefixes as $p) {
										// Add another item to the list, calling the function
										// 'determineIfActive' to determine if the active class
										// should be included in the item
										echo "<option value='" . $p . "'>" . $p . "</option>";
									}
								?>
                            </select>
                            <p class="help-block">Note: Spam links will be deleted.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary pull-right" name="user" value=<?php echo "'" . phpCAS::getUser() . "'"; ?> >
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
