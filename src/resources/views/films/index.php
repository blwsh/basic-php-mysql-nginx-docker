<content title="Films" template="layout.default">
    <div class="container">
        <h1 class="title title--divider">All films</h1>
        <?= view('components.film-list', ['items' => $films, 'colWidth' => 3]) ?>
    </div>
</content>