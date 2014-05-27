<?php

// config

$user = 'root';
$pass = '';

$pdo_myisam = "mysql:host=localhost;dbname=test";
$pdo_innodb = "mysql:host=localhost;dbname=test";

$pdo_innodb = new PDO($pdo_innodb, $user, $pass);
$pdo_myisam = new PDO($pdo_myisam, $user, $pass);

$table_myisam = "table_myisam";
$table_innodb = "table_innodb";

//$table_myisam = "table_myisam_noindex";
//$table_innodb = "table_innodb_noindex";

$type_myisam = "MyISAM";
$type_innodb = "InnoDB";



// testing

$iterations = isset($argv[1]) ? intval($argv[1]) : 100;

$tester = new DBTester;
$tester->iterations = $iterations;

$tester->say("Test DB Engines: MyISAM vs InnoDB");
$tester->say("Tables: $table_myisam, $table_innodb");
$tester->say("Iterations: $tester->iterations");


$tester->truncateTable($pdo_myisam, $table_myisam);
$tester->truncateTable($pdo_innodb, $table_innodb);



//******************** INSERT ****************************************************************


/** Test MyISAM Insert */
$tester->doAction('insert', $pdo_myisam, $table_myisam, $type_myisam);

/** Test InnoDB Insert */
$tester->doAction('insert', $pdo_innodb, $table_innodb, $type_innodb);

$tester->say(" ");


//******************** SELECT ****************************************************************

/** Test MyISAM Select */
//$tester->doAction('select', $pdo_myisam, $table_myisam, $type_myisam);

/** Test InnoDB Select */
//$tester->doAction('select', $pdo_innodb, $table_innodb, $type_innodb);

$tester->say(" ");

//******************** UPDATE ****************************************************************

/** Test MyISAM Update */
//$tester->doAction('update', $pdo_myisam, $table_myisam, $type_myisam);

/** Test InnoDB Update */
//$tester->doAction('update', $pdo_innodb, $table_innodb, $type_innodb);

$tester->say(" ");

//******************** DELETE ****************************************************************

/** Test MyISAM Delete */
//$tester->doAction('delete', $pdo_myisam, $table_myisam, $type_myisam);

/** Test InnoDB Delete */
//$tester->doAction('delete', $pdo_innodb, $table_innodb, $type_innodb);
$tester->say(" ");





class DBTester
{
    public $iterations = 1000;

    public $limit = 30;

    /** @var $_cache Memcached */
    private $_cache;


    function doAction($action, $pdo, $table, $type)
    {
        $start_time = microtime(true);

        for($i = 1; $i < $this->iterations; $i++){
            $this->$action($pdo, $table, $i);
        }
        $runtime = microtime(true) - $start_time;

        $this->say("$type $action runtime: '$runtime'");

        $this->storeRuntimeToCache($type, $action, $runtime);
    }

    function insert($pdo, $table, $id = null)
    {
        $sql = sprintf("INSERT INTO `%s` (`id`, `title`, `desc`, `status`, `date`) VALUES('', '%s','%s',%d,'%s');", $table, 'test', 'very hard test', 1, date("Y-m-d"));

        /** @var $pdo PDO */
        $pdo->query($sql);
    }


    function select($pdo, $table, $id = null)
    {
        $sql = "SELECT * FROM $table LIMIT " . $this->limit;

        /** @var $pdo PDO */
        $pdo->query($sql);
    }


    function update($pdo, $table, $id)
    {
        $sql = "UPDATE $table SET title = 'test-$id' WHERE id = " . rand(1, $this->iterations - 1);

        /** @var $pdo PDO */
        $pdo->query($sql);
    }


    function delete($pdo, $table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = $id";

        /** @var $pdo PDO */
        $pdo->query($sql);
    }


    function truncateTable($pdo, $table)
    {
        $sql = "TRUNCATE `$table`";

        /** @var $pdo PDO */
        $pdo->query($sql);
    }

    function storeRuntimeToCache($type, $action, $runtime)
    {
        $key = "runtime_{$type}_{$action}";

        $this->getCache()->set(
            $key,
            floatval($this->getCache()->get($key)) + $runtime
        );
    }

    function getCache()
    {
        if(!$this->_cache){
            $cache = new Memcached();
            $cache->addServer('localhost', 11211);

            $this->_cache = $cache;
        }

        return $this->_cache;
    }

    function say($text)
    {
        echo $text.PHP_EOL;
    }
}
