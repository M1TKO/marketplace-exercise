<div class="container mt-4">
    <?php if($bundle) : ?>
	    <h1 class="text-center mb-4">Edit Bundle</h1>
    <?php else: ?>
	    <h1 class="text-center mb-4">New Bundle</h1>
    <?php endif; ?>

	<?php if (!empty($error_message)) : ?>
        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
	<?php endif; ?>

	<?php if ($show_save_success) : ?>
        <div class="alert alert-success" role="alert">Bundle saved successfully</div>
	<?php endif; ?>

    <form method="post" action="/edit-bundle/<?php echo $product['id_product']; ?>">
        <input type="hidden" name="id_product" value="<?php echo $product['id_product']; ?>">
		<div class="mb-3">
			<label class="form-label">SKU</label>
			<input type="text" class="form-control" value="<?php echo $product['sku']; ?>" disabled>
		</div>
        <div class="mb-3">
			<label class="form-label">Product unit price</label>
			<input type="text" class="form-control" value="<?php echo $product['price']; ?>" disabled>
		</div>
		<div class="mb-3">
			<label for="bundleQuantity" class="form-label">Bundle Quantity</label>
			<input type="number" class="form-control" name="product_quantity" value="<?php echo $bundle['product_quantity'] ?? ''; ?>" placeholder="Buy X product">
		</div>
		<div class="mb-3">
			<label for="bundlePrice" class="form-label">Bundle Price (in cents $)</label>
			<input type="number" step="1" class="form-control" name="bundle_price" value="<?php echo $bundle['bundle_price'] ?? ''; ?>" placeholder="For $Y price">
		</div>
		<button type="submit" class="btn btn-primary">Save Changes</button>
		<a href="/edit-products" class="btn btn-secondary">Cancel</a>
	</form>
</div>
