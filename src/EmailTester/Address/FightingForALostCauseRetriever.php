<?php
/**
 * Services that retrieve emailaddress from http://fightingforalostcause.net/misc/2006/compare-email-regex.php
 *
 * PHP version 5.4
 *
 * @category   EmailTester
 * @package    Address
 * @author     Pieter Hordijk <info@EmailTester.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    1.0.0
 */
namespace EmailTester\Address;

class FightingForALostCauseRetriever implements Retriever
{
    /**
     * Constant defining the URL to parse
     */
    const URL = 'https://fightingforalostcause.net/content/misc/2006/compare-email-regex.php';

    /**
     * Retrieves the addresses from a website
     *
     * @return array List of the retrieved emailaddresses
     * @throws \EmailTester\Address\BadResponseException
     */
    public function retrieve()
    {
        $html = file_get_contents(self::URL);

        if ($html === false || !$this->isValidRequest($http_response_header)) {
            throw new BadResponseException('Error while trying to retrieve emailaddresses from: `' . self::URL . '`');
        }

        return $this->parseHtml($html);
    }

    /**
     * Checks whether the request was valid
     *
     * @param array The contents of `$http_response_header` (the response header
     *              of the `file_get_contents` HTTP wrapper call)
     *
     * @return boolean True if the request was valid
     */
    private function isValidRequest(array $header)
    {
        if (!isset($header[0])) {
            return false;
        }

        return $header[0] === 'HTTP/1.1 200 OK';
    }

    /**
     * Converts the HTML string into a DOM document
     *
     * @param string $html The HTML of the page
     *
     * @return array List of emailaddresses
     */
    private function parseHtml($html)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        return $this->getAddresses($dom);
    }

    /**
     * Gets the addresses from the DOM
     *
     * @return array List of emailaddresses
     */
    private function getAddresses(\DOMDocument $dom)
    {
        $addresses = [
            'valid' => [],
            'invalid' => [],
        ];

        $xpath = new \DOMXPath($dom);

        $type = 'valid';
        foreach ($xpath->query("//table[@id='results']/tr/td[1]") as $node) {
            if ($node->textContent == 'These should be invalid') {
                $type = 'invalid';
                continue;
            } elseif ($node->textContent == 'These should be valid' || !trim($node->textContent)) {
                continue;
            }

            $addresses[$type][] = trim($node->textContent);
        }

        return $addresses;
    }
}
