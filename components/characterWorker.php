<?php

namespace app\components;

class CharacterWorker{

    public function setUp(){
    }

    public function perform(){
        echo "!!! CharacterWorker.perform\n";
        print_r($this->args);
        print($this->args['id']);
    }

    public function tearDown(){
    }
}

