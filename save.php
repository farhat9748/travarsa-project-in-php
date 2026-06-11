save.php (without protection)

<?php

echo "<h2>Data Received by travarsa.com</h2>";

echo "Name: " . htmlspecialchars($_POST['name'] ?? '');



