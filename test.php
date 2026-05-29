<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$now = \Carbon\Carbon::now();
echo "NOW: " . $now . PHP_EOL;

foreach(App\Models\MassageRecord::all() as $r) {
    echo $r->id . " | Start: " . $r->start_time . " | End: " . $r->end_time;
    if ($r->start_time && $r->end_time && $now->between($r->start_time, $r->end_time)) {
        echo " [ACTIVE]";
    }
    echo PHP_EOL;
}
