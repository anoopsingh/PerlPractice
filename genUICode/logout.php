<SCRIPT LANGUAGE="JavaScript">
<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->
<!-- Begin
if (window != top) top.location.href = location.href;
// End -->
</SCRIPT>

<?php
    include("session.php");
    header("Cache-control: private");
    session_destroy();
    header('Window-target: _top');
    header("Location: http://" . $_SERVER{'SERVER_NAME'} . "/login.php");
?>

