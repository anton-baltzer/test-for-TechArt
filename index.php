<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
        body{
            font-family: Arial, Helvetica, sans-serif;
            background: #ebede9;
        }
        .container{
            background: white;
            border-radius: 2px;
            margin: 20px auto;
            padding: 20px;
            width: 90%;
            border: 1px solid #cbcdca;
        }
        h1 {
            margin: 0 0 -10px 0;
        }
        h2{
            margin: 0;
        }
        .title_news{
            display: flex;
        }
        .title_news>p{
            font-size: 14px;
            background-color: #822b5a;
            color: white;
            padding: 3px 5px;
        }
        .title_news>a{
            margin: auto 10px;
        }
        .announce{
            font-size: 16px;
            margin-top:-12px
        }
        .pagination{
            display: grid;
            grid-template-areas:
          "1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28";
            text-align: center;
            grid-template-columns: repeat(28, 1fr);
        }
        .pagination>a{
            color: rgba(0, 0, 0, 0.83);
            background: #ebede9;
            border-radius: 2px;
            font-size: 12px;
            text-decoration: none;
            border: 1px solid #cccecb;
            margin: 2px;
        }
        .pagination>a:hover{
            background-color: rgba(130, 43, 90, 0.59);
            color: white;
        }
        a.active{
            background-color: #822b5a;
            color: white;
        }
        hr{
            margin-top:10px ;
            padding: 0;
            height: 0;
            border: none;
            border-top: 1px dotted #822b5a;
        }
    </style>
    <title>Document</title>
</head>
<body>
<div class="container">
<?php



$url=$_SERVER['REQUEST_URI'];
$segment = explode('?', $url);
$segment_value = explode('=', $segment[1]);

function innerDb($mysql){
    require 'pdoconfig.php';
    $out = [];
    $k = 0;
    try {
        $dbh =  new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        foreach($dbh->query($mysql) as $row) {
            $out[$k] = $row;
            $k++;
        }
        return $out;
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}

$maxNews = innerDb("SELECT count(*) FROM news")[0][0];

if($segment_value[0] == 'page' || $segment_value[0] == NULL){

$url_value = $segment_value[1];
if($url_value != '')$url_value = $url_value - 1;
$page = 5 * $url_value;
$out_news = innerDb("SELECT id, idate, title, announce FROM news ORDER BY idate DESC LIMIT $page, 5");

    echo '<h1>Новости</h1>';
    echo '<hr>';

for($i = 0; $i < count($out_news); $i++) {
    echo '<div class="title_news"><p>' . date("d.m.Y", $out_news[$i]['idate']). ' </p> ' . '<a href=/index.php?id=' . $out_news[$i]['id'] . '>' . $out_news[$i]["title"] . '</a></div><p class="announce">' . $out_news[$i]['announce'] . '</p>';
    if($out_news[$i]['id'] == '') break;
}
    echo "<hr>";
    echo "<h2>Страницы:</h2>";
$num = $maxNews / 5;
    echo '<div class="pagination">';
for($i = 1; $i < $num + 1; $i++){
    if($segment_value[1] == $i) echo "<a href='/index.php?page=$i' class='active'> $i </a>";
else echo "<a href='/index.php?page=$i'> $i </a>";
}
echo '</div>';
}
else{
    $out = innerDb("SELECT title, content FROM news WHERE id = $segment_value[1]");
    echo '<h2>'.$out[0]['title'].'</h2>';
    echo '<hr>';
    echo '<p>'.$out[0]['content'].'</p>';
    echo '<hr>';
    echo "<a href='/index.php?page=1'> Все новости >></a>";
}
?>
</div>

</body>
</html>