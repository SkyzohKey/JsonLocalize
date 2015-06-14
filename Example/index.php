<?php
/**
*   Translation Example
*   @version   0.0.1
**/

$pagesPath = './pages/';
$pagesExt = '.inc.php';

$langsPath = './langs/';
$langsExt = '.json';
$defaultLang = 'en';

$includesPath = './includes/';
$includesExt = '.class.php';

$imagesPath = './images/';

require_once($includesPath.'JsonLocalizer'.$includesExt);
session_start();

$parser = new JsonLocalizer($langsPath, 'json', 'fr');

// ATTENTION: DIRTY CODE BESIDE.
// TODO: Change this piece of shit.
// Fast handle of lang.
if (isset($_GET['lang']))
{
    $from = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'home';
    $_SESSION['lang'] = htmlentities($_GET['lang']);
    //$parser->setLang($_SESSION['lang']);
    header('Location: ' . $from);
}
elseif (isset($_SESSION['lang']))
{
    $parser->setLang($_SESSION['lang']);
}
else
{
    $parser->setLang($defaultLang);
}

$parser->render(null, '<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{website.title}</title>
    </head>
    <body>
');
$parser->render(null, '{lang.current} {_lang.country} ({_lang.flag}) - <a href="?lang=fr">[fr]</a> <a href="?lang=en">[en]</a> <a href="?lang=es">[es]</a> <hr>');
$file = '';
if (isset($_GET) && !empty($_GET))
{
    if (array_key_exists('p', $_GET))
    {
        $file = $pagesPath.htmlentities($_GET['p']).$pagesExt;
        if (file_exists($file))
        {
            $get = (isset($_GET) && ! empty($_GET)) ? $_GET : [];
            $post = (isset($_POST) && ! empty($_POST)) ? $_POST : [];
            $parser->render($file);
        }
        else
        {
            $file = $pagesPath.'errors/404'.$pagesExt;
            $parser->render($file);
        }
    }
    else
    {
        $file = $pagesPath.'home'.$pagesExt;
        $parser->render($file);
    }
}
else
{
    $file = $pagesPath.'home'.$pagesExt;
    $parser->render($file);
}

$fileContent = file_get_contents($file);

$parser->render(null, '<p>
    <h2>{website.codeOfPage}</h2>');

echo '<pre><code>File: ' . $file . '

' . htmlentities($fileContent) . '</code></pre>
</p></body></html>';
