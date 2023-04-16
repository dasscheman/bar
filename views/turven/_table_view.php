Turf gegevens:
<table id="table">
    <tr>
        <th>ID</th>
        <th>Item</th>
        <th>Prijslijst</th>
        <th>Aantal</th>
        <th>Prijs</th>
        <th>Status</th>
        <th>Datum</th>
    </tr>
    <?php foreach($model->turvens as $items) { ?>
        <tr>
            <td><?php echo $items->turven_id; ?></td>
            <td><?php echo $items->eenheid->name; ?></td>
            <td><?php echo 'Prijslijst ' . $items->prijslijst_id; ?></td>
            <td><?php echo $items->aantal; ?></td>
            <td><?php echo number_format($items->totaal_prijs, 2, ',', ' '); ?> €</td>
            <td><?php echo $items->getStatusText(); ?></td>
            <td><?php echo Yii::$app->setupdatetime->displayFormat($items->datum, 'datetime2', true) ?></td>
        </tr>
    <?php } ?>
</table>


<style>
    #table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #table td, #table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #table tr:nth-child(even){background-color: #f2f2f2;}

    #table tr:hover {background-color: #ddd;}

    #table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
    }
</style>
