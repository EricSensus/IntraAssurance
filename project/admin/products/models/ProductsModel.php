<?php
namespace Jenga\MyProject\Products\Models;

use Jenga\App\Models\ORM;

class ProductsModel extends ORM
{

    public $table = 'products';

    public function getProduct($id, $return = 'array')
    {
        if ($return == 'array') {
            $data = $this->where('id', '=', $id)
                ->first();

            return [
                'id' => $id,
                'name' => $data->name,
                'product_alias' => $data->alias
            ];
        } else {
            return $this->where('id', '=', $id)->first();
        }
    }
}