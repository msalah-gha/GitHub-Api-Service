<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Version Control Systems Settings (configurations)
    |--------------------------------------------------------------------------
    |
    | Here we declare and configure the general settings we can use to connect to:
    | GitHub Api as example with it's basic settings: base_uri, if there are any general configurations we
    | can set it here, for other system control platforms we can add it's settings here also
    |
    |
    */

    'github' => [
           'base_uri' => env('GITHUB_API_URL', 'https://api.github.com/'),
           'paginate' => env('GITHUB_RESULTS_PAGINATE', 10),
        ]
];
