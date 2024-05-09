<?php
namespace App\Repositories\Traits;

trait Protections {

    public function checks() {
        return auth()->id() !== 1 && config( 'app.stage' ) == 'demo';
    }
}

/// $this->checks()