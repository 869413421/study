<?php
/**
 * Created by PhpStorm.
 * User: 、、、、、、、、、、、
 * Date: 2019/12/2
 * Time: 22:07
 */

use Swoole\Table;

$table = new Table(1024);

$table->column('id', $table::TYPE_INT, 4);
$table->column('name', $table::TYPE_STRING, 8);
$table->column('age', $table::TYPE_INT, 3);

$table->create();

$table->set('ym', ['id' => 1, 'name' => 'Ym', 'age' => 24]);
$table->incr('ym', 'age', 2);
$table->decr('ym', 'age', 1);

if ($table->exist('ym')) {
    $value = $table->get('ym');
    var_dump($value);
    $table->del('ym');
} else {
    echo '错误';
}
