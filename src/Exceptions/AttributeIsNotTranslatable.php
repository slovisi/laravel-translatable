<?php

namespace Spatie\Translatable\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class AttributeIsNotTranslatable extends Exception
{
    public static function make($key, $model)
    {
        $translatable = implode(', ', $model->getTranslatableAttributes());

        return new static("Cannot translate attribute `{$key}` as it's not one of the translatable attributes: `$translatable`");
    }
}
