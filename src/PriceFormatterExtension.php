<?php

namespace Marijnvdwerf\Peek;

use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;

class PriceFormatterExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('format_price', [$this, 'formatPrice'])
        ];
    }

    public function formatPrice($number, $currency)
    {
        $decimals = 2;
        switch ($currency) {
            case 'JPY':
                $decimals = 0;
                break;
        }


        $prefix = '';
        $suffix = '';
        switch ($currency) {
            case 'SEK':
                $suffix = ' kr.';
                break;
            case 'EUR':
                $prefix = '€ ';
                break;
            default:
                $suffix = ' ' . $currency;
        }


        $output = '';
        if ($number < 0) {
            $output .= '&minus;';
            $number = -$number;
        }

        $decimalSep = '<sup>';

        // Narrow, non-breaking space
        $thousandSep = '&#8239;';

        $output .= number_format($number, $decimals, $decimalSep, $thousandSep);

        if ($decimals > 0) {
            $output .= '</sup>';
        }


        return new Markup($prefix . $output . $suffix, 'UTF-8');

    }


}