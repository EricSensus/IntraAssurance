<?php
namespace Jenga\MyProject\Entities\Models;

use Jenga\App\Models\ORM;
use Jenga\App\Request\Facade\Sanitize;

class EntitiesModel extends ORM
{

    public $table = 'entities';

    public function getEntityTypes($id = null)
    {

        if (is_null($id))
            return $this->table('entity_types')->show();
        else
            return $this->table('entity_types')->where('id', $id)->show();
    }

    /**
     * Retrieves generic entities based on type id sent
     * @param type $typeid
     * @return object $results
     */
    public function getEntitiesByType($typeid)
    {
        return $this->table('entities')->where('entity_types_id', $typeid)->show();
    }

    /**
     * Returns the customer_entity_data table model
     * @param int|null $id
     * @return object
     */
    public function customerEntityData($id = null)
    {

        if (empty($id))
            return $this->table('customer_entity_data');
        else
            return $this->table('customer_entity_data', 'NATIVE')->where('id',$id)->first();
    }

    /**
     * Returns the processed entity values
     *
     * @param int $id
     * @param string $column
     * @return type
     */
    public function getCustomerEntity($id, $column, $pid = null)
    {

        $entitytable = $this->customerEntityData();

        if (Sanitize::is_json($id)) {

            $ids = json_decode($id, true);
            $multiple_entities = true;

            $count = 0;
            foreach ($ids as $id) {

                if ($count == 0) {

                    if ($column == 'primary')
                        $entitytable->where('id', $id);
                    else
                        $entitytable->where($column, $id);
                } else {
                    if ($column == 'primary')
                        $entitytable->orWhere('id', $id);
                    else
                        $entitytable->orWhere($column, $id);
                }

                $count++;
            }

            if ($column == 'primary')
                $customerentity = $entitytable->first();
            else
                $customerentity = $entitytable->show();
        } else {

            if ($column == 'primary')
                $customerentity = $entitytable->where('id', $id)->first();
            else
                $customerentity = $entitytable->where($column, $id)->show();
        }

        if (!is_array($customerentity)) {

            //get generic entity name
            $generic = $this->where('id', $customerentity->entities_id)->first();

            $entarr = ['entity' => json_decode($customerentity->entity_values, true),
                'type' => $generic->name,
                'id' => $customerentity->id];

            $ent[] = $entarr;
        } else {

            foreach ($customerentity as $ind_entity) {

                //get generic entity name
                $generic = $this->where('id', $ind_entity->entities_id)->first();

                if (!is_null($pid)) {

                    //get product
                    $product = $this->table('products')->where('id', $pid)->first();

                    //only add if both product and generic entity types align
                    if ($product->entity_types_id == $generic->entity_types_id) {

                        $entarr = ['id' => $ind_entity->id,
                            'entity' => json_decode($ind_entity->entity_values, true),
                            'type' => $generic->name
                        ];

                        if ($multiple_entities == true)
                            $entarr['multiple'] = true;

                        $ent[] = $entarr;
                    }
                } else {

                    $entarr = ['id' => $ind_entity->id,
                        'entity' => json_decode($ind_entity->entity_values, true),
                        'type' => $generic->name
                    ];

                    if ($multiple_entities == true)
                        $entarr['multiple'] = true;

                    $ent[] = $entarr;
                }
            }
        }

        return $ent;
    }
}

