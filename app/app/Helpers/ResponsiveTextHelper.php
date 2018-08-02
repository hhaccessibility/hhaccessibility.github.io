<?php

namespace App\Helpers;

class ResponsiveTextHelper
{
    public static function build()
    {
        return app(ResponsiveTextHelper::class);
    }

    public function getClassesFor(string $text)
    {
        // Replace multiple spaces with single spaces.
        $text = preg_replace('!\s+!', ' ', $text);
        $result = '';
        $len = strlen($text);
        for ($len_group = 40; $len_group < $len; $len_group += 20) {
            if ($result !== '') {
                $result .= ' ';
            }
            $result .= 'longer-than-' . $len_group;
        }
        return $result;
    }
}
