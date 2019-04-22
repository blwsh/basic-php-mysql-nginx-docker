<?php
$slides = [
    (object) ['img' => 'https://picsum.photos/id/277/1920/620'],
    (object) ['img' => 'https://picsum.photos/id/278/1920/620'],
    (object) ['img' => 'https://picsum.photos/id/279/1920/620'],
    (object) ['img' => 'https://picsum.photos/id/280/1920/620']
];

$classics = [
    (object) ['title' => 'Title 1', 'img' => 'https://picsum.photos/id/77/420/520', 'rating' => 4],
    (object) ['title' => 'Title 2', 'img' => 'https://picsum.photos/id/78/420/520', 'rating' => 5],
    (object) ['title' => 'Title 3', 'img' => 'https://picsum.photos/id/79/420/520', 'rating' => 2],
    (object) ['title' => 'Title 4', 'img' => 'https://picsum.photos/id/80/420/520', 'rating' => 3]
];
?>
<content title="Home" template="layout.default">
    <?= view('components.slider', ['slides' => $slides]) ?>
    <div class="container">
        <h1 class="title title--divider">Featured films</h1>
        <?= view('components.film-list', ['items' => $films, 'colWidth' => 3]) ?>
        <h1 class="title title--divider">Classic films</h1>
        <?= view('components.film-list', ['items' => $classics, 'colWidth' => 3]) ?>
        <h1 class="title title--divider">2019 top picks</h1>
    </div>
</content>