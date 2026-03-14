<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>

<input type="text" id="time">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
flatpickr("#time", {
enableTime: true,
noCalendar: true,
time_24hr: true
});
</script>

</body>
</html>