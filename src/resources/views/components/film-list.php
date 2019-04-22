<?php if (!empty($items)): ?>
<div class="product-list">
    <div class="row">
        <?php foreach ($items as $data): ?>
        <div class="col col--<?= $colWidth ?? 4 ?>/12">
            <?= view('components.card', [
                'title' => $data->filmtitle,
                'description' => $data->filmdescription,
                'img' => '/assets/img/films/' . slug($data->filmtitle ?? 'placeholder') .'.jpg',
                'rating' => rand(1, 5),
                'button_label' => 'Order now',
                'overlay' => true
            ]); ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>