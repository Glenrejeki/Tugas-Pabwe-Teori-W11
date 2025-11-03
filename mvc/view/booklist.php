<?php /* expects $books as array of assoc */ ?>
<table border="1" cellpadding="6">
  <thead><tr><th>Title</th><th>Author</th><th>Description</th></tr></thead>
  <tbody>
  <?php foreach ($books as $book): ?>
    <tr>
      <td><a href="index.php?route=book&book=<?php echo urlencode($book['title']); ?>">
        <?php echo htmlspecialchars($book['title']); ?></a></td>
      <td><?php echo htmlspecialchars($book['author']); ?></td>
      <td><?php echo htmlspecialchars($book['description']); ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
