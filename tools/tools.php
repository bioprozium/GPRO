<?php
class tools
{
    public static $type = array(
        0=>"CDNA",

    );
    public static function generateToken($length = 20)
	{
		return random_bytes($length);
	}
    public static function random_password($length = 8, $allow_uppercase = true, $allow_numbers = true)
	{
		$out = '';
		$arr = array();
		for($i=97; $i<123; $i++) $arr[] = chr($i);
		if ($allow_uppercase) for($i=65; $i<91; $i++) $arr[] = chr($i);
		if ($allow_numbers) for($i=0; $i<10; $i++) $arr[] = $i;
		shuffle($arr);
		for($i=0; $i<$length; $i++)
		{
			$out .= $arr[mt_rand(0, sizeof($arr)-1)];
		}
		return $out;
	}
    public static function parse_by_constants($text)
    {
        while(preg_match("/\{\|(.+?)\|\}/", $text, $res))
        {
            $block=str_replace("{|", "", $res[0]);
            $block=str_replace("|}", "", $block);
            $result=constant($block);
            $text=preg_replace("/\{\|(.+?)\|\}/", "$result", $text, 1);
        }
        return $text;
    }
    public static function parse_by_blocks($block,$text,$rep)
    {
        $block="<{".$block."}>";
        $text=str_replace($block,$rep,$text);
        return $text;
    }
    public static function remove_empty_blocks($text)
    {
        return preg_replace('/\<\{(.*?)\}\>/', '', $text);
    }
    public static function generateRandomString($length = 15) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function checkIfUserLoggedIn()
    {
        if(isset($_SESSION["user_id"]) && isset($_SESSION["user_name"]))
        {
            $user_id = $_SESSION["user_id"];
            $user_name = $_SESSION["user_name"];
            $query = "SELECT user_id,user_name FROM users WHERE user_id = $user_id AND user_name='$user_name'";
            $result = mysqlDB::$_connectionGDB->query($query);
            if($result->num_rows < 1)
            {
                header('Location: '. HTTP_PATH . 'index.php');
            }
        }
        else
        {
            header('Location: '. HTTP_PATH . 'index.php');
        }
    }
}
?>