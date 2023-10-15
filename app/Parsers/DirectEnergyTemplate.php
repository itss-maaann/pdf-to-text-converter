<?php

namespace App\Parsers;

use App\Contracts\TemplateParserInterface;

class DirectEnergyTemplate implements TemplateParserInterface
{
    const COMPANY_NAME_LINE = 0;
    const BUSINESS_LOCATION_LINE = 15;
    const DATE_OF_INVOICE_LINE = 0;
    const INVOICE_NUMBER_LINE = 0;
    const CUSTOMER_NUMBER_LINE = 18;
    const GST_AMOUNT_LINE = 27;
    const TOTAL_AMOUNT_LINE = 31;

    public function parse(array $lines)
    {
        $data = [];
        $contractIds = [];

        if ($this->isValidTemplate($lines)) {
            $data['Company Name'] = 'Direct Energy';

            // Extract Business Location
            $businessLocationLine = $lines[self::BUSINESS_LOCATION_LINE];
            $businessLocation = $this->extractBusinessLocation($businessLocationLine);
            $data['Business Location'] = $businessLocation;

            $invoiceDateLine = $lines[self::DATE_OF_INVOICE_LINE];
            $invoiceDate = $this->extractInvoiceDate($invoiceDateLine);
            $data['Invoice Date'] = $invoiceDate;

            $invoiceNumberLine = $lines[self::INVOICE_NUMBER_LINE];
            $invoiceNumber = $this->extractInvoiceNumber($invoiceNumberLine);
            $data['Invoice No'] = $invoiceNumber;

            $contractIds = $this->extractContractIds($lines);
        }

        $data['Contract Ids'] = implode(', ', $contractIds);
        $data['GST Amount in Invoice'] = $this->extractFloatValueWithSymbole($lines[self::GST_AMOUNT_LINE]);
        $data['Total Amount of Invoice'] = $this->extractFloatValueWithSymbole($lines[self::TOTAL_AMOUNT_LINE]);

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

    private function extractBusinessLocation(string $line): string
    {
        $startIndex = strpos($line, 'Write to:') + strlen('Write to:');
        return trim(substr($line, $startIndex));
    }

    private function extractInvoiceDate(string $line): string
    {
        $dateParts = explode(' ', $line);
        return trim($dateParts[count($dateParts) - 2]);
    }

    private function extractInvoiceNumber(string $line): string
    {
        $invoiceParts = explode(' ', $line);
        return trim($invoiceParts[count($invoiceParts) - 3]);
    }

    private function extractContractIds(array $lines): array
    {
        $contractIds = [];

        foreach ($lines as $index => $line) {
            if (strpos($line, 'Contract Id') !== false) {
                $parts = preg_split('/\s+/', $line);
                foreach ($parts as $part) {
                    if (preg_match('/^C\d+$/', $part)) {
                        $contractIds[] = $part;
                    }
                }
            }
        }

        return $contractIds;
    }

    private function extractFloatValueWithSymbole(string $line): string
    {
        preg_match('/[\d,.]+/', $line, $matches);
        $value = isset($matches[0]) ? str_replace(',', '', $matches[0]) : '0';
        preg_match('/[^\d,.]+/', $line, $currencyMatches);
        $currencySymbol = isset($currencyMatches[0]) ? $currencyMatches[0] : '';

        $value = number_format(str_replace("\xc2\xa0", '', $value), 2);

        return trim($currencySymbol . $value);
    }
}
