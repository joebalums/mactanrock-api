<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierBank;
use App\Models\SupplierContact;
use App\Services\InventoryServices;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ImportSupplier implements ToCollection, WithHeadingRow, WithUpserts, WithUpsertColumns
{

    // public $category_id;
    public function __construct()/* $category_id */
    {
        // $this->category_id = $category_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $supplier = Supplier::updateOrCreate([
                'address' => $row['address'] ?? ' ',
                'street' => $row['street'] ?? ' ',
                'owner' => $row['owner'] ?? ' ',
                'tin' => $row['tin'] ?? ' ',
                'gl_account' => $row['gl_account_id'] ?? ' '
            ], [
                'name' => $row['name'] ?? ' ',
                'code' => $row['code'] ?? ' ',
            ]);

            $bank = new SupplierBank();
            $bank->name = $row['bank_name'] ?? ' ';
            $bank->account_name = $row['bank_account_name'] ?? ' ';
            $bank->account_number = $row['bank_account_number'] ?? ' ';
            $bank->location = $row['bank_account_location'] ?? ' ';
            $bank->supplier_id = $supplier->id;
            $bank->save();

            $contact = new SupplierContact();
            $contact->name = $row['contact_person_name'] ?? ' ';
            $contact->email = $row['contact_person_email'] ?? ' ';
            $contact->number = $row['contact_person_mobile'] ?? ' ';
            $contact->position = $row['contact_person_position'] ?? ' ';
            $contact->supplier_id = $supplier->id;
            $contact->save();
        }
    }

    public function upsertColumns()
    {

        return [
            'code',
            'name',
            'address',
            'street',
            'owner',
            'tin',
            'gl_account_id',
            'contact_person_name',
            'contact_person_mobile',
            'contact_person_position',
            'contact_person_email',
            'bank_name',
            'bank_account_number',
            'bank_account_name',
            'bank_account_location'
        ];
    }
    public function uniqueBy()
    {
        return [
            'code',
            'name',
            'address',
            'street',
            'owner',
            'tin',
            'gl_account_id',
            'contact_person_name',
            'contact_person_mobile',
            'contact_person_position',
            'contact_person_email',
            'bank_name',
            'bank_account_number',
            'bank_account_name',
            'bank_account_location'
        ];
    }
}
