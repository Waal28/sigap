<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLampiranPengajuansTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'pengajuan_id' => [
                'type' => 'INT',
            ],
            'nama_dokumen' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addForeignKey('pengajuan_id', 'pengajuans', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('lampiran_pengajuans', true);
    }

    public function down()
    {
        $this->forge->dropTable('lampiran_pengajuans', true);
    }
}
