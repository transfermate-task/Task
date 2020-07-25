<?php
use PHPUnit\Framework\TestCase;
use src\ApiHtmlResponse;

class ApiHtmlResponseTest extends TestCase
{
    public function testWhenResultIsEmpty()
    {
        require_once 'src\ApiHtmlResponse.php';
        $this->assertEquals('<table><tr><th>No Results</th></tr><table>', ApiHtmlResponse::getBookResults([]));
    }

    public function testBookResults()
    {
        $array[] = [
            'id' => '1',
            'name' => 'John',
            'author' => 'Snow',
            'updated_at' => '1',
        ];

        $expected = '<table><tr><th>ID</th><th>Name</th><th>Author</th><th>Updated At</th></tr><tr><td>1</td><td>John</td><td>Snow</td><td>1</td></tr><table>';
        $this->assertEquals($expected, ApiHtmlResponse::getBookResults($array));
    }
}