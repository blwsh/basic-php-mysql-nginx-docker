<content title="My Account" template="layout.default">
    <div class="container">
        <div class="content">
            <h1 class="title title--divider">My Account</h1>
            <strong>Welcome back, <?= $customer->personname ?>.</strong>
            <p>This page provides you with an overview of your account details, past purchase and other information.</p>

            <h2>Account</h2>

            <?= view('components.errors', ['errors' => $errors]) ?>

            <form  action="<?= url('/account/update') ?>" method="post" class="row">
                <div class="col col--3/12--sm">
                    <label for="personname">
                        <span class="label">Name</span>
                        <input name="personname" type="text" value="<?= htmlentities($customer->personname) ?>">
                    </label>
                </div>
                <div class="col col--3/12--sm">
                    <label for="personphone">
                        <span class="label">Telephone</span>
                        <input name="personphone" type="tel" value="<?= htmlentities($customer->personphone) ?>">
                    </label>
                </div>
                <div class="col col--3/12--sm">
                    <label for="personemail">
                        <span class="label">Email</span>
                        <input name="personemail" type="email" value="<?= htmlentities($customer->personemail) ?>">
                    </label>
                </div>

                <div class="col col--3/12--sm">
                    <label for="submit">
                        <span class="label hidden" aria-hidden="true">Update your account</span>
                        <button type="submit" name="submit" class="btn btn--block btn--wide">Update</button>
                    </label>
                </div>
            </form>

            <h2>Purchases</h2>
            <?= view('components.payments', ['payments' => $payments]) ?>

            <h2>Addresses</h2>
            <?php foreach ($addresses as $address): ?>
            <address>
                <?= $address->addstreet ?>
                <?= $address->addcity ?>
                <?= $address->addpostcode ?>
            </address>
            <?php endforeach; ?>

            <h2>Account actions</h2>
            <form action="<?= url('/auth/logout') ?>" method="post">
                <button type="submit" class="btn">Logout</button>
            </form>
        </div>
    </div>
</content>