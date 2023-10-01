<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Message
{

    /**
     * someting went wrong
     * @return string
     */
    public static function wentWrong(): string
    {
        return __('locale.exceptions.something_went_wrong');
    }

    /**
     *  todo status class 
     */
    public static function todoStatusclass(string $name): string
    {

        $data =  [
            'available'         => 'info',
            'in_progress'       => 'primary',
            'review'            => 'warning',
            'complete'          => 'success',
            'pending'           => 'info',
            'pause'             => 'info',
            'continue'          => 'primary',
        ];
        if (isset($data[$name])) {
            return $data[$name];
        }

        return 'nothing';
    }
}
