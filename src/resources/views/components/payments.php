<?php if ($payments): ?>
<table>
    <thead>
        <tr>
            <th title="id"><strong>#</strong></th>
            <th>Film</th>
            <th>Price</th>
            <th>Rating</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($payments as $payment): ?>
        <tr>
            <td><?= $payment->payid ?></td>
            <td><h3><a href="<?= url('/films/' . $payment->filmid) ?>"><?= $payment->filmtitle ?></a></h3></td>
            <td>&pound;<?= $payment->price ?></td>
            <td><?= $payment->filmrating ?></td>
            <td><?= date("d/m/Y", strtotime($payment->paydate)) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>You currently have no purchase history.</p>
<?php endif; ?>