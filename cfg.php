<?php
// phpinfo();
// error_reporting(0);
if(isset($_GET["session_id"]))
	session_id($_GET["session_id"]);
session_start();
DEFINE("SESSION_ID", session_id());
DEFINE ("HTML_PATH", $_SERVER["DOCUMENT_ROOT"]."/");
DEFINE ("ROOT_PATH", str_replace("\\","/",__DIR__)."/");
DEFINE ("CLASSES", ROOT_PATH."classes/");
DEFINE ("FASTA_CLASSES", CLASSES."fasta/");
DEFINE ("TEMPLATES", ROOT_PATH."templates/");
DEFINE ("HEADBLOCK",TEMPLATES."headblock/");
DEFINE ("NAVBLOCK",TEMPLATES."navblock/");
DEFINE ("MAINBLOCK",TEMPLATES."mainblock/");
DEFINE ("FOOTERBLOCK",TEMPLATES."footerblock/");
DEFINE ("MODULES", ROOT_PATH."modules/");
DEFINE ("TOOLS", ROOT_PATH."tools/");
DEFINE ("DATA", ROOT_PATH."data/");
DEFINE ("HTTP_PATH", $_SERVER['REQUEST_SCHEME']."://".$_SERVER["HTTP_HOST"]."/GPRO/");	//!!! Server - - - must be changed!
DEFINE ("HTTP_PATH_SCRIPT",HTTP_PATH.$_SERVER["SCRIPT_NAME"]);
DEFINE ("HTTP_IMAGES_PATH", HTTP_PATH."images/");
DEFINE ("HTTP_SCRIPTS_PATH", HTTP_PATH."scripts/");
DEFINE ("HTTP_URL", HTTP_PATH."?$_SERVER[QUERY_STRING]");
DEFINE ("PHP_SELF", htmlspecialchars($_SERVER["PHP_SELF"]));
if(strstr(ROOT_PATH, ":"))
{
 DEFINE("PROGRAM_PATH", ROOT_PATH."json_parser/json_parser/Release/json_parser.exe");
 DEFINE("BLAST_PATH", ROOT_PATH."blast/blast_win/bin/blastn.exe");
 DEFINE("BLAST_DB_PATH", ROOT_PATH."blast/blast_win/bin/makeblastdb.exe");
}
else
{
	DEFINE("PROGRAM_PATH", ROOT_PATH."json_parser/json_parser");
	DEFINE("BLAST_PATH", "blastn");
	DEFINE("BLAST_DB_PATH", "makeblastdb");
}
DEFINE ("USER_FILES_PATH", ROOT_PATH."files/upload/");
DEFINE ("PARSED_FILES_PATH", ROOT_PATH."files/parsed/");
DEFINE ("PROJECT_FILES_PATH", ROOT_PATH."files/projects/");
DEFINE ("FASTA_FILES_PATH", ROOT_PATH."files/fasta/");
DEFINE ("TMP_FILES_PATH", ROOT_PATH."files/tmp/");
DEFINE("GO_PTR_FILE", ROOT_PATH."go.ptr");
//DEFINE ("ADMIN_PATH", ."classes/");
require_once "conf/mysqlDB.php";//подключение к базе
mysqlDB::connection_genomeDB();
require_once TOOLS."tools.php";
//require_once CLASSES."xcount/xcount.php";//ведет логи посещений
?>