<h1> EXAMPLE USAGE </h1>
<?php include ('BibleGateway.class.php');
$bible = new BibleGateway('KJV');
$bible->searchPassage('John 3:1-17');
echo $bible->text;
$bible->reference = 'John 1:1';
echo '<br>';
echo $bible->reference;
echo $bible->permalink;
echo $bible->text;
echo '<br>';
$bible->getVerseOfTheDay();
echo $bible->text;
echo $bible->permalink;