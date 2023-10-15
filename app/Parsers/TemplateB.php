<?php

namespace App\Parsers;

use App\Contracts\TemplateParserInterface;

class TemplateB implements TemplateParserInterface
{
    const COMPANY_NAME_INDEX = 2;
    const BUSINESS_LOCATION_LINE = 12;
    const DATE_OF_INVOICE_INDEX = 6;
    const INVOICE_NO_INDEX = 6;
    const CUSTOMER_NO_INDEX = 14;
    const GST_AMOUNT_INDEX = 144;
    const TOTAL_AMOUNT_INDEX = 43;

    public function parse(array $lines)
    {
        $data = [];

        if ($this->isValidTemplate($lines)) {
            $data['Company Name'] = trim(explode(' LTD', $lines[self::COMPANY_NAME_INDEX])[0]);
            $data['Business Location'] = trim($lines[self::BUSINESS_LOCATION_LINE]);
            $data['Date of Invoice'] = trim(explode('INVOICE DATE:', $lines[self::DATE_OF_INVOICE_INDEX])[1]);
            $data['Invoice No'] = $this->extractInvoiceNumber($lines[self::INVOICE_NO_INDEX]);
            $data['Customer No'] = trim(explode('Customer No.', $lines[self::CUSTOMER_NO_INDEX])[1]);
            $data['GST Amount in Invoice'] = floatval($lines[self::GST_AMOUNT_INDEX]);
            $data['Total Amount of Invoice'] = floatval(explode('Total Due', $lines[self::TOTAL_AMOUNT_INDEX])[1]);
        }
        return $data;
    }

    private function isValidTemplate(array $lines): bool
    {
        return isset(
            $lines[self::COMPANY_NAME_INDEX],
            $lines[self::DATE_OF_INVOICE_INDEX],
            $lines[self::INVOICE_NO_INDEX],
            $lines[self::CUSTOMER_NO_INDEX],
            $lines[self::GST_AMOUNT_INDEX],
            $lines[self::TOTAL_AMOUNT_INDEX]
        );
    }

    private function extractInvoiceNumber(string $line): string
    {
        preg_match('/(\d+)/', $line, $matches);
        return isset($matches[0]) ? $matches[0] : '';
    }
}
