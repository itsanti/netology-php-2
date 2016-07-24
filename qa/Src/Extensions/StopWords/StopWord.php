<?php

namespace App\Extensions\StopWords;

class StopWord {

    private static function search($q)
    {
        $sw = new StopWordModel();
        $words = $sw->findAllFlat();

        if (empty($words)) {
            return [];
        }

        $patterns_flattened = implode('|', $words);

        if (preg_match_all("~{$patterns_flattened}~", $q, $matches))
        {
            return $matches[0];
        }

        return [];
    }

    public static function isClean($question)
    {
        return empty(self::search($question));
    }

    public static function getWords($question)
    {
        return self::search($question);
    }

}