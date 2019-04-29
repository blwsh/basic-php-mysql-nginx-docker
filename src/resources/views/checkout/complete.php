<content title="Checkout" template="layout.default">
    <div class="container">
        <div class="content">
            <h1 class="title title--divider">Checkout</h1>

            <?= view('components.errors', ['errors' => $errors]) ?>

            <form action="<?= url('/checkout/submit') ?>" method="post" class="row">
                <div class="col col--6/12--md">
                    <h4>Payment Details</h4>

                    <label for="name">
                        <span class="label">Name on card</span>
                        <input type="text" name="name" placeholder="John Smith" required>
                    </label>

                    <label for="card_number" class="mb">
                        <span class="label">Card number</span>
                        <input type="number" name="card_number" placeholder="0000 0000 0000 0000" required>
                        A valid card number is required. It is used to identify the card type. You can <a href="https://www.paypalobjects.com/en_AU/vhelp/paypalmanager_help/credit_card_numbers.htm" target="_blank">use this link to get a test card that will work</a>.
                    </label>

                    <div class="row">
                        <label for="expiry_month" class="col col--6/12--md">
                            <span class="expiry_month">Month</span>
                            <select name="expiry_month" id="expiry_month">
                                <option value="" disabled selected>Select month</option>
                                <?= array_map(function($month) { echo "<option value='" . sprintf("%02d", $month) . "'>" . sprintf("%02d", $month) . "</option>"; }, range(1, 12)) ?>
                            </select>
                        </label>

                        <label for="expiry_year" class="col col--6/12--md">
                            <span class="expiry_year">Year</span>
                            <select name="expiry_year" id="expiry_year">
                                <option value="" disabled selected>Select year</option>
                                <?= array_map(function($year) { echo "<option value='$year'>" . (2000 + $year) . "</option>"; }, range(date('y'), date('y') + 10)) ?>
                            </select>
                        </label>
                    </div>

                    <h4>Address</h4>

                    <label for="street">
                        <span>Address</span>
                        <input type="text" placeholder="Address line 1, line 2, line 3..." name="street" required>
                    </label>

                    <div class="row">
                        <label for="city" class="col col--6/12--md">
                            <span>City</span>
                            <input type="text" name="city" required>
                        </label>

                        <label for="postcode" class="col col--6/12--md">
                            <span>Postcode</span>
                            <input type="text" name="postcode" required>
                        </label>
                    </div>
                </div>
                <div class="col col--5/12--md offset--1/12--md offset--border border--shadow--left">
                    <div class="bg--lighter br">
                        <div class="card__body">
                            <h3 class="title title--divider">&#128274; Secure payment</h3>

                            <table class="mb">
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text--right">&pound;<?= number_format($subtotal, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Delivery</td>
                                        <td class="text--right">&pound;<?= number_format(3.99, 2) ?></td>

                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td class="text--right"><strong>&pound;<?= number_format($subtotal + 3.99, 2) ?></strong></td>
                                    </tr>
                                </tr>
                            </table>

                            <div class="text--center">
                                <small class="block"><a href="<?= url('/films') ?>">Continue shopping</a></small>
                                <button class="btn btn--wide">Pay now</button>
                                <div><img src="<?= url('assets/img/cards.png') ?>" alt="Accepted cards" width="120"></div>
                            </div>
                        </div>
                    </div>

                    <p class="text--center"><small>Standard delivery charge is &pound;3.99 and should arrive within two working days.</small></p>
                </div>
            </form>
        </div>
</content>