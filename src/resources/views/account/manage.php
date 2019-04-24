<content title="My Account" template="layout.default">
    <div class="container">
        <div class="content">
            <h1 class="title title--divider">My Account</h1>
            <strong>Welcome back, <?= $customer->personname ?></strong>
            <p>This page provides you with an overview of your account details, past purchase and other information.</p>

            <h2>Account</h2>
            <div class="row">
                <div class="col col--4/12">
                    <label for="personname">
                        <span class="label">Name</span>
                        <input name="personname" type="text" value="<?= htmlentities($customer->personname) ?>">
                    </label>
                </div>
                <div class="col col--4/12">
                    <label for="personphone">
                        <span class="label">Telephone</span>
                        <input name="personphone" type="tel" value="<?= htmlentities($customer->personphone) ?>">
                    </label>
                </div>
                <div class="col col--4/12">
                    <label for="personemail">
                        <span class="label">Email</span>
                        <input name="personemail" type="email" value="<?= htmlentities($customer->personemail) ?>">
                    </label>
                </div>
            </div>

            <h2>Purchases</h2>
            <?= view('components.payments', ['payments' => $payments]) ?>

            <h2>Account actions</h2>
            <form action="/auth/logout" method="post">
                <button type="submit" class="btn">Logout</button>
            </form>
        </div>
    </div>
</content>