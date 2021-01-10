<?php
/**
 * Created by PhpStorm.
 * User: rustysun
 */
namespace Lum\Lubye\Utils;
/**
 * 英语名词单、复数转换
 *
 * @package Lum\Lubye\Utils
 */
class Inflector {
    private static $irregular   = [
        'move'   => 'moves',
        'sex'    => 'sexes',
        'child'  => 'children',
        'man'    => 'men',
        'person' => 'people',
        'cow'    => 'kine',
        'zombie' => 'zombies'
    ];
    private static $uncountable = [
        'sheep',
        'fish',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment',
        'jeans'
    ];

    /**
     * 单数名词转成复数名词
     *
     * @param string $string 单数名词
     *
     * @return mixed
     */
    public static function pluralize($string) {
        $str = strtolower($string);
        if (in_array($str, static::$uncountable)) {
            return $string;
        }
        if (isset(static::$irregular[$str])) {
            return static::$irregular[$str];
        }
        $pluralPatterns = [
            '/(quiz)$/i',
            '/^(oxen)$/i',
            '/^(ox)$/i',
            '/([m|l])ice$/i',
            '/([m|l])ouse$/i',
            '/(matr|vert|ind)(?:ix|ex)$/i',
            '/(x|ch|ss|sh)$/i',
            '/([^aeiouy]|qu)y$/i',
            '/([^aeiouy]|qu)ies$/i',
            '/(hive)$/i',
            '/(?:([^f])fe|([lr])f)$/i',
            '/sis$/i',
            '/([ti])(?:um|a)$/i',
            '/(buffal|tomat)o$/i',
            '/(bu)s$/i',
            '/(alias|status)$/i',
            '/(octop|vir)(?:us|i)$/i',
            '/(ax|test)is$/i',
            '/s$/i',
            '/$/'
        ];
        $pluralReplaces = [
            "$1zes",
            "$1",
            "$1en",
            "$1ice",
            "$1ice",
            "$1ices",
            "$1es",
            "$1ies",
            "$1y",
            "$1s",
            "$1$2ves",
            "ses",
            "$1a",
            "$1oes",
            "$1ses",
            "$1es",
            "$1i",
            "$1es",
            "s",
            "s"
        ];
        return preg_replace($pluralPatterns, $pluralReplaces, $string);
    }

    /**
     * @param string $str
     *
     * @return string
     */
    public static function singular(string $str): string {
        $singularPattern = [
            "/s$/",
            "/(n)ews$/",
            "/([ti])a$/",
            "/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/",
            "/(^analy)ses$/",
            "/([^f])ves$/",
            "/(hive)s$/",
            "/(tive)s$/",
            "/([lr])ves$/",
            "/([^aeiouy]|qu)ies$/",
            "/(s)eries$/",
            "/(m)ovies$/",
            "/(x|ch|ss|sh)es$/",
            "/([m|l])ice$/",
            "/(bus)es$/",
            "/(o)es$/",
            "/(shoe)s$/",
            "/(cris|ax|test)es$/",
            "/([octop|vir])i$/",
            "/(alias|status)es$/",
            "/^(ox)en/",
            "/(vert|ind)ices$/",
            "/(matr)ices$/",
            "/(quiz)zes$/"
        ];
        $singularReplace = [
            "",
            "$1ews",
            "$1um",
            "$1$2sis",
            "$1sis",
            "$1fe",
            "$1",
            "$1",
            "$1f",
            "$1y",
            "$1eries",
            "$1ovie",
            "$1",
            "$1ouse",
            "$1",
            "$1",
            "$1",
            "$1is",
            "$1us",
            "$1",
            "$1",
            "$1ex",
            "$1ix",
            "$1"
        ];
        return preg_replace($singularPattern, $singularReplace, $str);
    }
}