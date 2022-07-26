<?php
class mysqlDB
{
    private $_hostname;
    private $_username;
    private $_password;
    private $_database;
    public static $_connectionGDB;
    public static function connection_genomeDB()
    {
        $_hostname = "localhost";
        $_username = "root";
        $_password = "";
        $_database = "genomeprodb";
        self::$_connectionGDB = new mysqli($_hostname, $_username, $_password, $_database);
        if(self::$_connectionGDB->connect_error)
            die("MySQL_UserDB Fatal Error: ".self::$_connectionGDB->connect_error);
    }
}
?>