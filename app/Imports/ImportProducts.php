<?php

namespace App\Imports;

use App\Models\Product;
use App\Services\InventoryServices;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ImportProducts implements ToCollection, WithHeadingRow, WithUpserts, WithUpsertColumns
{

    public $category_id;
    public function __construct($category_id)
    {
        $this->category_id = $category_id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $inventoryService = new InventoryServices();
        $user = request()->user();
        foreach ($rows as $row) {
            if ($row['description'] || $row['item_id']) {
                if ($row['description']) {
                    if (!str_contains(strtolower($row['description']), 'dummy') && !str_contains(strtolower($row['description']), 'dont use') && !str_contains(strtolower($row['description']), 'do not') && !str_contains(strtolower($row['description']), 'ayaw')) {
                        $product = Product::updateOrCreate([
                            'code' => $row['item_id'] ?? $row['description'] ?? ' ',
                            'name' => $row['description'] ?? $row['item_id'] ?? ' ',
                            'description' => $row['description'] ?? $row['item_id'] ?? ' ',
                        ], [
                            'category_id' => $this->category_id,
                            'unit_measurement' => $row['uom'] ?? ' ',
                            'unit_value' => 1,
                        ]);

                        $inventoryLocation = $inventoryService->resolveProduct($product->id, $user->branch_id);

                        $stock = $inventoryService->resolveStockInventory($inventoryLocation);
                    }
                }
            }
        }
    }
    public function upsertColumns()
    {
        return ['item_id', 'description'];
    }
    public function uniqueBy()
    {
        return ['item_id', 'description'];
    }
}
