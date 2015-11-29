<?php
include_once("CAS.php");
phpCAS::client(CAS_VERSION_2_0,'cas-auth.rpi.edu',443,'/cas/');
//phpCAS::setCasServerCACert("CACert.pem");
phpCAS::setNoCasServerValidation();
?>