<?php

class CacheKey
{
    //Tanks:
    public static function tanks()
    {
        return 'tanks';
    }

    //Tank info:
    public static function tankinfo($id)
    {
        return 'tankinfo::' . $id;
    }

    //Clan info:
    public static function clanInfo($id)
    {
        return 'claninfo::' . $id;
    }
}
