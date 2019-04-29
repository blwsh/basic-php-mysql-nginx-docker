<?php $filmtitle = $film->filmtitle ?>
<content title="<?= $filmtitle ?>" template="layout.default">
    <div class="row">
        <div class="col col--4/12--lg">
            <div class="img-overlay">
                <img src="<?= 'https://res.cloudinary.com/dvsvyssoa/image/upload/w_600,h_1100,c_fill,e_blur:1000/v1555973011/mvc/films/' . slug($film->filmtitle ?? 'placeholder') . '.jpg' ?>" alt="">
                <img src="<?= 'https://res.cloudinary.com/dvsvyssoa/image/upload/w_300,q_auto/v1555973011/mvc/films/' . slug($film->filmtitle ?? 'placeholder') . '.jpg' ?>" align="<?= $film->filmtitle ?>" />
            </div>
        </div>
        <div class="col col--7/12--lg offset--1/12--lg first--lg">
            <div class="container">
                <div class="content">
                    <h1 class="title title--divider"><?= $film->filmtitle ?></h1>
                    <p class="rating" title="<?= $rating; ?> stars"><?= str_repeat('★', $rating) ?><span class="disabled"><?= str_repeat('★', 5 - $rating) ?></span> (<?= $rating ?>)</p>
                    <p><?= $film->filmdescription ?></p>

                    <?php if ($film->stocklevel): ?>
                    <p><?= $film->stocklevel ?> in stock</p>
                    <?php endif; ?>

                    <?= view('components.errors', ['errors' => $errors]) ?>

                    <form action="<?= url('/basket/add') ?>" method="post">
                        <input type="hidden" name="filmid" value="<?= $film->filmid ?>">
                        <button
                            type="submit"
                            class="btn"
                            data-basket-add="<?= $film->filmid ?>" <?= !$film->stocklevel ? 'disabled' : null ?>
                        >
                            <?= $film->stocklevel ? 'Add to basket &#187;' : 'Out of stock' ?>
                        </button>
                    </form>
                </div>

                <div class="content">
                    <h3>Rating</h3>
                    This film has been rated <?= $film->filmrating ?>.
                </div>

                <h1 class="title title--divider">Related</h1>
                <?= view('components.film-list', ['items' => $related, 'colWidth' => 4]) ?>
            </div>
        </div>
    </div>
</content>