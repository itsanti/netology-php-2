<?php

namespace App\Models;


class Question extends Model {

    const DRAFT = 0;
    const PUBLISHED = 1;
    const HIDDEN = 2;
    const BLOCKED = 3;

    protected $tblname = 'question';

    public function findByCategory($id) {
        $sql  = 'SELECT * FROM [question] WHERE %and ORDER BY [postdate] DESC';
        $result = $this->app->db->query($sql, [
            ['cat_id = %i', $id],
            ['status = %i', self::PUBLISHED]
        ]);
        return $result->fetchAll();
    }

    public function findAllByCategory($id) {
        $result = $this->app->db->query('SELECT * FROM [question] WHERE %and ORDER BY [postdate] DESC', [
            ['cat_id = %i', $id],
            ['status != %i', self::BLOCKED]
        ]);
        return $result->fetchAll();
    }

    public function findById($id)
    {
        $result = $this->app->db->query('SELECT * FROM [question] WHERE [id] = %i', $id);
        $result = $result->fetchAll();
        return $result[0];
    }

    public function findAllBlocked()
    {
        $sql =<<<SQL
SELECT q.id AS qid, c.id AS cid, q.q, c.name, q.postdate
FROM question AS q JOIN category AS c ON q.cat_id = c.id WHERE status = %i
ORDER BY q.postdate
SQL;
        $result = $this->app->db->query($sql, self::BLOCKED);
        return $result->fetchAll();
    }

    public function findFromBot()
    {
        $result = $this->app->db->query('SELECT * FROM [question] WHERE %and ORDER BY [postdate] DESC', [
            ['bot_id %sql', 'IS NOT NULL'],
            ['status != %i', self::BLOCKED]
        ]);
        return $result->fetchAll();
    }

    public function findAllWithoutAnswer() {
        $sql =<<<SQL
SELECT q.id AS qid, c.id AS cid, q.q, c.name, q.postdate
FROM question AS q JOIN category AS c ON q.cat_id = c.id WHERE a IS NULL AND status != %i
ORDER BY q.postdate
SQL;
        $result = $this->app->db->query($sql, self::BLOCKED);
        return $result->fetchAll();
    }

    public function addQuestion($data)
    {
        $this->app->db->query('INSERT INTO [question]', $data);
    }

    public function editQuestionById($id, $data)
    {
        if (empty($data['a'])) {
            unset($data['a']);
        }
        $this->app->db->query('UPDATE [question] SET ', $data, 'WHERE [id] = %i LIMIT 1', $id);
    }

    public function deleteQuestion($id)
    {
        $this->app->db->query('DELETE FROM [question] WHERE [id] = %i', $id);
    }

    public function deleteQuestionByCategoryId($id)
    {
        $this->app->db->query('DELETE FROM [question] WHERE [cat_id] = %i', $id);
    }

}