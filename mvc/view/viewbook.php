<?php
// view/viewbook.php
// expects $book
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?php echo htmlspecialchars($book->title); ?></title></head>
<body>
    <p><a href="index.php?route=home">Back to Home</a></p>
    <h2><?php echo htmlspecialchars($book->title); ?></h2>
    <p><strong>Author:</strong> <?php echo htmlspecialchars($book->author); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($book->description); ?></p>
</body>
</html>
