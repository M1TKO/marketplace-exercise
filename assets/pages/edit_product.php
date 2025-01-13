<div class="container mt-4">
	<?php if($product) : ?>
		<h1 class="text-center mb-4">Edit Product</h1>
	<?php else: ?>
		<h1 class="text-center mb-4">New Product</h1>
	<?php endif; ?>

	<?php if (!empty($error_message)) : ?>
		<div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
	<?php endif; ?>

	<?php if ($show_save_success) : ?>
		<div class="alert alert-success" role="alert">Product saved successfully</div>
	<?php endif; ?>

	<form method="post" action="<?php echo $product ? "/edit-product/{$product['id_product']}" : '/new-product'; ?>">
		<input type="hidden" name="id_product" value="<?php echo $product['id_product'] ?? 0; ?>">

		<div class="mb-3">
			<label class="form-label">SKU</label>
			<input type="text" class="form-control" name="sku" value="<?php echo $product['sku']; ?>" placeholder="Max 2 letter characters" maxlength="2" <?php echo $product ? 'disabled' : ''; ?> />
		</div>
		<div class="mb-3">
			<label for="productPrice" class="form-label">Product Price (in cents $)</label>
			<input type="number" step="1" class="form-control" name="price" value="<?php echo $product['price'] ?? ''; ?>" placeholder="$ cents">
		</div>
		<button type="submit" class="btn btn-primary">Save Changes</button>
		<a href="/edit-products" class="btn btn-secondary">Cancel</a>
	</form>
</div>
