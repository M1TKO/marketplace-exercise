<div class="container mt-4">
    <h1 class="text-center mb-5">Cart</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">SKU</th>
            <th scope="col">Quantity</th>
            <th scope="col">Single item price</th>
            <th scope="col">Total price</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <td><?php echo $item['sku']; ?></td>
                    <td><?php echo $item['product_quantity']; ?></td>
                    <td><?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($item['single_item_price']); ?></td>
                    <td>
						<?php if ($item['line_price'] != $item['line_price_no_discount']) :; ?>
                            <del style="color: #bbb"><?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($item['line_price_no_discount']); ?></del>
                        <?php endif; ?>

                        <b><?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($item['line_price']); ?></b>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm" data-id-product="<?php echo $item['id_product']; ?>">Remove 1</button>
                    </td>
                </tr>
            <?php endforeach; ?>

			<?php if (empty($items)) :; ?>
                <tr>
                    <td colspan="5" class="text-center">No items added to the cart yet.</td>
                </tr>
			<?php endif; ?>
        </tbody>
    </table>

    <div class="mt-5 text-right">
        <h4 class="text-end">
            Total price:
			<?php if ($total_price_no_discount != $total_price) :; ?>
                &nbsp;
                <del style="color: #bbb"><?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($total_price_no_discount); ?></del>
			<?php endif; ?>
            &nbsp;
            <b><?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($total_price); ?></b>
        </h4>
    </div>

	<?php if (!empty($items)) :; ?>
        <div class="text-end mt-5">
            <button class="btn btn-primary" id="purchase-btn">Purchase</button>
        </div>
	<?php endif; ?>
</div>

<script>
    $(document).ready(function(){
        $('.table').on('click', '.btn-danger', function() {
            $(this).text('Removing').attr('disabled', true);

            $.ajax({
                url: '/remove-from-cart',
                type: 'POST',
                data: { id_product: $(this).attr('data-id-product') }
            }).done(function(data) {
                window.location.reload();
            })
        });

        $('#purchase-btn').click(function() {
            $(this).text('Purchasing').attr('disabled', true);

            $.ajax({
                url: '/process-purchase',
                type: 'POST',
            }).done(function(data) {
                console.log(data);

                if (data.success) {
                    window.location.href = `/receipt/${data.id_sale}`;
                } else {
                    alert(data.message);
                }
            })
        });
    });
</script>
