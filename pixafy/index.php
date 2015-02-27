<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="./login.js"></script>
</head>
<body>

<?php require_once('user.php'); ?>

<form method="post" action="login.php">
    <div class="field">Email: <input type="text" name="email"></div> <br />
    <div class="field">Password: <input type="password" name="password"></div> <br />
    <div class='actions'><input type="submit" value="Log In" name="submit" disabled="disabled"></div>
</form>

<br />
<form method="post" action="register.php">
    <div class="field2">Email: <input type="text" name="email"></div> <br />
    <div class="field2">Password: <input type="password" name="password" id="register_password"></div> <br />
    <div class="field2">Confirm Password: <input type="password" name="confirm_password" id="register_confirm"></div> <br />
    <div class='actions2'><input type="submit" value="Register" name="submit" disabled="disabled"></div>
</form>

</body>
</html>