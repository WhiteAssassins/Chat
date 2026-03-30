<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRooms extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 60,
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 160,
            ],
            'accent' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'ember',
            ],
            'sort_order' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('rooms');

        $timestamp = date('Y-m-d H:i:s');

        $this->db->table('rooms')->insertBatch([
            [
                'name'        => 'General',
                'slug'        => 'general',
                'description' => 'Conversacion diaria para mensajes rapidos y coordinacion general.',
                'accent'      => 'ember',
                'sort_order'  => 1,
                'created_at'  => $timestamp,
                'updated_at'  => $timestamp,
            ],
            [
                'name'        => 'Ideas',
                'slug'        => 'ideas',
                'description' => 'Espacio para propuestas, brainstorm y notas que luego se vuelven tareas.',
                'accent'      => 'forest',
                'sort_order'  => 2,
                'created_at'  => $timestamp,
                'updated_at'  => $timestamp,
            ],
            [
                'name'        => 'Soporte',
                'slug'        => 'soporte',
                'description' => 'Incidencias, bloqueos y seguimiento rapido de problemas.',
                'accent'      => 'ink',
                'sort_order'  => 3,
                'created_at'  => $timestamp,
                'updated_at'  => $timestamp,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('rooms', true);
    }
}
