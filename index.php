<?php

use src\BookDB;
use src\PostgreSQLConnection;
use src\DirectoryParser;

require_once 'autoloader.php';

try {
    $configs = parse_ini_file('config/config.ini');
    if ($configs === false) {
        throw new \Exception("Error reading parameters from configuration file");
    }

    $directoryParser = new DirectoryParser();

    //Parse directory content and insert to database
    if ($content = $directoryParser->parseDirectoryContent($configs['directory'])) {
        $pdo = PostgreSQLConnection::getConnection()->connect();
        $bookDB = new BookDB($pdo);

        //content should be displayed as a result
        foreach ($content as $filename => $books) {
            echo 'Filename: ' . $filename . '<br><br>';

            foreach ($books as $key => $book) {
                $bookDB->updateOrInsert($book['name'], $book['author']);

                echo 'Book id: ' . $key . '<br>';
                echo 'Name: ' . $book['name'] . '<br>';
                echo 'Author: ' . $book['author'] . '<br><br>';
            }
            echo '<hr>';
        }
    }
} catch (\Exception $exception) {
    echo '<h1>' . $exception->getMessage() . '</h1>';
}
