<?php

namespace App\Extensions\StopWords;

use App\Models\Model;

class StopWordModel extends Model {

    protected $tblname = 'stopword';

    public function findAllFlat() {
        $result = $this->app->db->query("SELECT * FROM [{$this->tblname}]");
        return $result->fetchPairs();
    }

    public function addStopWord($data)
    {
        $this->app->db->query('INSERT INTO [stopword]', $data);
    }

    public function deleteStopWordById($id)
    {
        $this->app->db->query('DELETE FROM [stopword] WHERE [id] = %i LIMIT 1', $id);
    }

}
