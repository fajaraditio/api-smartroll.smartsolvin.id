<?php

return [
    "origins" => [
        "https://smartroll.smartsolvin.id",
        "https://smartroll.smartsolvin.test",
        "http://localhost:5173/"
    ],

    "methods" => "GET, POST, PUT, PATCH, DELETE, OPTIONS",

    "headers" => "Authorization, Content-Type, X-Requested-With, Accept, Origin",

    "credentials" => true,

    "max_age" => 86400,
];
