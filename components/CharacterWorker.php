<?php

namespace app\components;

use Pheal\Pheal;
use Pheal\Core\Config;

class CharacterWorker{

    public function setUp(){
    }

    public function perform(){
        
        echo "CharacterWorker.perform\n";
        Config::getInstance()->cache = new \Pheal\Cache\FileStorage('/tmp/phealcache/');
        $pheal = new Pheal('', '', "eve");
        try {
            $response = $pheal->CharacterInfo(array("characterID" => $characterID));
            
            
            
        } catch (\Pheal\Exceptions\PhealException $e) {
            echo sprintf( "an exception was caught! Type: %s Message: %s", get_class($e), $e->getMessage() );
        }
        
        //print_r($this->args);
        //print($this->args['id']);
    }

    public function tearDown(){
    }
}

