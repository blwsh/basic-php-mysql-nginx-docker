<content title="Are you sure you want to delete your account?" template="layout.default">
    <div class="container">
        <div class="content text--center">
            <h1 class="title">Delete My Account</h1>
            <p>Are you sure you want to delete your account?</p>

            <div class="flex justify-center">
                <button class="btn" onclick="window.history.back()">Cancel</button>

                <form action="<?= url('/account/delete/confirm') ?>" method="post" class="ml">
                    <button type="submit" class="btn btn--warn">Delete My Account</button>
                </form>
            </div>
        </div>
    </div>
</content>