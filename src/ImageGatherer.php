<?php

namespace KevinAllenBriggs\SlimFig;

class ImageGatherer
{
    const EXTENSION_WHITELIST = ['jpeg', 'jpg', 'png', 'gif'];

    private \RecursiveIteratorIterator $iterator;

    private string $path = '';

    public function __construct(string $path)
    {
        $this->path = $path;

        $this->iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path)
        );
    }

    public function collect(int $offset, int $length) {

        $images = [];
        $index = 1;

        foreach ($this->iterator as $key => $file) {
            if (! $file->isFile()) continue;

            if ($index < $offset) continue;

            if ($index > $offset + $length) continue;

            if (! in_array(strtolower($file->getExtension()), SELF::EXTENSION_WHITELIST)) continue;

            $images[] = $file->getFilename();
            $index++;
        }

        return $images;
    }
}