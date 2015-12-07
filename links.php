<?php
// Configurations and includes for PHP
require 'resources/functions.php';
require 'resources/config.php';
require 'resources/rpiCAS.php';

require 'resources/linkFunctions.php';

require 'partials/head.partial.php';
require 'partials/navigation.partial.php';
require 'partials/pageheader.partial.php';
?>
<div class="container mtb">
	<?php populateAlertRow($alertType, $alertMessage); ?>
    <div class="row">
		<div class="col-md-2 col-sm-6">
			<a href="classes.php" class="btn btn-primary">
				<?php populateBackButtonText(isset($_GET["class"]) ? $_GET["class"] : ""); ?>
			</a>
		</div>
		<div class="col-md-5 col-sm-6">
			<div class="form-group form-group-sm">
                <form method="post">
				    <input name="srch" value="" class="form-control" placeholder="Search Links" id="search" />
                </form>
			</div>
		</div>

        <!--sort by-->
        <div class="col-md-4 col-sm-6">
            <form method="post">
                <div class="btn-group" role="group">
					<button type="submit" class="<?php echoClassForFilterButtons($sort, "`score` DESC"); ?>" name="sort"
							value="`score` DESC">
						Sort by votes
					</button>
					<button type="submit" class="<?php echoClassForFilterButtons($sort, "`title` ASC"); ?>" name="sort"
							value="`title` ASC">
						Sort by name
					</button>
                    <button type="submit" class="<?php echoClassForFilterButtons($sort, "`creation_date` DESC"); ?>"
							name="sort" value="`creation_date` DESC">
						Sort by date
					</button>
                </div>
            </form>
        </div>

		<div class="col-md-1 col-sm-6">
			<?php populateAddButton(isset($_GET["class"]) ? $_GET["class"] : ""); ?>
		</div>
	</div>
	<br/>
	<div class="row">
        <div class="col-md-12">
			<?php populateData($conn, $query, $isAdmin); ?>
        </div>
    </div>
    <!--/row -->
</div>
<!--/container -->

<?php require 'partials/footer.partial.php'; ?>
<script src="assets/js/listings.js"></script>

</body>
</html>
