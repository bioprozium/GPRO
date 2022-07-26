<?php
require_once MODULES."login.php";
require_once TOOLS."parser.php";
class gpro
{
    
    static $HTML=array(
        "METAS"=>"",
        "LINKS"=>"",
        "NO_INDEX_LINKS"=>"",
        "TITLE"=>"",
        "NAVIGATION_BLOCK"=>"",
        "MAIN_BLOCK"=>"",
        "FOOTER_BLOCK"=>"",
        "BODY_SCRIPT_BLOCK"=>""
    );
    public $loginBut;
    function __construct()
    {
        require_once CLASSES."taxonomyQ.php";
        $this->gets=$_GET;
        $this->loginBut="";
        $this->login_class = new login;
        $this->modules=array(
            "upldyrfls"=>"uploadYourFiles",
            "txstrdb"=>"taxStructuredDB",
            "geo"=>"GEO",
            "prj"=>"project",
            "login"=>"login",
            "anl"=>"analyzer",
            "gnm"=>"genome",
            "ufp"=>"userfilepanel",
            "anl"=>"analyzer",
            "check"=>"checkStatus"
        );
        $this->module=$this->get_module();
        $this->set_login_block();
        $this->set_nav_block();
        $this->SetPage();
        
    }
    function SetPage()
    {
		if($this->module=="")
		{
			if(isset($this->gets["login"]))
				$this->module="login";
			if($this->module=="")
				$this->module="index_page";
		}
		include_once MODULES."$this->module.php";
		$class=new $this->module;
		$class->SetPage();
    }
    function get_module()
	{
		if(!isset($this->gets["m"]))
			return;
		else if(!isset($this->modules["{$this->gets["m"]}"]))
			return;
		else
			return $this->modules["{$this->gets["m"]}"];
	}
    function set_nav_block()
    {
        self::$HTML["NAVIGATION_BLOCK"]=parse_by_blocks("LOGIN_BLOCK",parse_by_constants(file_get_contents(TEMPLATES."navblock/nav_index.html")),$this->loginBut);
        if($this->module!="")
        {
            $m=$this->gets["m"];
            $block="ACT_$m";
            self::$HTML["NAVIGATION_BLOCK"]=parse_by_blocks($block,self::$HTML["NAVIGATION_BLOCK"],"active");
            self::$HTML["NAVIGATION_BLOCK"]=remove_empty_blocks(self::$HTML["NAVIGATION_BLOCK"]);
        }
        else
        {
            $block="ACT_HOME";
            self::$HTML["NAVIGATION_BLOCK"]=parse_by_blocks($block,self::$HTML["NAVIGATION_BLOCK"],"active");
            self::$HTML["NAVIGATION_BLOCK"]=remove_empty_blocks(self::$HTML["NAVIGATION_BLOCK"]);
        }
    }
    function set_login_block()
    {
        if(isset($_SESSION["user_name"]))
        {
            $this->loginBut = tools::parse_by_blocks("USER_NAME",tools::parse_by_constants(file_get_contents(NAVBLOCK."login/logout_block.html")),$_SESSION["user_name"]);
        }
        else
        {
            $this->loginBut=parse_by_constants(file_get_contents(TEMPLATES."navblock/login/login_index.html"));
        }
    }
}
?>