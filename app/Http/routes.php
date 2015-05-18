<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use Carbon\Carbon;

$transformer = function ($application) {
    return [
        'id' => $application->id,
        'name' => $application->name,
	    'processed' => is_null($application->approved)? false: true,
        'approved' => is_null($application->approved)? null: !! $application->approved,
        'reason'=> $application->reason,
        'created_at' => Carbon::parse($application->created_at)->toW3cString(),
        'updated_at' => Carbon::parse($application->updated_at)->toW3cString(),
    ];
};

/* This is for CORS */
$request = $app->make('request');
if($request->isMethod('OPTIONS'))
{
	$app->options($request->path(), function()
	{
		return response('OK', 200);
	});
}


$app->get('/', function() use ($app) {
    return $app->welcome();
});

$app->get('/refresh', function () use ($app) {
	$seeder = new ApplicationTableSeeder();
	$seeder->runFromLumen($app);
	return response()->json(['message' => 'ok!']);
});

$app->get('/addrandom', function () use ($app, $transformer) {
	$seeder = new ApplicationTableSeeder();
	$application = $seeder->addFromLumen($app);
	$fractal = new Manager();
	$resource = new Item($application, $transformer);
	$data = $fractal->createData($resource)->toArray();
	return response()->json($data);
});

$app->get('/applications', function () use ($app, $transformer) {
    $applications = $app['db']->table('applications')->latest()->get();
    $fractal = new Manager();
    $resource = new Collection($applications, $transformer);
    $data = $fractal->createData($resource)->toArray();
    return response()->json($data);
});

$app->put('/applications/{id}/approve', function ($id) use ($app, $transformer) {
    $app['db']->table('applications')->where('id', $id)->update(['approved' => true]);
	$application = $app['db']->table('applications')->find($id);
	$fractal = new Manager();
	$resource = new Item($application, $transformer);
	$data = $fractal->createData($resource)->toArray();
	return response()->json($data);
});

$app->put('/applications/{id}/deny', function ($id) use ($app, $transformer) {
    $app['db']->table('applications')->where('id', $id)->update(['approved' => false]);
	$application = $app['db']->table('applications')->find($id);
	$fractal = new Manager();
	$resource = new Item($application, $transformer);
	$data = $fractal->createData($resource)->toArray();
	return response()->json($data);
});

$app->delete('/applications/{id}', function ($id) use ($app) {
    $app['db']->table('applications')->delete($id);
	return response()->json(['id' => $id, 'messaged' => 'ok'], 204);
});

