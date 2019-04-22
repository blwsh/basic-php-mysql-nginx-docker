<a href="<?= $slug; ?>" class="card <?= $overlay ? 'card--overlay' : null ?>">
    <?php if ($img): ?>
    <div class="card__img">
        <img src="<?= $img; ?>" alt="<?= $title; ?>">

        <?php if ($overlay): ?>
        <p class="card__description"><?= $description ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="card__body">
        <h2 class="card__title"><?= $title; ?></h2>
        <?php if (!$overlay): ?>
        <p class="card__description"><?= $description ?></p>
        <?php endif; ?>
    </div>

    <div class="card__actions">
        <p class="card__rating" title="<?= $rating; ?> stars"><?= str_repeat('★', $rating) ?><span class="disabled"><?= str_repeat('★', 5-$rating) ?></span> (<?= $rating ?>)</p>
        <span class="btn card__btn"><?= $button_label ?? 'Read More' ?></span>
    </div>
</a>
