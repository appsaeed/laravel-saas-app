<?php

namespace App\Http\Controllers;

use App\Models\User;

class Testcontroller extends Controller {
    public function index() {
        return User::find( '2560c47e-938d-4df2-8141-44bd8984f1c2' );
    }
}
