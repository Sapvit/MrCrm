<!DOCTYPE html>
<html>
<body>
<?php
// 1. Создай БД (если нет)
$db = new PDO('sqlite:test.db');
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY, 
    name TEXT, 
    email TEXT
)");

// 2. Добавь данные (один раз)
$db->exec("INSERT OR IGNORE INTO users (id, name, email) VALUES 
    (1, 'Иван', 'ivan@mail.ru'),
    (2, 'Мария', 'maria@yandex.ru')");

// 3. Читай + таблица
$stmt = $db->query("SELECT * FROM users");
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Имя</th><th>Email</th></tr>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['name']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
</body>
</html>
