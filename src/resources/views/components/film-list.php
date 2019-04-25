<?php if (!empty($items)): ?>
<div class="product-list">
    <div class="row">
        <?php foreach ($items as $data): ?>
        <div class="col col--<?= $colWidth ?? 4 ?>/12--md">
            <?= view('components.card', [
                'title' => $data->filmtitle,
                'description' => $data->filmdescription,
                'slug' => '/films/' . $data->filmid,
                'img' => 'https://res.cloudinary.com/dvsvyssoa/image/upload/w_300,q_auto/v1555973011/mvc/films/' . slug($data->filmtitle ?? 'placeholder') . '.jpg',
                'rating' => rand(1, 5),
                'button_label' => 'Order now',
                'overlay' => true
            ]); ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

