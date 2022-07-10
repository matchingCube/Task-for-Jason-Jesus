<!DOCTYPE html>
<html>
<head>
    <title>Ground Force Trucking</title>
</head>
<body>
    <h1>Ground Force Trucking</h1>
    <p>Hi,</p>
    <p>You received PDF file(s) for monthly maintenance record.</p>
    <p>Maintenance Record for the Month and Year of: <strong>{{ $date }}</strong></p>
    <p>Tractor IDs: {{ implode(', ', $tractors) }}</p>
    <p>Thank you!</p>
</body>
</html>