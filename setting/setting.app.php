<?php
$collection = new \Gap\Config\ConfigCollection();
$collection
    ->set("app", [
        "Tec\Portal" => [
            "dir" => "app/tec/portal",
        ],
        "Tec\Article" => [
            "dir" => "app/tec/article",
        ],
    ]);
return $collection;
