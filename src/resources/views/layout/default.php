<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><yield value="title"></yield></title>
    <?= view('layout.head') ?>
</head>
<body>
    <?= view('layout.header') ?>
    <main class="app">
        <yiled value="content"></yiled>
    </main>
    <?= view('layout.footer') ?>
    <?= view('layout.scripts') ?>
</body>
</html>
