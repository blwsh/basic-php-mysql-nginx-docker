<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <!-- Meta  -->
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Favicons -->
    <link rel=apple-touch-icon sizes=180x180 href=//www.hud.ac.uk/media/recruitment/styleassets/assets/icons/xapple-touch-icon.png.pagespeed.ic.WIz2wcY8jF.webp>
    <link rel=icon type=image/png sizes=32x32 href=//www.hud.ac.uk/media/recruitment/styleassets/assets/icons/xfavicon-32x32.png.pagespeed.ic.VQs3W4zRUd.webp>
    <link rel=icon type=image/png sizes=192x192 href=//www.hud.ac.uk/media/recruitment/styleassets/assets/icons/xandroid-chrome-192x192.png.pagespeed.ic.KXHXbW6tza.webp>
    <link rel=icon type=image/png sizes=16x16 href=//www.hud.ac.uk/media/recruitment/styleassets/assets/icons/xfavicon-16x16.png.pagespeed.ic.U3j6-4xo_5.webp>
    <link rel=manifest href=//www.hud.ac.uk/media/recruitment/styleassets/assets/icons/site.webmanifest>
    <link rel=mask-icon href=//www.hud.ac.uk/media/recruitment/styleassets/assets/icons/safari-pinned-tab.svg color=#00adef>
    <link rel="shortcut icon" href=//www.hud.ac.uk/media/recruitment/styleassets/assets/icons/favicon.ico>

    <!-- Title -->
    <title><yield value="title"></yield> -  <?= config('site') ?></title>

    <!-- Other -->
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
