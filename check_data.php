<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=shop_tlo', 'root', '');
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $t) {
    $cnt = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
    if ($cnt > 0) {
        echo "$t: $cnt\n";
    }
}
