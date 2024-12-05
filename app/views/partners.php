<!DOCTYPE html>
<html>
<head>
    <title>Partners</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #eaeaea;
        }
    </style>
</head>
<body>
    <h1>Partners</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
        </tr>
        <?php foreach ($partners as $partner): ?>
        <tr>
            <td><?php echo htmlspecialchars($partner['id']); ?></td>
            <td><?php echo htmlspecialchars($partner['name']); ?></td>
            <td><?php echo htmlspecialchars($partner['contact']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
