<?php

namespace CBEDU\Frontend\Helpers;

class Helpers
{
        public static function convert_marks_to_grade($marks)
    {
        if ($marks >= 80) {
            return array('A+', 5.00);
        } elseif ($marks >= 70) {
            return array('A', 4.00);
        } elseif ($marks >= 60) {
            return array('A-', 3.50);
        } elseif ($marks >= 50) {
            return array('B', 3.00);
        } elseif ($marks >= 40) {
            return array('C', 2.00);
        } elseif ($marks >= 33) {
            return array('D', 1.00);
        } else {
            return array('F', 0.00);
        }
    }

}