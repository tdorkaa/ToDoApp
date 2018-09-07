<?php


use Phinx\Seed\AbstractSeed;

class FirstTodos extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'name'    => 'my todo1',
                'description' => 'example description',
                'status' => 'incomplete',
                'due_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'my todo2',
                'description' => 'example description',
                'status' => 'incomplete',
                'due_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $posts = $this->table('todos');
        $posts->insert($data)
            ->save();
    }
}
