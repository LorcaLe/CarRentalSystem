<h2>My Bookings</h2>

<?php foreach($bookings as $booking): ?>

<div class="card">

<h3><?= $booking['name'] ?></h3>

<p>Pickup: <?= $booking['pickup_date'] ?></p>

<p>Return: <?= $booking['return_date'] ?></p>

<p>Status: <?= $booking['status'] ?></p>

<a class="btn-gold"
href="/cancel-booking?id=<?= $booking['id'] ?>">
Cancel
</a>

</div>

<?php endforeach; ?>