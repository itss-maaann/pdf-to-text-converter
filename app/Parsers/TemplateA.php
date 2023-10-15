<?php

namespace App\Parsers;

use App\Contracts\TemplateParserInterface;

class TemplateA implements TemplateParserInterface
{
    const COMPANY_NAME_LINE = 0;
    const BUSINESS_LOCATION_LINE = 1;
    const DATE_OF_INVOICE_LINE = 12;
    const INVOICE_NUMBER_LINE = 11;
    const CUSTOMER_NUMBER_LINE = 18;
    const GST_AMOUNT_LINE = 353;
    const TOTAL_AMOUNT_LINE = 374;

    public function parse(array $lines)
    {
        $data = [];

        if ($this->isValidTemplate($lines)) {
            $data['Company Name'] = trim($lines[self::COMPANY_NAME_LINE]);
            $businessLocation = strstr($lines[self::BUSINESS_LOCATION_LINE], 'Phone:', true);
            $data['Business Location'] = trim($businessLocation);
            $data['Date of Invoice'] = $this->extractDate($lines[self::DATE_OF_INVOICE_LINE]);
            $data['Invoice No'] = $this->extractInvoiceNumber($lines[self::INVOICE_NUMBER_LINE]);
            $data['Customer No'] = trim($lines[self::CUSTOMER_NUMBER_LINE]);
            $data['GST Amount in Invoice'] = $this->extractFloatValue($lines[self::GST_AMOUNT_LINE]);
            $data['Total Amount of Invoice'] = $this->extractFloatValue($lines[self::TOTAL_AMOUNT_LINE]);
        }
        return $data;
    }

    private function isValidTemplate(array $lines): bool
    {
        return isset(
            $lines[self::COMPANY_NAME_LINE],
            $lines[self::BUSINESS_LOCATION_LINE],
            $lines[self::DATE_OF_INVOICE_LINE],
            $lines[self::INVOICE_NUMBER_LINE],
            $lines[self::CUSTOMER_NUMBER_LINE],
            $lines[self::GST_AMOUNT_LINE],
            $lines[self::TOTAL_AMOUNT_LINE]
        );
    }

    private function extractDate(string $line): string
    {
        preg_match('/(\d{1,2}\/\d{1,2}\/\d{4})/', $line, $matches);
        return isset($matches[1]) ? $matches[1] : '';
    }

    private function extractInvoiceNumber(string $line): string
    {
        return trim(str_replace('Invoice Number:', '', $line));
    }

    private function extractFloatValue(string $line): float
    {
        preg_match('/[\d,.]+/', $line, $matches);
        $value = isset($matches[0]) ? str_replace(',', '', $matches[0]) : '0';
        return floatval($value);
    }
}
