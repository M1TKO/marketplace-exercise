<div class="container mt-4 " style="max-width: 800px">
    <h1 class="text-center mb-5">Purchase completed</h1>
    <h2 class="text-center mb-5">ORDER #<?php echo $sale['id_sale']; ?></h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">SKU</th>
            <th scope="col">Quantity</th>
            <th scope="col">Total price</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($sale_details as $item) : ?>
                <tr>
                    <td><?php echo $item['sku']; ?></td>
                    <td><?php echo $item['product_quantity']; ?></td>
                    <td><b><?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($item['line_price']); ?></b></td>
                </tr>
            <?php endforeach; ?>

			<?php if (empty($sale_details)) :; ?>
                <tr>
                    <td colspan="5" class="text-center">No such order exists</td>
                </tr>
			<?php endif; ?>
        </tbody>
    </table>

    <div class="mt-5 text-right">
        <h4 class="text-end">
            Total paid:
            <b><?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($sale['total_price'] ?? 0); ?></b>
        </h4>
    </div>
</div>
