<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ApplicationTableSeeder extends Seeder {

	protected $reason = [
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

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('applications')->truncate();

		$faker = Faker::create();

		foreach(range(1, 20) as $i) {
			$dt = $faker->dateTimeThisMonth;
			$data = [
				'name' => $faker->firstName . ' ' . $faker->lastName,
				'approved' => $faker->randomElement([null, true, false]),
				'reason' => $faker->randomElement($this->reason),
				'created_at' => $dt,
				'updated_at' => $dt
			];
			DB::table('applications')->insert($data);
		}
	}

	public function runFromLumen($app) {
		$app['db']->table('applications')->truncate();

		$faker = Faker::create();


		foreach(range(1, 20) as $i) {
			$dt = $faker->dateTimeThisMonth;
			$data = [
				'name' => $faker->firstName . ' ' . $faker->lastName,
				'approved' => $faker->randomElement([null, true, false]),
				'reason' => $faker->randomElement($this->reason),
				'created_at' => $dt,
				'updated_at' => $dt
			];
			$app['db']->table('applications')->insert($data);
		}
	}

	public function addFromLumen($app) {
		$faker = Faker::create();
		$name = $faker->firstName . ' ' . $faker->lastName;
		$data = [
			'name' => $name,
			'approved' => null,
			'reason' => $faker->randomElement($this->reason),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		];

		$app['db']->table('applications')->insert($data);
		return $app['db']->table('applications')->where('name', $name)->latest()->first();
	}
}