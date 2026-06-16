<?php
echo "<h2>PHP Info</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

echo "<h3>Загруженные расширения:</h3>";
$extensions = get_loaded_extensions();
echo "<pre>";
foreach ($extensions as $ext) {
    echo $ext . "\n";
}
echo "</pre>";

echo "<h3>Поиск PDO:</h3>";
if (extension_loaded('pdo')) {
    echo "✅ PDO загружен<br>";
    
    $drivers = PDO::getAvailableDrivers();
    echo "<strong>Доступные драйверы PDO:</strong><br>";
    foreach ($drivers as $driver) {
        echo "  - " . $driver . "<br>";
    }
} else {
    echo "❌ PDO НЕ загружен!<br>";
}

if (extension_loaded('pdo_mysql')) {
    echo "✅ pdo_mysql загружен<br>";
} else {
    echo "❌ pdo_mysql НЕ загружен!<br>";
}

if (extension_loaded('mysqli')) {
    echo "✅ mysqli загружен<br>";
} else {
    echo "❌ mysqli НЕ загружен!<br>";
}
?>
