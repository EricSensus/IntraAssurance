<?php
namespace Jenga\MyProject\Travel\Models;

use Jenga\App\Models\ORM;
use Jenga\App\Models\Repositories\RepositoryInterface;

/**
 * Description of TravelModel
 *
 * @author developer
 */
class TravelModel extends ORM implements RepositoryInterface
{
    public function register()
    {
        $this->entity = Travel::class;
    }
}
