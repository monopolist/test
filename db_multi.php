<?php


$threads = isset($argv[1]) ? intval($argv[1]) : 5;

say('Run Multi DB test');
say("Threads: $threads");

$iterations = isset($argv[2]) ? intval($argv[2]) : 100;



$cache = new Memcached();
$cache->addServer('localhost', 11211);

$types = array(
    "MyISAM",
    "InnoDB",
);
$actions = array(
    'insert',
    'select',
    'update',
    'delete',
);

// clear cache
foreach($types as $type){
    foreach($actions as $action){
        $cache->delete("runtime_{$type}_{$action}");
    }
}


for($i = 0; $i<$threads - 1; $i++){
    exec("php db.php $iterations > /dev/null 2>&1 &");
    say('exec');
}

sleep(2);
say('Last thread exec');
exec("php db.php $iterations", $output);
say(implode(PHP_EOL, $output));

say('---');
say('Total results:');

foreach($actions as $action){
    foreach($types as $type){
        say("Type: $type, ".strtoupper($action).": " .  $cache->get("runtime_{$type}_{$action}"));
    }
    say('---');
}


function say($text)
{
    echo $text . PHP_EOL;
}
