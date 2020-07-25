<?php

namespace src;

class DirectoryParser
{
    /**
     * @var array
     */
    private $content = [];

    /**
     * @param string $directory
     * @return array
     * @throws \Exception
     */
    public function parseDirectoryContent(string $directory): array
    {
        if (file_exists($directory)) {
            $rit = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $directory,
                    \FilesystemIterator::KEY_AS_PATHNAME |
                    \FilesystemIterator::CURRENT_AS_FILEINFO |
                    \FilesystemIterator::SKIP_DOTS
                ),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($rit as $path => $fileinfo) {
                if (!$fileinfo->isDir()) {
                    $this->getFileContentAsArray($path);
                }
            }

            return $this->content;
        } else {
            throw new \Exception('No such folder!');
        }
    }

    /**
     * @param string $path
     * @throws \Exception
     */
    private function getFileContentAsArray(string $path)
    {
        try {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_file($path);
            if (!$xml) {
                throw new \Exception(' ---   Not Valid XML in '.$path.'!   ---');
            }
            foreach ($xml->children() as $book) {
                $this->content[$path][] = ['author' => (string)$book->author, 'name' => (string)$book->name];
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}