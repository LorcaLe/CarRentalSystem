<h2>Vehicle Management</h2>

<a href="/add-vehicle" class="btn-gold">Add New Vehicle</a>

<table border="1" width="100%" style="margin-top:20px">

<tr>
<th>ID</th>
<th>Name</th>
<th>Seats</th>
<th>Price / Day</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php foreach($cars as $car): ?>

<tr>

<td><?= $car['id'] ?></td>

<td><?= $car['name'] ?></td>

<td><?= $car['seats'] ?></td>

<td>$<?= $car['price_per_day'] ?></td>

<td><?= $car['status'] ?></td>

<td>

<a href="/edit-vehicle?id=<?= $car['id'] ?>">Edit</a>

|

<a href="/delete-vehicle?id=<?= $car['id'] ?>"
onclick="return confirm('Delete this vehicle?')">
Delete
</a>

</td>

</tr>

<?php endforeach; ?>

</table>