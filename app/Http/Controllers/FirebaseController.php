<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

class FirebaseController extends MainController
{

    public function index(){
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/mydb-6aeda3a963f2.json');
        $firebase = (new Factory)->withServiceAccount($serviceAccount)
                                 ->withDatabaseUri('https://mytestproject-c8035.firebaseio.com/')
                                 ->create();
        
        $database = $firebase->getDatabase();
        $reference = $database->getReference('notification');
        $snapshot = $reference->getSnapshot();

        $value = $snapshot->getValue();
        echo '<pre>';
        print_r($value);
        
        $database = $firebase->getDatabase();
        $newPost = $database
                             ->getReference('notification')
                             ->push([
                                        'title' => 'Post title',
                                        'body' => 'This should probably be longer.'
                                    ]);
        
        // print_r($newPost->getvalue());
        
    }
}
