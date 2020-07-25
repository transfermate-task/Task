<?php

namespace src;

class ApiHtmlResponse
{
    /**
     * @param array $result
     * @return string
     */
    public static function getBookResults(array $result): string
    {
        $response = '<table>';
        if (empty($result)) {
            $response .= '<tr><th>No Results</th></tr>';
        } else {
            $response .= '<tr><th>ID</th><th>Name</th><th>Author</th><th>Updated At</th></tr>';
        }

        foreach ($result as $item) {
            $response .= '<tr><td>' . $item['id'] . '</td><td>' . $item['name'] . '</td><td>' . $item['author'] . '</td><td>' . $item['updated_at'] . '</td></tr>';
        }
        $response .= '<table>';

        return $response;
    }
}