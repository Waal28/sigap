<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengajuansTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'pegawai_id' => [
                'type' => 'INT',
            ],
            'jenis_kenaikan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Menunggu',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pegawai_id', 'pegawais', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengajuans', true);
    }

    public function down()
    {
        $this->forge->dropTable('pengajuans', true);
    }
}
