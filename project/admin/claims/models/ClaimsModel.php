<?php

namespace Jenga\MyProject\Claims\Models;

use Jenga\App\Models\ORM;

class ClaimsModel extends ORM
{
    /**
     * @var string
     */
    public $table = 'claims';
    /**
     * @var string
     */
    protected $process_table = 'claim_process';

    /**
     * Connect customers to the quotes
     */
    public function connectProcess()
    {

        $this->associate($this->process_table)
            ->using([
                'type' => 'one-to-one',
                'local' => 'id',
                'foreign' => 'claim_id',
                'on_delete' => 'delete'
            ]);
    }

    /**
     * Find from table
     * @param int|string $id The finder
     * @param string $table The table
     * @return mixed
     */
    public function findFromTable($id, $table)
    {
        return $this->table($table)->where('id', $id)->first();
    }

    /**
     * The claim process pivot point
     * @return object
     */

    public function getProcessModel()
    {
        return $this->table($this->process_table);
    }

    /**
     * Get the process timeline for the claim
     * @param $claim_id
     * @return array
     */
    public function getProcess($claim_id)
    {
        $claim_process = $this->table($this->process_table)->where('claim_id', $claim_id)->show();
        $count = count($claim_process);
        return [
            'count' => $count,
            'process' => $claim_process
        ];
    }

}