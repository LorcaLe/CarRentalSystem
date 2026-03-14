<h2>Customer Enquiry</h2>

<form method="POST">

<label>Your Question</label>

<textarea name="message" required></textarea>

<button class="btn-gold">Submit Enquiry</button>

</form>

<h3>Your Previous Enquiries</h3>

<?php foreach($enquiries as $enquiry): ?>

<div class="card">

<p><?= $enquiry['message'] ?></p>
<p>Date: <?= $enquiry['created_at'] ?></p>

</div>

<?php endforeach; ?>