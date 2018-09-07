<?php

namespace ToDoApp\Entity;


class Status
{
    const COMPLETE = 'complete';
    const INCOMPLETE = 'incomplete';

    public static function isValid($status)
    {
        if ($status === self::COMPLETE || $status === self::INCOMPLETE) {
            return true;
        }
        return false;
    }
}