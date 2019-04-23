<content title="Shop" template="layout.default">
    <div class="container">
        <h2>Shops</h2>

        <div class="row">
        <?php foreach ($shops as $shop): ?>
            <div class="col col--4/12">
                <div class="card">
                    <div class="card__body">
                        <h2 class="card__title"><?= $shop->shopname; ?></h2>
                        <p class="card__description"><?= $shop->shopphone; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</content>