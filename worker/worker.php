<?php declare(strict_types=1);

ini_set('display_errors', 'stderr');

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$app = require 'src/public/index.php';

$relay = new Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new Spiral\RoadRunner\PSR7Client(new Spiral\RoadRunner\Worker($relay));

while ($req = $psr7->acceptRequest()) {
    try {
        $response = $app->handle($req);
        $psr7->respond($response);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
