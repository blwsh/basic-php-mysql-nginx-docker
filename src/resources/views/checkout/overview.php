<content title="Review Basket" template="layout.default">
    <div class="container">
        <div class="content">
            <h1 class="title title--divider">My Basket</h1>

            <?php foreach ($items as $item): ?>
            <div class="checkout-item">
                <a href="<?= '/films/' . $item->item->filmid ?>">
                    <img src="<?= 'https://res.cloudinary.com/dvsvyssoa/image/upload/w_60,q_auto/v1555973011/mvc/films/' . slug($item->item->filmtitle ?? 'placeholder') . '.jpg' ?>" alt="<?= $item->item->filmtitle ?>">
                </a>

                <h3><?= $item->item->filmtitle ?></h3>
                <p><?= $item->item->filmdescription ?></p>

                <span><?= $item->quantity ?></span>

                <strong>&pound;<?= number_format($item->quantity * 9.99, 2) ?></strong>

                <div>
                    <form action="<?= url('/basket/remove') ?>" method="post">
                        <input type="hidden" name="filmid" value="<?= $item->item->filmid ?>">
                        <button type="submit" class="btn">-</button>
                    </form>

                    <form action="<?= url('/basket/add') ?>" method="post">
                        <input type="hidden" name="filmid" value="<?= $item->item->filmid ?>">
                        <button type="submit" class="btn">+</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="flex justify-space-between mt mb">
                <h4>Subtotal - &pound;<?= number_format($subtotal, 2) ?></h4>
                <h4>Delivery - &pound;<?= number_format(3.99, 2) ?></h4>
                <h4>Total - <strong>&pound;<?= number_format($subtotal + 3.99, 2) ?></strong></h4>
            </div>

            <div class="text--center">
                <a href="<?= url('/checkout/complete') ?>" class="btn btn--wide">Next step</a>
            </div>
        </div>
    </div>
</content>