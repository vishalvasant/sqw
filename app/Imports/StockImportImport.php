<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class StockImportImport implements ToModel
{
    public function model(array $row)
    {
        return new Product([
            'product_name' => $row[0],
            'category_id' => $row[1],
            'unit_id' => $row[2],
            'price' => $row[3],
            'stock_quantity' => $row[4],
        ]);
    }
}