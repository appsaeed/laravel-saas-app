<?php
try {
    $path = '/var/www/html';
    $files = scandir( $path );
    print_r( $files );
} catch ( \Throwable $th ) {
    print_r( $th->getMessage() );
}