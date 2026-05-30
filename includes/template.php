<?php
$customerName    = 'John Doe';
$customerAddress = '54321 Cloudy Road';
$customerCity    = 'Cloudsville, CA 54321';
$invoiceDate     = date('F d, Y');
$items = [
    [
        'item'        => '001',
        'description' => 'Web Design Services',
        'quantity'    => 1,
        'price'       => 1500.00
    ],
    [
        'item'        => '002',
        'description' => 'Hosting (12 months)',
        'quantity'    => 1,
        'price'       => 240.00
    ],
    // Add more items as needed
];
$totalDue = array_reduce($items, function($sum, $item) {
    return $sum + ($item['quantity'] * $item['price']);
}, 0);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        /* Minimal styling for clarity */
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { margin-bottom: 10px; }
        .company-details, .customer-details {
            display: inline-block;
            width: 45%;
            vertical-align: top;
        }
        .customer-details { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { text-align: right; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Invoice</h1>

    <div class="company-details">
        <strong>Company Name</strong><br>
        12345 Sunny Road<br>
        Sunnyville, TX 12345
    </div>

    <div class="customer-details">
        <strong><?= htmlspecialchars($customerName) ?></strong><br>
        <?= htmlspecialchars($customerAddress) ?><br>
        <?= htmlspecialchars($customerCity) ?>
    </div>
    <br style="clear: both;">

    <p>Date: <?= htmlspecialchars($invoiceDate) ?></p>

    <table>
        <tr>
            <th>Item</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Line Total</th>
        </tr>
        <?php foreach($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['item']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td><?= htmlspecialchars($item['quantity']) ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="total">
        <strong>Total Due: $<?= number_format($totalDue, 2) ?></strong>
    </div>
</body>
</html>