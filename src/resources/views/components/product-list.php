<?php if (!empty($items)): ?>
<div class="product-list">
    <div class="row">
        <?php foreach ($items as $data): ?>
        <div class="col col--<?= $colWidth ?? 4 ?>/12">
            <a href="<?= $data->slug; ?>" class="card">
                <div class="card__img">
                    <img src="<?= $data->img; ?>" alt="<?= $data->title; ?>">
                </div>
                <h2 class="card__title"><?= $data->title; ?></h2>
                <p class="card__description">A short description about the film. Information about the actors, directors or the story can go in this section.</p>
                <p class="card__rating" title="<?= $data->rating; ?> stars"><?= str_repeat('★', $data->rating) ?><span class="disabled"><?= str_repeat('★', 5-$data->rating) ?></span> (<?= $data->rating ?>)</p>
                <span class="btn card__btn">Book now</span>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>