<?php

use Phinx\Migration\AbstractMigration;

class AddPostTable extends AbstractMigration
{
    public function up()
    {
        $post = $this->table('post');
        $post->addColumn('title', 'string', ['limit' => 250])
            ->addColumn('content', 'string')
            ->addColumn('created_dt', 'datetime')
            ->addColumn('updated_dt', 'datetime', ['null' => true])
            ->create();
    }

    public function down()
    {
        $this->table('post')->drop()->save();
    }
}
