<?php
/**
 * Created by PhpStorm.
 * User: itsanti
 * Date: 13.07.2016
 * Time: 19:16
 */

namespace App\Models;


class Question extends Model {

    const DRAFT = 0;
    const PUBLISHED = 1;

    protected $tblname = 'question';

    public function findByCategory($id) {
        $sql  = 'SELECT * FROM [question] WHERE %and ORDER BY [postdate] DESC';
        $result = $this->app->db->query($sql, [
            ['cat_id = %i', $id],
            ['status = %i', self::PUBLISHED]
        ]);
        return $result->fetchAll();
    }

    public function addQuestion($data)
    {
        $this->app->db->query('INSERT INTO [question]', $data);
    }

}