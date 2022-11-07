<?php

if (!function_exists('apply_validation_to')) {
    function apply_validation_to($params, $requestFor = 'create'): array
    {

        $commonValidations = array(
            "name"             => "required",
            "email"            => "required|email|unique:users,email",
            "password"         => "required",
            "city"             => "required",
            "state"            => "required",
            "dob"              => "required|date|before:today",
            "gender"           => "required|in:M,F,O",
            "confirm_password" => "required|same:password",
            "description"      => 'required',
            "type"             => 'required|alpha',
            "id"               => 'required|numeric|gt:0',
            "user_id"          => 'required|numeric|gt:0',
            "role_id"          => "required|numeric|gt:0",
            "product_id"       => 'required|numeric|gt:0',
            "category_id"      => 'required|numeric|gt:0',
            "registered_by"    => 'required|numeric|gt:0',
            "price"            => 'required|numeric|gt:0',
            "quantity"         => 'required|numeric|gt:0',
            "discount"         => 'required|numeric|gt:0',
            "date"             => 'required',
        );

        if ($requestFor != 'create') {
            $commonValidations['email'] = 'required';
        }

        $returnArray = array();

        foreach ($params as $param) {
            $returnArray[$param] = $commonValidations[$param];
        }

        return $returnArray;
    }
}


if (!function_exists('p')) {
    function p($arr): void
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}

if (!function_exists('isZero')) {
    function isZero($num): bool
    {
        return $num == 0;
    }
}

if (!function_exists('alert')) {
    function alert(): void
    {
        if (Session()->has('success')) {
            echo "<p class='alert alert-success'>" . session()->get('success') . "</p>";
        }

        if (Session()->has('warning')) {
            echo "<p class='alert alert-warning'>" . session()->get('warning') . "</p>";
        }
        if (Session()->has('error')) {
            echo "<p class='alert alert-fail'>" . session()->get('error') . "</p>";
        }
    }
}


if (!function_exists('sentenceCase')) {
    function sentenceCase($string): string
    {
        return preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $string);
    }
}
