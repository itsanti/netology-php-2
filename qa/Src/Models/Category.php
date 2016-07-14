<?php
/**
 * Created by PhpStorm.
 * User: itsanti
 * Date: 13.07.2016
 * Time: 19:16
 */

namespace App\Models;


class Category extends Model {

    protected $tblname = 'category';

    public function getStat()
    {
        $sql = 'SELECT c.id, c.name, q.status, count(*) as total FROM `category` as c LEFT JOIN question as q ON c.id = q.cat_id GROUP BY c.id, q.status';
    }

}