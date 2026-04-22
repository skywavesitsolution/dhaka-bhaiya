<?php

// Define global helper functions

// Function to get the username from userId
if (!function_exists('getUserName')) {
    function getUserName($userId)
    {
        $user = \App\Models\User::find($userId);
        return $user ? $user->name : 'Unknown User';
    }
}

// Function to convert amount to words
if (!function_exists('getAmountInWords')) {
    function getAmountInWords($number)
    {
        $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        return $f->format($number);
    }
}
