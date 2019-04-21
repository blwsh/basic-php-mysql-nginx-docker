<content title="<?= $hello ?>" template="layout.default">
    <div class="slider">
        <div class="slide">
            <img src="" alt="">
        </div>
        <div class="slide">
            <img src="" alt="">
        </div>
        <div class="slide">
            <img src="" alt="">
        </div>
    </div>

    <h1 class="title title--divider">Featured films</h1>

    <?php if (!empty($films)): ?>
    <div class="featured-films flex">
        <?php foreach ($films as $film): ?>
        <div class="card card--film">
            <img src="<?= $film->img; ?>" alt="<?= $film->title; ?>" class="card__img">
            <h2 class="card__title"><?= $film->title; ?></h2>
            <p class="card__rating" title="<?= $film->rating; ?> stars"><?php str_repeat('★', $film->ratiung); ?></p>
            <a href="<?= $film->slug; ?>" class="card__button">Book now</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <h1 class="title title--divider">Classic films</h1>

    <?php if (!empty($classics)): ?>
        <div class="featured-films flex">
        <?php foreach ($classics as $film): ?>
            <div class="card card--film">
            <img src="<?= $film->img; ?>" alt="<?= $film->title; ?>" class="card__img">
            <h2 class="card__title"><?= $film->title; ?></h2>
            <p class="card__rating" title="<?= $film->rating; ?> stars"><?php str_repeat('★', $film->ratiung); ?></p>
            <a href="<?= $film->slug; ?>" class="card__button">Book now</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</content>