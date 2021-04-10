<?php

use Facades\App\Facades\ApiFacade;
use  Modules\Core\Support\Hashing\Obfuscator;

Route::get('system/test-api', function() {

    // search apps
    // $searchTerm = request()->get('q', 'mobile');
    // $api = ApiFacade::search($searchTerm);
    // pre($api);

    // get details
    // $searchTerm = 'com.facebook.katana';
    // $api = ApiFacade::detail($searchTerm,[], 'admin');
    //  pre($api);
    // exit;
});


Route::get('system/storage-link', function() {

    if ( file_exists(public_path('storage')) )
        unlink(public_path('storage'));

    Artisan::call('storage:link');
    exit;

});