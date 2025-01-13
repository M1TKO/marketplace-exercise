<div class="container mt-4">
    <h1 class="text-center mb-4">Product Listing</h1>
    <div class="row">
        <?php foreach ($products_list as $id_product => $product) : ?>
            <!-- Example product item -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">SKU: <?php echo $product['sku']; ?></h5>
                        <p class="card-text"><b>Price: <?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($product['price']); ?></b></p>

                        <?php if (isset($product_bundles[$id_product])) : ?>
                            <p class="card-text">
                                Get
                                <?php echo $product_bundles[$id_product]['product_quantity']; ?>
                                for
                                <?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($product_bundles[$id_product]['bundle_price']); ?></p>
						<?php endif; ?>

                        <button class="btn btn-primary add-to-cart" data-id-product="<?php echo $product['id_product']; ?>">Add to Cart</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".add-to-cart").click(function() {
            const btn = $(this);

            btn.text('Added');
            btn.attr('disabled', true);

            setTimeout(() => {
                btn.text('Add to Cart');
                btn.attr('disabled', false);
            }, 300);

            $.ajax({
                url: '/add-to-cart',
                type: 'POST',
                data: { id_product: btn.attr('data-id-product') }
            }).done(function(data) {
                console.log(data);
            })
        });
    });
</script>
