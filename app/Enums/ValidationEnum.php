<?php

namespace App\Enums;

enum ValidationEnum: string
{
    // Define a constant for the required message
    case REQUIRED_MESSAGE = 'The :attribute field is required.';

    // Define a constant for the string message

    case STRING_MESSAGE = 'The :attribute must be a string.';
}
