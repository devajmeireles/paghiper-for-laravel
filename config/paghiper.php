<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PagHiper API Credential
    |--------------------------------------------------------------------------
    |
    | This is the PagHiper API Credential. Please, visit the PagHiper
    | docs to get the credentials for your account: https://dev.paghiper.com/
    |
    */
    'api' => env('PAGHIPER_API'),

    /*
    |--------------------------------------------------------------------------
    | PagHiper Token Credential
    |--------------------------------------------------------------------------
    |
    | This is the PagHiper Token Credential. Please, visit the PagHiper
    | docs to get the credentials for your account: https://dev.paghiper.com/
    |
    */
    'token' => env('PAGHIPER_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | PagHiper Account E-mail
    |--------------------------------------------------------------------------
    |
    | This is the PagHiper Account E-mail. Generally, this is the email
    | of the account you are trying to communicate with PagHiper by API.
    |
    */
    'email' => env('PAGHIPER_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | PagHiper API URL
    |--------------------------------------------------------------------------
    |
    | For obvious reasons, the package allows you to edit the URL of the PagHiper
    | API, in case there is a crash, and they decide to change the URL for some reason,
    | you do not depend on the package being updated to continue using it,
    | but without a real reason PLEASE DO NOT CHANGE IT OR THE PACKAGE WILL NOT WORK.
    |
    */
    'url' => 'https://api.paghiper.com/',
];
