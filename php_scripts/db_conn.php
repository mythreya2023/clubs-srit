<?php
include 'comn_funcs.php';
$conn=mysqli_connect("localhost","root","","tech talks");
class dbconnect extends comn_funcs{
    private $host; private $dbuname;
    private $dbpwd; private $dbname;
    protected function connect (){
       $oopconn=new mysqli( $this->host="localhost",
        $this->dbuname="root",$this->dbpwd="",
        $this->dbname="tech talks");
        if(isset($_COOKIE['_uid_'])){$_SESSION['ses_id']=htmlentities($_COOKIE['_udi_']);}
        return $oopconn;
    }
}

?>