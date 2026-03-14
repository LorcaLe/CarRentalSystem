<h2><?= $car['name'] ?></h2>

<p><?= $car['description'] ?></p>

<p>Seats: <?= $car['seats'] ?></p>

<p>Price: $<?= $car['price_per_day'] ?>/day</p>

<form method="POST" action="/book">

<input type="hidden" name="vehicle_id"
value="<?= $car['id'] ?>">

<label>Pickup Date</label>
<input type="date" name="pickup_date">

<label>Return Date</label>
<input type="date" name="return_date">

<button class="btn-gold">Book Now</button>

</form>