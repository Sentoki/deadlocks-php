<?php
/**
 * Created by PhpStorm.
 * User: egor
 * Date: 17.09.16
 * Time: 14:26
 */

$from_acc = $argv[1];
$to_acc = $argv[2];
$amount = $argv[3];

$pdo = new PDO('pgsql:dbname=deadlocks;host=127.0.0.1', 'postgres', '123456');

for ($i = 1; $i <= 20; $i++) {
    $pdo->beginTransaction();
    $stm = $pdo->prepare('SELECT id FROM account where id=:id_from for update;');
    $lock_1_result = $stm->execute([
        'id_from' => $from_acc,
    ]);
    if ($lock_1_result) {
        echo "{$i}) lock 1 ok\n";
    } else {
        echo "{$i}) lock 1 failure\n";
    }

    usleep(1000);
    $stm = $pdo->prepare('SELECT id FROM account where id=:id_to for update;');
    $lock_2_result = $stm->execute([
        'id_to' => $to_acc,
    ]);
    if ($lock_2_result) {
        echo "{$i}) lock 2 ok\n";
    } else {
        echo "{$i}) lock 2 failure\n";
    }

    $insert_stm = $pdo->prepare(
        'INSERT INTO operation 
    (amount, account_from_id, account_to_id) VALUES 
    (:amount, :from, :to);'
    );
    $insert_stm->execute([
        'from' => $from_acc,
        'to' => $to_acc,
        'amount' => $amount,
    ]);

    $decrease_stm = $pdo->prepare('UPDATE account set amount=(amount-:amount) WHERE id=:id');
    $decrease_stm->execute([
        'amount' => $amount,
        'id' => $from_acc,
    ]);

    $increase_stm = $pdo->prepare('UPDATE account set amount=(amount+:amount) WHERE id=:id');
    $increase_stm->execute([
        'amount' => $amount,
        'id' => $to_acc,
    ]);

    $result = $pdo->commit();
    if ($result) {
        echo "{$i}) commit ok\n";
    } else {
        echo "{$i}) commit failure\n";
    }
    echo "=====================================================\n";
}