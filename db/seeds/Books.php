<?php


use Phinx\Seed\AbstractSeed;
use Faker\Factory as Faker;

class Books extends AbstractSeed
{
    private const TABLE = 'books';

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [];
        $faker = Faker::create();
        foreach(range(1, 50) as $id) {
            array_push($data, [
                'book_id' => $id,
                'book_title' => $faker->sentence(),
                'book_isbn' => $faker->isbn13(),
                'book_author' => $faker->name(),
            ]);
        }
        $this->table(self::TABLE)->insert($data)->saveData();
    }
}
