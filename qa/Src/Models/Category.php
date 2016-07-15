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

    public function getQuestionStat()
    {
        $sql = 'select c.id, name, sum(if(status = 1, 1, 0)) as published, ' .
               'sum(if(a is NULL and status is not NULL, 1, 0)) as noanswer, sum(if(status is NULL, 0, 1)) as total ' .
               'from question as q right join category as c ON c.id = q.cat_id group by c.id order by c.id';
        $result = $this->app->db->query($sql);
        return $result->fetchAssoc('id');
    }

    public function addCategory($data)
    {
        $this->app->db->query('INSERT INTO [category]', $data);
    }

    public function deleteCategoryById($id)
    {
        $questions = new Question();
        $this->app->db->query('DELETE FROM [category] WHERE [id] = %i LIMIT 1', $id);
        $questions->deleteQuestionByCategoryId($id);
    }

}
