<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();


$dbHost = 'localhost';
$dbName = 'denemee';
$dbUser = 'root';
$dbPass = '';


try{
$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$app->get('/posts/', function (Request $request, Response $response, $args) use ($db) {
    $stmt = $db->query("SELECT * FROM posts");
    $posts = $stmt->fetchAll();
    return $response->withJson($posts);
});


$app->get('/comments/', function (Request $request, Response $response, $args) use ($db) {
    $stmt = $db->query("SELECT * FROM comments");
    $comments = $stmt->fetchAll();
    return $response->withJson($comments);
});


$app->get('/posts/{post_id}/comments', function (Request $request, Response $response, $args) use ($db) {
    $postId = $args['post_id'];
    $stmt = $db->prepare("SELECT * FROM comments WHERE postId = ?");
    $stmt->execute([$postId]);
    $comments = $stmt->fetchAll();
    return $response->withJson($comments);
});
}catch(PDOException $e){
    echo "Error:" . $e->getMessage();
}

$app->run();
?>
