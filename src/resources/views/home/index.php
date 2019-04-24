<?php $slides = [
    (object) ['img' => 'https://picsum.photos/id/277/1920/620'],
    (object) ['img' => 'https://picsum.photos/id/278/1920/620'],
    (object) ['img' => 'https://picsum.photos/id/279/1920/620'],
    (object) ['img' => 'https://picsum.photos/id/280/1920/620']
]; ?>
<content title="Home" template="layout.default">
    <?= view('components.slider', ['slides' => $slides]) ?>
    <div class="container">
        <h1 class="title title--divider">Featured films</h1>
        <?= view('components.film-list', ['items' => $films, 'colWidth' => 3]) ?>
        <h1 class="title title--divider">Classic films</h1>
        <?= view('components.film-list', ['items' => $classics, 'colWidth' => 3]) ?>
    </div>
</content>