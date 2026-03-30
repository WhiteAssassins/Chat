<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoomPresenceFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('messages', [
            'room_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
                'after'      => 'user_id',
            ],
        ]);

        $this->forge->addColumn('users', [
            'active_room_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'password_hash',
            ],
            'last_typing_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'last_seen_at',
            ],
        ]);

        $this->db->table('messages')->set('room_id', 1)->update();
        $this->db->table('users')->set('active_room_id', 1)->update();
    }

    public function down()
    {
        $this->forge->dropColumn('messages', 'room_id');
        $this->forge->dropColumn('users', ['active_room_id', 'last_typing_at']);
    }
}
