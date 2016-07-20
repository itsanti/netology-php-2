<?php

namespace App\Models;


class User extends Model {

    protected $tblname = 'user';

    const ACTIVE = 1;
    const BLOCKED = 0;

    public function findCredentials($login) {
        $sql  = 'SELECT * FROM [user] WHERE %and';
        $result = $this->app->db->query($sql, [
            ['login = %s', $login],
            ['status = %i', self::ACTIVE]
        ]);
        return $result->fetchPairs('login', 'password');
    }

    public function addUser($data)
    {
        $this->app->db->query('INSERT INTO [user]', $data);
    }

    public function editUserById($id, $data)
    {
        $this->app->db->query('UPDATE [user] SET ', $data, 'WHERE [id] = %i', $id);
    }

    public function blockUserById($id)
    {
        $this->app->db->query('UPDATE [user] SET [status] = %i', self::BLOCKED, 'WHERE [id] = %i', $id);
    }

    public function deleteUserById($id)
    {
        $this->app->db->query('DELETE FROM [user] WHERE [id] = %i LIMIT 1', $id);
    }
}