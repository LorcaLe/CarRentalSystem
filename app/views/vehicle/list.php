<h2 style="text-align:center;margin-top:40px;">Available Vehicles</h2>

<div class="vehicle-container">

<?php foreach($cars as $car): ?>

<div class="vehicle-card">

<h3><?= $car['name'] ?></h3>

<p>Seats: <?= $car['seats'] ?></p>

<p>$<?= $car['price_per_day'] ?> / day</p>

<a class="btn-gold" href="/car_rental/public/vehicle?id=<?= $car['id'] ?>">
View Details
</a>

</div>

<?php endforeach; ?>

</div>