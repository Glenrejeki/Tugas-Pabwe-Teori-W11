<?php
// view/home.php
// expects $user and $books
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Home</title></head>
<body>
    <p><a href="index.php?route=logout">Logout</a></p>
    <h1>Welcome, <?php echo htmlspecialchars($user['nama']); ?>!</h1>

    <h3>Some Books</h3>
    <?php include 'view/booklist.php'; ?>
</body>
</html>
