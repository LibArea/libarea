<?php

/**
 * See PDO settings (for some cases)
 * См. настройки PDO (для некоторых случаев)
 *
 * \PDO::ATTR_EMULATE_PREPARES => false,
 * \PDO::ATTR_STRINGIFY_FETCHES => false,
 */

$sql = "SELECT id FROM users WHERE email = :email";

$pdo = new PDO('mysql:dbname=***dbname***;host=localhost', '***user***', '***pass***');
$pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', 'test@test.ru', PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

var_dump($row['id']); 