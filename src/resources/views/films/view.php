<?php $filmtitle = $film->filmtitle ?>
<content title="<?= $filmtitle ?>" template="layout.default">
    <div class="row">
        <div class="col--8/12 offset--1/12">
            <div class="container">
                <div class="content">
                    <h1 class="title title--divider"><?= $film->filmtitle ?></h1>
                    <p class="rating" title="<?= $rating; ?> stars"><?= str_repeat('★', $rating) ?><span class="disabled"><?= str_repeat('★', 5 - $rating) ?></span> (<?= $rating ?>)</p>
                    <p><?= $film->filmdescription ?></p>

                    <form action="/basket/add" method="post">
                        <input type="hidden" name="filmid" value="<?= $film->filmid ?>">
                        <button type="submit" class="btn" data-basket-add="<?= $film->filmid ?>">Add to basket &#187;</button>
                    </form>
                </div>

                <div class="content">
                    <h3>Rating</h3>
                    This film has been rated <?= $film->filmrating ?>.
                </div>

                <h1 class="title title--divider">Related</h1>
                <?= view('components.film-list', ['items' => $related, 'colWidth' => 3]) ?>
            </div>
        </div>
        <div class="col--4/12">
            <div class="img-overlay" style="position:fixed; top: 0; bottom: 0; z-index: -1; background: linear-gradient(#000, #474747, #000);">
                <img src="<?= 'https://res.cloudinary.com/dvsvyssoa/image/upload/w_600,h_1100,c_fill,e_blur:1000/v1555973011/mvc/films/' . slug($film->filmtitle ?? 'placeholder') . '.jpg' ?>" alt="">
                <img src="<?= 'https://res.cloudinary.com/dvsvyssoa/image/upload/w_300,q_auto/v1555973011/mvc/films/' . slug($film->filmtitle ?? 'placeholder') . '.jpg' ?>" align="<?= $film->filmtitle ?>" />
            </div>
        </div>
    </div>
</content>