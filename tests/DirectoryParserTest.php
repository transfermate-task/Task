<?php
use PHPUnit\Framework\TestCase;
use src\DirectoryParser;

class DirectoryParserTest extends TestCase
{
    public function testParseDirectoryContentWrongDirectory()
    {
        require_once 'src\DirectoryParser.php';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No such folder!');
        $parser = new DirectoryParser();

        $parser->parseDirectoryContent('directory');
    }

    public function testParseDirectoryContent()
    {
        require_once 'src\DirectoryParser.php';

        $configs = parse_ini_file('config/config.ini');

        $parser = new DirectoryParser();

        $this->assertIsArray($parser->parseDirectoryContent($configs['directory']));
    }
}