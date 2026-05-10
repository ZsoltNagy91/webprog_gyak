<?php
require_once 'config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $category = trim($_POST['category'] ?? 'Egyéb');
    $image = trim($_POST['image'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title !== '' && $slug !== '' && $content !== '') {

        $sql = "INSERT INTO posts
                (title, slug, category, image, summary, content, created_at)
                VALUES
                (:title, :slug, :category, :image, :summary, :content, NOW())";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'image' => $image,
            'summary' => $summary,
            'content' => $content
        ]);

        $message = 'A bejegyzés sikeresen elmentve.';
    } else {
        $message = 'A cím, a slug és a tartalom megadása kötelező.';
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <title>Új blogbejegyzés</title>
</head>
<body>

<h1>Új blogbejegyzés</h1>

<?php if ($message): ?>
    <p><strong><?php echo htmlspecialchars($message); ?></strong></p>
<?php endif; ?>

<form method="post">

    <p>
        Cím:<br>
        <input type="text" name="title" required style="width: 600px;">
    </p>

    <p>
        Slug:<br>
        <input type="text" name="slug" required style="width: 600px;">
    </p>

    <p>
        Kategória:<br>
        <input type="text" name="category" value="Egyéb" style="width: 600px;">
    </p>

    <p>
        Kép útvonala:<br>
        <input type="text" name="image" style="width: 600px;" placeholder="images/blog/mirai.jpg">
    </p>

    <p>
        Összefoglaló:<br>
        <textarea name="summary" rows="4" cols="80"></textarea>
    </p>

    <p>
        Teljes tartalom:<br>
        <textarea name="content" rows="20" cols="80" required></textarea>
    </p>

    <p>
        <button type="submit">Mentés</button>
    </p>

</form>

</body>
</html>