<?php

namespace App;

/**
 * Antivirus report class.
 */
class Report
{
    /** @var Item[] */
    public $items = [];

    /**
     * Load from CSV file.
     *
     * @param string $filename
     */
    public function loadFromCsv($filename)
    {
        if (!is_readable($filename)) {
            throw new \RuntimeException('File missing ' . $filename);
        }
        $handle = fopen($filename, 'r');
        $this->items = [];
        while(!feof($handle)) {
            $fields = fgetcsv($handle, 0, ';', '"');
            if (is_array($fields) && $fields[1] !== 'Selective Scan') {
                $this->items[] = new Item($fields);
            }
        }
        fclose($handle);
    }
}
