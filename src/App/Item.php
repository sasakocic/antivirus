<?php

namespace App;

class Item
{
    /** @var string */
    public $file;
    /** @var string */
    public $dirname;
    /** @var string */
    public $extension;
    /** @var array */
    private $fields;
    /** @var array */
    public $filename;
    /** @var array */
    public $virusInfo;
    /** @var array */
    public $newFilename;
    /** @var boolean */
    public $isVirus;

    /**
     * Item constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->parse($fields);
    }

    /**
     * @return string
     */
    private function getFilename()
    {
        $filename = $this->fields[3];
        $packed = strpos($filename, '//');
        $end = $packed
            ? $packed
            : strlen($filename);
        $filename = substr($filename, 0, $end);
        $filename = str_replace('\\', '/', $filename);
        $filename = str_replace('//synology/', '/volume1/', $filename);

        return $filename;
    }

    /**
     * @param array $fields
     */
    private function parse($fields)
    {
        $this->fields = $fields;
        $this->file = $this->getFilename();
        $this->parseFilename();
        $this->virusInfo = $this->getVirusInfo();
        $this->isVirus = !isset($this->fields[5]);;
    }

    /**
     * @return string
     */
    private function getVirusInfo()
    {
        $virusInfo = $this->fields[4];
        $afterTwoDots = strpos($virusInfo, ':')
            ? strpos($virusInfo, ':') + 1
            : 0;
        $virusInfo = substr($virusInfo, $afterTwoDots);
        $virusInfo = '(' . $virusInfo . ')';

        return $virusInfo;
    }

    /**
     * Parse filename to parts.
     */
    private function parseFilename()
    {
        $pathInfo = pathinfo($this->file);
        $this->dirname = $pathInfo['dirname'];
        $this->filename = $pathInfo['filename'];
        $this->extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
    }

    /**
     *
     * @return string
     */
    public function prettify()
    {
        $suffix = $this->isVirus
            ? '.virus'
            : '';
        $newName = $this->dirname . '/';
        $newName .= str_replace('.virus', '', $this->filename);
        $newName .= $this->virusInfo . $suffix . '.' . $this->extension;

        return $newName;
    }
}
