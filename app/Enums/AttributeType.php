<?php

namespace App\Enums;

enum AttributeType: string
{
    case Text = 'text';
    case Number = 'number';
    case Select = 'select';
    case Radio = 'radio';
    case Color = 'color';
}