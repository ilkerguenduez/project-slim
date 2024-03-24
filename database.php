<?php
use Slim\Factory\AppFactory;

require __DIR__.'/vendor/autoload.php';

$dbHost = 'localhost';
$dbName = 'denemee';
$dbUser = 'root';
$dbPassword = '';

try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents(__DIR__.'/database.sql');

    $db->exec($sql);
    echo "Tablolar oluşturuldu.\n";

    
    $postsdata = file_get_contents('https://jsonplaceholder.typicode.com/posts');
    $posts = json_decode($postsdata,true);
    foreach ($posts as $post){
        $stmt = $db->prepare("INSERT INTO posts (id,user_id,title,body) VALUES (?,?,?,?)");
        $stmt->execute([$post['id'],$post['userId'],$post['title'],$post['body']]);
    }

    
    $commentsdata = file_get_contents('https://jsonplaceholder.typicode.com/comments');
    $comments = json_decode($commentsdata,true);
    foreach ($comments as $comment){
        $stmt = $db->prepare("INSERT INTO comments (id,post_id,name,email,body) VALUES (?,?,?,?,?)");
        $stmt->execute([$comment['id'],$comment['postId'],$comment['name'],$comment['email'],$comment['body']]);
    }

    echo "Veriler veritabanına aktarıldı.\n";
} catch(PDOException $e){
    echo "Error:" . $e->getMessage();
}
?>
