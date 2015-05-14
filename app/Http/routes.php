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
use League\Fractal\Manager;

$transformer = function ($application) {
    return [
        'id' => $application->id,
        'name' => $application->name,
	    'processed' => is_null($application->approved)? false: true,
        'approved' => is_null($application->approved)? null: !! $application->approved,
        'reason'=> $application->reason,
        'created_at' => $application->created_at,
        'updated_at' => $application->updated_at,
    ];
};

$app->get('/', function() use ($app) {
    return $app->welcome();
});

$app->get('/applications', function () use ($app, $transformer) {
    $applications = $app['db']->table('applications')->latest()->get();
    $fractal = new Manager();
    $resource = new Collection($applications, $transformer);
    $data = $fractal->createData($resource)->toArray();
    return response()->json($data);
});

$app->put('/applications/{id}/approve', function ($id) use ($app) {
    $app['db']->table('applications')->where('id', $id)->update(['approved' => true]);
});

$app->put('/applications/{id}/deny', function ($id) use ($app) {
    $app['db']->table('applications')->where('id', $id)->update(['approved' => false]);
});

$app->delete('/applications/{id}', function ($id) use ($app) {
    $app['db']->table('applications')->delete($id);
});

