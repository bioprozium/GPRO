<?php
class login
{
    static $user_id;
	static $user_name;
    function __construct()
    {
        require_once "conf/mysqlDB.php";
        $this->gets = $_GET;
		$this->exp_time=time()+(3600*24*30);
        $this->check_user();
    }
    function SetPage()
    {
        if(isset($this->gets["login"]))
        {
            if($this->gets["login"]=="lf")
                $this->getForm();
        }
        if($this->gets["action"]=="register")
        {
            $res=$this->register();
        }
        if($this->gets["action"]=="forgot")
        {
            $res=$this->send_recovery_mail();
        }
        if($this->gets["action"]=="reset")
        {
            $res=$this->reset_pass();
        }
        if($this->gets["action"]=="logout")
        {
            unset($_SESSION["user_id"]);
			unset($_SESSION["user_name"]);
			unset($_COOKIE["TID"]);
			setcookie("TID", "", time()-3600);
			header("Location: ".HTTP_PATH);
        }
        if($this->gets["action"]=="login")
        {
            $mail=addslashes(trim($_POST["email"]));
            $query="SELECT * FROM users WHERE mail='$mail'";
            $result = mysqlDB::$_connectionGDB->query($query);
            if($result->num_rows == 0)
            {
                $res="No user found with this mail and password combination.";
            }
            $row = $result->fetch_assoc();
            if(!password_verify($_POST["pass"], $row["pass"]))
            {
                $res="No user found with this mail and password combination.";
            }
            else
            {
                $user_id = $row["user_id"];
                $_SESSION["user_id"]=$user_id;
                $_SESSION["user_name"]=$row["user_name"];
                if($_POST["remember"]==1)
                {
                    $session = tools::random_password(15,0,1);
                    $session = md5($session);
                    $token = tools::generateToken();
                    $cr_token=hash("sha256", $token);
                    $query = "INSERT INTO user_tokens(user_id, session, token, expires) VALUES('$user_id', '$session', '$cr_token', $this->exp_time)";
                    mysqlDB::$_connectionGDB->query($query);
                    setcookie("TID", "$session,$token", $this->exp_time);
                }
                else
                {
                    unset($_COOKIE["TID"]);
					setcookie("TID", "", time()-3600);
                }
                $res="OK";
            }
            echo $res;
            exit;
        }

    }
    function check_user()
    {
		if(isset($_SESSION["user_id"]))
		    self::$user_id=$_SESSION["user_id"];
		else if(isset($_COOKIE["TID"]))
		{
			$pts=explode(",", addslashes($_COOKIE["TID"]));
			$user_hash=hash("sha256", $pts[1]);
			$query="SELECT * FROM user_tokens WHERE session='$pts[0]'";
			$result=mysqlDB::$_connectionGDB->query($query);
			if(!$result or $result->num_rows<1)
			{
				unset($_COOKIE["TID"]);
				setcookie("TID", "", time()-3600);
				return;
			}
            $r=$result->fetch_assoc();
			if(!hash_equals($user_hash, $r["token"]))
			{
				$token_id=$r["id"];
				$query="DELETE FROM user_tokens WHERE id=$token_id";
				mysqlDB::$_connectionGDB->query($query);
				setcookie("TID", "", time()-3600);
				return;
			}
			else
			{
				$token_id=$r["id"];
				$user_id=$r["user_id"];
				$token=$token=md5(tools::random_password());
				$query="UPDATE user_tokens SET token='$token' WHERE id=$token_id";
				mysqlDB::$_connectionGDB->query($query);    //!!!!!!!!!!!!!!!!!!!!!!!!!! HAS ADDED TO THE CURRENT ROW
				$sesid=$r["session"];
				setcookie("TID", "$sesid,$token", $this->exp_time);
				$_SESSION["user_id"]=$user_id;
				$query="SELECT * FROM users WHERE user_id=$user_id";
				$result=mysqlDB::$_connectionGDB->query($query);
                $r2 = $result->fetch_assoc();
				$_SESSION["user_name"]=$r2["user_name"];
				return;
			}
		}
		if(isset($_SESSION["user_name"]))
			self::$user_name=$_SESSION["user_name"];        
    }
    function register()
    {
        $out = "";
        extract($this->gets);
        $name = addslashes($name);
        $surname = addslashes($surname);
        $errors = array();
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            $errors[] = "Incorrect email address!";
        $email=trim(addslashes(strtolower($email)));
        if($email == "")
            $errors[] = "Email address is empty!";
        if($name == "")
            $errors[] = "Name is empty";
        $query = "SELECT * FROM users WHERE mail='$email'";
        $result = mysqlDB::$_connectionGDB->query($query);
        if(!empty($result->num_rows) && $result->num_rows > 0)
        {
            $errors[]="Mail exists!";
        }
        if($pass1 != $pass2)
        {
			$errors[]="Passwords do not match!";
        }
        if(strlen($pass1) < 5)
        {
            $errors[] = "Password length must be at least 7 symbols!";
        }
        if (!preg_match("#[0-9]+#", $pass1) or !preg_match("#[a-zA-Z]+#", $pass1))
        {
            $errors[] = "Password must contain alphabets and numbers.";
        }
        if(count($errors)>0)
        {
            for($i=0;$i<count($errors);$i++)
            {
                $out.="$errors[$i]<br>";
                if($i>2)
                    break;
            }
            return $out;
        }
        else
        {
            $pass1 = password_hash($pass1, PASSWORD_BCRYPT);
            $email = addslashes($email);
            $name = addslashes($name);
            $query = "INSERT INTO users(pass, mail, user_name, user_surname) VALUES('$pass1', '$email', '$name', '$surname')";
            $result = mysqlDB::$_connectionGDB->query($query);
            if($result)
            {
                $user_id = mysqlDB::$_connectionGDB->insert_id;
            }
            else
            {
                return "Could not create account, something went wrong... :-(";
            }
            $_SESSION["user_id"]=$user_id;
			$_SESSION["user_name"]=$name;
            echo $user_id;
        }
        return "OK";
    }
    function send_recovery_mail()
    {
        $mail = addslashes(trim($_POST["email"]));
		$query = "SELECT * FROM users WHERE mail='$mail'";
        $result = mysqlDB::$_connectionGDB->query($query);
        if($result->num_rows == 0)
        {
            echo "This email address was not found in our system.";
			exit;
        }
        $token = tools::generateToken();
		$cr_token=hash("sha256", $token);
		$query="UPDATE users SET forgot_pass='$cr_token' WHERE mail='$mail'";
        mysqlDB::$_connectionGDB->query($query);

    }
    function reset_pass()
    {

    }
    function getForm()
    {
        echo file_get_contents(NAVBLOCK."login/login_modal.html");
        exit;
    }
}
?>