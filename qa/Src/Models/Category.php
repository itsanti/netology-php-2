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
        $sql = 'SELECT c.id, name, SUM(IF(status = '.Question::PUBLISHED.', 1, 0)) AS published, ' .
               'SUM(IF(a IS NULL AND status IS NOT NULL AND status != '.Question::BLOCKED.', 1, 0)) AS noanswer, ' .
               'SUM(IF(status IS NULL OR status = '.Question::BLOCKED.', 0, 1)) AS total ' .
               'FROM question AS q RIGHT JOIN category AS c ON c.id = q.cat_id GROUP BY c.id ORDER BY c.id';
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
