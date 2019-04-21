<content title="<?php echo $hello ?>" template="layout.default">
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
            <img src="<?php echo $film->img; ?>" alt="<?php echo $film->title; ?>" class="card__img">
            <h2 class="card__title"><?php echo $film->title; ?></h2>
            <p class="card__rating" title="<?php echo $film->rating; ?> stars"><?php str_repeat('★', $film->ratiung); ?></p>
            <a href="<?php echo $film->slug; ?>" class="card__button">Book now</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <h1 class="title title--divider">Classic films</h1>

    <?php if (!empty($classics)): ?>
        <div class="featured-films flex">
        <?php foreach ($classics as $film): ?>
            <div class="card card--film">
            <img src="<?php echo $film->img; ?>" alt="<?php echo $film->title; ?>" class="card__img">
            <h2 class="card__title"><?php echo $film->title; ?></h2>
            <p class="card__rating" title="<?php echo $film->rating; ?> stars"><?php str_repeat('★', $film->ratiung); ?></p>
            <a href="<?php echo $film->slug; ?>" class="card__button">Book now</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</content>