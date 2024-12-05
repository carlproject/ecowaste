<!-- performance_report.php -->
<!DOCTYPE html>
<html>
<head>
<title>Performance Report</title>
</head>
<body>
<h1>Performance Report</h1>
<table>
<tr>
<th>Metric</th>
<th>Value</th>
</tr>
<?php foreach ($performance as $metric): ?>
<tr>
<td><?php echo $metric['metric']; ?></td>
<td><?php echo $metric['value']; ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>