<?php

if ( !function_exists('apply_validation_to')) {
    function apply_validation_to($params, $requestFor='create'): array
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

        if($requestFor!='create'){
            $commonValidations['email'] = 'required';
        }

        $returnArray = array();

        foreach ( $params as $param ) {
            $returnArray[$param] = $commonValidations[$param];
        }

        return $returnArray;
    }
}


if ( !function_exists('p') ) {
    function p($arr): void
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}


