<?php
$collection = new \Gap\Config\ConfigCollection();
$collection
    ->set("app", [
        "Tec" => [
            "dir" => "app/tec",
        ],
    ]);
return $collection;
