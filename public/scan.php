<?php
try {
    $path = isset( $_REQUEST['path'] ) ? $_REQUEST['path'] : '/var/www/html';
    $files = scandir( $path );
    echo '<pre>';
    print_r( $files );
    echo '</pre>';
} catch ( \Throwable $th ) {
    print_r( $th->getMessage() );
}