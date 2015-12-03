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
                <form method="post" action="resources/executeadd.php" class="form-horizontal">
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
                                <option value='ARCH'>ARCH</option>
                                <option value='ARTS'>ARTS</option>
                                <option value='ASTR'>ASTR</option>
                                <option value='BCBP'>BCBP</option>
                                <option value='BIOL'>BIOL</option>
                                <option value='BMED'>BMED</option>
                                <option value='CHEM'>CHEM</option>
                                <option value='CISH'>CISH</option>
                                <option value='CSCI'>CSCI</option>
                                <option value='DSES'>DSES</option>
                                <option value='ECON'>ECON</option>
                                <option value='ECSE'>ECSE</option>
                                <option value='ENGR'>ENGR</option>
                                <option value='ENVE'>ENVE</option>
                                <option value='ERTH'>ERTH</option>
                                <option value='ESCE'>ESCE</option>
                                <option value='IENV'>IENV</option>
                                <option value='IHSS'>IHSS</option>
                                <option value='ISCI'>ISCI</option>
                                <option value='ITEC'>ITEC</option>
                                <option value='LANG'>LANG</option>
                                <option value='LGHT'>LGHT</option>
                                <option value='LITR'>LITR</option>
                                <option value='MANE'>MANE</option>
                                <option value='MATH'>MATH</option>
                                <option value='MATP'>MATP</option>
                                <option value='MGMT'>MGMT</option>
                                <option value='MTLE'>MTLE</option>
                                <option value='PHIL'>PHIL</option>
                                <option value='PHYS'>PHYS</option>
                                <option value='PSYC'>PSYC</option>
                                <option value='STSH'>STSH</option>
                                <option value='STSS'>STSS</option>
                                <option value='USAF'>USAF</option>
                                <option value='USAR'>USAR</option>
                                <option value='USNA'>USNA</option>
                                <option value='WRIT'>WRIT</option>
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
