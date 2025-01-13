<div class="container mt-4">
    <h1 class="text-center mb-4">Product List</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">SKU</th>
            <th scope="col">Price</th>
            <th scope="col">Bundle</th>
            <th scope="col">Created At</th>
            <th scope="col">Product Actions</th>
            <th scope="col">Bundle Actions</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($products_list as $id_product => $product) : ?>
                <tr>
                    <td><?php echo $product['sku']; ?></td>
                    <td><?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($product['price']); ?></td>
                    <td>
                        <?php if (isset($product_bundles[$id_product])) : ?>
                            Get
                            <?php echo $product_bundles[$id_product]['product_quantity']; ?>
                            for
                            <?php echo \MarketplaceExercise\Helpers\PriceHelper::getDisplayPrice($product_bundles[$id_product]['bundle_price']); ?>
                        <?php else: ?>
                            <i>none</i>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $product['created_at']; ?></td>
                    <td>
                        <a href="/edit-product/<?php echo $id_product; ?>"><button class="btn btn-warning btn-sm">Edit</button></a>
                        &nbsp;
                        <button class="btn btn-danger btn-sm delete-btn" data-action="/delete-product/<?php echo $id_product; ?>">Delete</button>
                    </td>
                    <td>
                        <?php if (isset($product_bundles[$id_product])) : ?>
                            <a href="/edit-bundle/<?php echo $id_product; ?>"><button class="btn btn-warning btn-sm edit-bundle-btn">Edit</button></a>
                            &nbsp;
                            <button class="btn btn-danger btn-sm delete-btn" data-action="/delete-bundle/<?php echo $id_product; ?>">Delete</button>
                        <?php else: ?>
                            <a href="/edit-bundle/<?php echo $id_product; ?>"><button class="btn btn-primary btn-sm edit-bundle-btn">Add</button></a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($products_list)) : ?>
                <tr>
                    <td colspan="6" class="text-center">No Products</td>
                </tr>
            <?php endif; ?>

            <td colspan="6" class="text-center">
                <a href="/new-product"><button class="btn btn-success btn-sm edit-bundle-btn">Create new product</button></a>
            </td>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('.table').on('click', '.delete-btn', function() {
            $(this).text('Removing').attr('disabled', true);

            $.ajax({
                url: $(this).attr('data-action'),
                type: 'POST',
            }).done(data => window.location.reload())
        });
    });
</script>
