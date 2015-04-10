<?php
   header('Pragma: private');
   header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
   #header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
   header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
   header( "Pragma: no-cache" );
   if (!defined('APP_DIR')) { define('APP_DIR', dirname(__FILE__));   }
   require_once("session.php");
   include("Auth.class.php");
   if(isset($_SESSION['USERINFO'])){
       header("Location: /index.php");
   } elseif (isset($_POST['username'])){
       $auth = new Auth();
       $loginDetails = $auth->doLogin($_POST['username'], (isset($_POST['password']) ? md5($_POST['password']) : ""));
       if (is_array($loginDetails) && isset($loginDetails['userId'])){
           $data = base64_encode(serialize($loginDetails));
           $redirect = "http://" . $_SERVER{'SERVER_NAME'}  . "/index.php";
           $_SESSION['USERINFO'] = $data;
           header("Location: $redirect");
       }
   }
       
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8">
	<TITLE>GENSMART ::Login::</TITLE>
	<script language="javascript" src="js/jquery-1.7.1.js"></script>
	<script language="javascript" src="js/jquery.ui.timepicker.js"></script>
	<script language="javascript" src="js/jquery.ui.datepicker.js"></script>
	<!--<TITLE><?php echo $_SERVER{'SERVER_NAME'}; ?></TITLE>-->
</HEAD>
 
<body id="Login">
<br><br><br><br><br>
	<table border="0" cellpadding="0" cellspacing="0" id="Wrapper">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" id="headerWrapper">
		<tr>
			<td>
			<div id="topmenu">
			</div>
			</td>
			</tr>
		<tr>
			<td><br>
			<div id="topmenu" style="text-align:right; padding-top: 40px; margin-top: 4px;">
			<br>    
			</div>
			</td>
		</tr>
		<tr>
		<td valign="bottom">
		<div id="menu">
		<ul>
		</ul>
	</div>
	</td>
	</tr>
</table>
</td>
</tr>
<style>
/* CSS Document */

	body {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
		background-repeat: repeat-x;
	}
	
	#timepicker {
	    position:relative;
	    float:left;
	}
	#wrap {
	    color: #404040;
	    margin: 20px 40px;
	}
	
	#Wrapper {
		width: 900px;
		margin-top: 0px;
		margin-right: auto;
		margin-bottom: 0px;
		margin-left: auto;
		background-image: url(imgs/genband-all-bg-change1.png);
		background-repeat: no-repeat;
		background-position: 5px top;
	}
	#headerWrapper {
		width: 900px;
		height: 70px;
		margin-right: auto;
		margin-left: auto;
		padding: 0px;
		margin-top: 0px;
		margin-bottom: 0px;
	}
	#contentWrapper {
		width: 840px;
		border-right-width: 1px;
		border-left-width: 1px;
		border-right-style: solid;
		border-left-style: solid;
		border-right-color: #E5E5E5;
		border-left-color: #E5E5E5;
	}
	#content {
		padding: 15px;
		font-family: Arial, Helvetica, sans-serif;
		line-height: 130%;
		color: #333333;
		font-size: 75%;
	}
	#LeftColumn {
		width: 230px;
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 11px;
		line-height: 18px;
		padding-top: 10px;
		border-right-width: 1px;
		border-right-style: solid;
		border-right-color: #E5E5E5;
		text-align: center;
		padding-bottom: 20px;
	}
	#FooterContentBox {
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 12px;
		text-decoration: none;
		background-color: #CC0000;
		color: #FFFFFF;
		margin-right: auto;
		margin-left: auto;
		text-align: center;
		padding: 1px;
		height: 20px;
	}
	#FooterContentBox a {
		text-decoration: none;
		/*color: #FFFFFF;*/
		color: #FFFFFF;
		padding: 5px;
	}
	#FooterContentBox p {
		text-decoration: none;
		margin-top: 4px;
	}
	#FooterContentBox a:hover {
		text-decoration: none;
		background-color: #0633FF;
	}
	.customWidth{
		width: 60px;
		font-size: 12px;
	}

</style>


</script>
 <tr> 
 </tr>
 <tr>
  <td>
   <table border="0" align="center" cellpadding="0" cellspacing="0" id="contentWrapper">
    <tr>
     <td valign="top" id="LeftColumn">
     </td>
     <td valign="top" id="content">
     
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="LOGIN">
		<table align="center" border="0" cellpadding="5" cellspacing="0">
			<tr>
			<td></td>
			</tr>
			<tr>
				<td><label for="username"><b>Username</b></label></td>
				<td width="100%"><input id="username" type="text" name="username" size="20" maxlength="20"/></td>
			</tr>
			<tr>
				<td><label for="password"><b>Password</b></label></td>
				<td width="100%"><input id="password" type="password" name="password" size="20" maxlength="20" /></td>
			</tr>
			<tr>
				<td><input type="submit" name="submit" value="Login" ></td>
			</tr>
		</table>
	</form>

     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr><td>
 <div id="FooterContentBox">
 <p><b><script language="JavaScript"> document.write('&copy; ' + (new Date()).getFullYear());</script>
 GENBAND. All rights reserved.</b></p>
</div>
 </td>
 </tr>
</table>
</body>
</html>
<noscript> 
<meta http-equiv=refresh content='1;url=noscript.php'> 
</noscript> 
