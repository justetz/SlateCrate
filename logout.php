<?PHP

include_once("resources/CAS.php");
phpCAS::client(CAS_VERSION_2_0,'cas-auth.rpi.edu',443,'/cas/');
// SSL!
phpCAS::setCasServerCACert("./CACert.pem");//this is relative to the cas client.php file

if (phpCAS::isAuthenticated())
{
    phpCAS::logout() ;
}else{
    header('location: ./index.php');
}
?>