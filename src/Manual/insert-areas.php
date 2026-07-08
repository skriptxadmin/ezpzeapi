<?php

$pdo = new PDO(
    "mysql:host=localhost;dbname=justdial",
    "root",
    ""
);

$data = require __DIR__.'/./areas.php';

$chunks = array_chunk($data, 1000);

foreach ($chunks as $rows) {

    $values = [];
    $params = [];

    foreach ($rows as $row) {

        $values[] = "(?,?,?,?)";

        $params[] = $row['id'];
        $params[] = $row['slug']
        $params[] = $row['area'];
        $params[] = $row['taluk_id'];
        $params[] = $row['pincode_id'];
        $params[] = $row['aka']
    }

    $sql =
        "INSERT INTO localities
        (id,slug,area,taluk_id,pincode_id,aka)
        VALUES " .
        implode(',', $values);

    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    echo "Inserted ".count($rows)." rows\n";
}