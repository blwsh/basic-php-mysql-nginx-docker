<?php $title = 'Films' . ($page ? ' (' . $page . '/' . ceil($count/$perPage) . ')' : null) ?>
<content title="<?= $title ?>" template="layout.default">
    <div class="container">
        <h1 class="title title--divider">All films</h1>
        <?= view('components.film-list', ['items' => $films, 'colWidth' => 4]) ?>

        <div class="padding">
            <?= view('components.pagination', ['scope' => 'films', 'count' => $count, 'perPage' => $perPage]) ?>
        </div>
    </div>
</content>