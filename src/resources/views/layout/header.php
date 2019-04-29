<div class="header-container">
    <header class="site-header">
        <div class="header__logo display--circle bg--primary">
            <a href="<?= url('/') ?>">
                <img src="https://www.hud.ac.uk/media/universityofhuddersfield/styleassets/images/2016homepageimages/uoh-logo-2019-white.svg" alt="MVC Framework" class="header__logo__img">
            </a>
        </div>

        <nav class="header__nav">
            <ul class="nav">
                <li class="nav__item"><a href="<?= url('/') ?>">Home</a></li>
                <li class="nav__item"><a href="<?= url('/films') ?>">Films</a></li>
                <li class="nav__item"><a href="<?= url('/shops') ?>">Shops</a></li>
                <li class="nav__item"><a href="<?= url('/about') ?>">About</a></li>
            </ul>
        </nav>

        <div class="header__actions">
            <ul class="nav nav--actions">
                <li class="nav__item"><a href="<?= url('/account') ?>" title="Account">👤</a></li>
                <li class="nav__item"><span id="basket-counter" class="basket-counter"></span> <a id="basket-button" href="javascript:void(0)" title="Basket">🛒 <span data-data-basket-count></span></a></li>
            </ul>
        </div>
    </header>

    <div id="basket-container" class="basket"></div>

    <div class="site-search">
        <label for="q"><input type="text" name="q" placeholder="Search" autocomplete="off"></label>
        <div class="site-search__results"></div>
    </div>
</div>
