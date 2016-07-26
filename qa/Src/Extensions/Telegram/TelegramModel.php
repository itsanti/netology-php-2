<?php

namespace App\Extensions\Telegram;

use App\Models\Model;

class TelegramModel extends Model {

    protected $tblname = 'telegram';

    public function findById($id)
    {
        $result = $this->app->db->query('SELECT * FROM [telegram] WHERE [id] = %i', $id);
        $result = $result->fetchAll();
        return !empty($result)? $result[0] : null;
    }

    public function addQuestion($data)
    {
        $this->app->db->query('INSERT INTO [telegram]', $data);
        return $this->app->db->getInsertId();
    }

    public function deleteQuestion($id)
    {
        $this->app->db->query('DELETE FROM [telegram] WHERE [id] = %i', $id);
    }

}
