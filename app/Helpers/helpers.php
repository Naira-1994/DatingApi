<?php

if (!function_exists('add_value_to_array')) {
    function add_value_to_array($value, string $name, array &$data): void {
         if (isset($value)) {
             $data[$name] = $value;
         }
    }
}
