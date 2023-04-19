<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function decryptId($action, $string){
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = '12345678911547891234567891154789';
        $iv = '1234567891154789';
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key,0,$iv);
            $output;
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt($string, $encrypt_method, $key,0,$iv);
        }

        return $output;
    }
}
