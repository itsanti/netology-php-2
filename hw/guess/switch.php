<?php
// вопрос: почему при $a == -10 мы не попадаем в case -10?

/**
 * ответ:
 * http://php.net/manual/en/control-structures.switch.php
 *
 * 1. В операторе switch выражение вычисляется один раз
 *    и этот результат сравнивается с каждым оператором case.
 * 2. $a > 10 вычисляется в -10 > 10 == false
 * 3. т.е. по сути мы получаем switch (false) {}
 * 4. конструкция swich/case использует неточное сравнение (==) поэтому
 *    мы будем сравнивать bool значение false с числом 0: false == 0
 * 5. при сравнении bool с чем угодно оба операнда преобразуются к bool,
 *    поэтому мы получаем следующее сравнение для "case 0":
 *    false == false, результат такого сравнение равен true
 * 6. будет выполнятся код в первом case, а т.к. в блоке есть break,
 *    то остальные case будут пропущены
 */

$a = -10;

switch ($a > 10)
{
    case 0:
        // выполняется, например, при $a == -10
        echo 'case 0';
        break;
    case -10:
        // не выполняется никогда
        echo 'case -10';
        break;
}
