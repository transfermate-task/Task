<?php

use src\ApiHtmlResponse;
use src\BookDB;
use src\PostgreSQLConnection;

require_once 'autoloader.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (array_key_exists('author', $_GET)) {
            $pdo = PostgreSQLConnection::getConnection()->connect();
            $bookDB = new BookDB($pdo);
            if(!$_GET['author']){
                echo ApiHtmlResponse::getBookResults($bookDB->all());
            }else{
                echo ApiHtmlResponse::getBookResults($bookDB->findBy(['author'=> trim($_GET['author'])]));
            }
        } else {
            throw new Exception('Author parameter is required!');
        }
    }else {
        throw new Exception('This request method is not allowed!');
    }
} catch (Exception $exception) {
    echo $exception->getMessage();
    http_response_code(404);
}
