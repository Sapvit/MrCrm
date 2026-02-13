<!DOCTYPE html>
<html>
<body>
<?php
// 1. Создаем БД (если нет)
$db = new PDO('sqlite:test.db');

if ($db->query("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='users'")->fetchColumn() == 0) {
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY, 
        name TEXT, 
        email TEXT,
        comment TEXT
    )");

// 2. Добавляем данные (один раз, для старта - демо)
    $db->exec("INSERT OR IGNORE INTO users (id, name, email, comment) VALUES 
        (1, 'Иван', 'ivan@mail.ru', 'aaa'),
        (2, 'Мария', 'maria@yandex.ru', 'asdd')");
}
?>

<?php
// Delete:
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

//Edit:
$editUser = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
// 2. UPDATE: сохраняем изменения
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $stmt = $db->prepare("UPDATE users SET name=?, email=?, comment=? WHERE id=?");
    $stmt->execute([
        $_POST['name'], 
        $_POST['email'], 
        $_POST['comment'], 
        (int)$_POST['update_id']
    ]);
    header('Location: ?'); exit;
}


// 3. Читаем + создаем таблицу
$stmt = $db->query("SELECT * FROM users");
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Имя</th><th>Email</th><th>Comment</th></tr>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['name']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['comment']}</td>";
    echo "<td>
            <a href='?delete={$row['id']}'>Удалить</a> |
            <a href='?edit={$row['id']}'>Редактировать</a>
        </td>";
    echo "</tr>";
}
echo "</table>";
?>

<!-- Форма редактирования-->
<form method="POST">
    <?php if ($editUser): ?>
        <!-- РЕДАКТИРОВАНИЕ -->
        <input type="hidden" name="update_id" value="<?= $editUser['id'] ?>">
        Имя: <input name="name" value="<?= htmlspecialchars($editUser['name']) ?>"><br>
        Email: <input name="email" value="<?= htmlspecialchars($editUser['email']) ?>"><br>
        Comment: <input name="comment" value="<?= htmlspecialchars($editUser['comment']) ?>"><br>
        <button name="update_id" value="<?= $editUser['id'] ?>">Сохранить</button>
        <a href="?">Отмена</a>
    <?php else: ?>
        <!-- ДОБАВЛЕНИЕ (твой старый код) -->
        Имя: <input name="name"><br>
        Email: <input name="email"><br>
        Comment: <input name="comment"><br>
        <button>Добавить</button>
    <?php endif; ?>
</form>

<!-- 1. Только POST -->
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $stmt = $db->prepare("INSERT INTO users (name, email, comment) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['comment']]);
    echo "<p style='color:green'>Добавлено!</p>";
    // 2. Редирект (убивает F5 дубль!)
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
} ?>

<!-- <?php
// Обработка POST
if ($_POST['name']) {
    $stmt = $db->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email']]);
    echo "<p>Добавлено!</p>";
}
?> -->

</body>
</html>
