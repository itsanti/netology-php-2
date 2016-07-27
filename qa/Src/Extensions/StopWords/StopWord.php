<?php

namespace App\Extensions\StopWords;

class StopWord {

    /**
     * Функция реализует механизм поиска слов.
     *
     * @param $q
     *
     * @return array
     */
    private static function search($q)
    {
        $sw = new StopWordModel();
        $words = $sw->findAllFlat();

        if (empty($words)) {
            return [];
        }

        $patterns_flattened = implode('|', $words);

        if (preg_match_all("~{$patterns_flattened}~i", $q, $matches))
        {
            return $matches[0];
        }

        return [];
    }

    /**
     * Функция проверки вопроса на наличие стопслов.
     *
     * @param $question
     *
     * @return bool
     */
    public static function isClean($question)
    {
        return empty(self::search($question));
    }

    /**
     * Функия возвращает массив слов за которые был заблокирован вопрос.
     *
     * @param $question
     *
     * @return array
     */
    public static function getWords($question)
    {
        return self::search($question);
    }

}