<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedDemoUser extends Migration
{
    public function up()
    {
        $existing = $this->db->table('users')
            ->where('username', 'demo')
            ->orWhere('email', 'demo@pulse.local')
            ->countAllResults();

        if ($existing > 0) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');

        $this->db->table('users')->insert([
            'username'      => 'demo',
            'email'         => 'demo@pulse.local',
            'password_hash' => password_hash('demo1234', PASSWORD_DEFAULT),
            'active_room_id'=> 1,
            'last_seen_at'  => $timestamp,
            'last_typing_at'=> null,
            'created_at'    => $timestamp,
            'updated_at'    => $timestamp,
        ]);
    }

    public function down()
    {
        $this->db->table('users')
            ->where('username', 'demo')
            ->where('email', 'demo@pulse.local')
            ->delete();
    }
}
