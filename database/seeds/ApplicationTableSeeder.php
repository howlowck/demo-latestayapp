<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ApplicationTableSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('applications')->truncate();

		$faker = Faker::create();
		$reason = [
			'I need a place to stay',
			'I have family visiting',
			'commencement',
			'friends visiting',
			'No where else to stay',
			'International students',
			'It\'s too cold outside',
			'extended exams',
			'Didn\'t finish my paper on time',
		];

		foreach(range(1, 20) as $i) {
			$dt = $faker->dateTimeThisMonth;
			$data = [
				'name' => $faker->firstName . ' ' . $faker->lastName,
				'approved' => $faker->randomElement([null, true, false]),
				'reason' => $faker->randomElement($reason),
				'created_at' => $dt,
				'updated_at' => $dt
			];
			DB::table('applications')->insert($data);
		}
	}

}