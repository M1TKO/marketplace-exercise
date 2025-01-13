<?php

namespace MarketplaceExercise\Managers;

use DB;

class PurchaseManager extends BaseSingletonManager
{
	public function getSaleDataForDisplay(int $id_sale): array
	{
		$sale = DB::query('SELECT * FROM sales WHERE id_sale = %i', $id_sale);
		$sale_details = DB::query("
			SELECT sd.* FROM sales_details sd 
			JOIN products p on p.id_product = sd.id_product
			 WHERE id_sale = %i
			",
			$id_sale
		);

		return [
			'sale' => $sale[0] ?? [],
			'sale_details' => $sale_details,
		];
	}

	/**
	 * @param array $items
	 * @return int|null
	 */
	public function purchaseProducts(array $items): ?int
	{
		try {
			$total_price = (int)array_sum(array_column($items, 'line_price'));

			DB::startTransaction();
			DB::insert('sales', ['total_price' => $total_price]);

			$id_sale = DB::insertId();
			$sales_details = [];

			foreach ($items as $item) {
				$sales_details[] = [
					'id_sale'          => $id_sale,
					'id_product'       => $item['id_product'],
					'sku'              => $item['sku'],
					'product_quantity' => $item['product_quantity'],
					'line_price'       => $item['line_price'],
				];
			}

			DB::insert('sales_details', $sales_details);
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollback();

			return null;
		}

		return $id_sale;
	}
}
