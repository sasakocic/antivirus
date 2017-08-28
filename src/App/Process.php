<?php

namespace App;

class Process
{
    const VIRUSES_SH = 'viruses.sh';
    const VIRUSES_CSV = 'viruses.csv';

    /**
     * Process virus report
     */
    public static function do()
    {
        $report = new Report();
        $report->loadFromCsv(self::VIRUSES_CSV);
        $output = implode(PHP_EOL, self::createRenames($report->items));
        $result = file_put_contents(self::VIRUSES_SH, $output);

        return (bool) $result;
    }

    /**
     * Create renames.
     *
     * @param Item[] $items
     *
     * @return array
     */
    public static function createRenames(array $items)
    {
        $renames = [];
        foreach ($items as $item) {
            $renames[$item->file] = $item->prettify();
        }
        ksort($renames);
        $moves = [];
        foreach ($renames as $old => $new) {
            $moves[] = 'mv "' . $old . '" "' . $new . '"';
        }

        return $moves;
    }
}
