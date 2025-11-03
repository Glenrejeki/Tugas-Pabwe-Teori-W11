<?php
// view/login.php
// expects $error maybe set
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
    <h2>Login</h2>
    <?php if (!empty($error)) echo '<p style="color:red;">'.htmlspecialchars($error).'</p>'; ?>
    <form method="post" action="index.php?route=login">
        <input type="hidden" name="action" value="login" />
        <label>Username: <input type="text" name="username" required/></label><br/>
        <label>Password: <input type="password" name="password" required/></label><br/>
        <button type="submit">Login</button>
    </form>
    <p>Dummy accounts: <strong>glen/password123</strong> or <strong>andi/secret456</strong></p>
</body>
</html>
