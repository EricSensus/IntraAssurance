<?php
namespace Jenga\MyProject\Navigation\Schema;

use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;
/**
 * Created by PhpStorm.
 * User: DEVELOPER 1
 * Date: 20/04/2017
 * Time: 16:48
 */
class MenuSchema implements SchemaInterface
{
    private $table = 'menus';

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build(){
        
        SchemaTasks::dropAndCreate($this->table)
            ->column('id', ['int', 'not null', 'auto_increment'])
            ->column('linkname', ['text', 'not null'])
            ->column('linkalias', ['text', 'not null'])
            ->column('href', ['varchar(200)', 'not null'])
            ->column('linkorder', ['int', 'not null'])
            ->column('menu_groups_id', ['text', 'not null'])
            ->column('parentid', ['int', 'not null'])
            ->column('published', ['boolean', 'not null'])
            ->column('home', ['text', 'null'])
            ->column('params', ['text', 'not null'])
            ->primary('id')->build();
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed()
    {
        $menus = array(
            array('id' => '58','linkname' => 'Financials','linkalias' => 'financials','href' => '{base}/admin/financials','linkorder' => '4','menu_groups_id' => '1','parentid' => '0','published' => '0','home' => '','params' => ''),
            array('id' => '85','linkname' => 'Dashboard','linkalias' => 'dashboard','href' => '{base}/admin/dashboard','linkorder' => '1','menu_groups_id' => '1','parentid' => '0','published' => '1','home' => 'yes','params' => ''),
            array('id' => '88','linkname' => 'Companies','linkalias' => 'companies','href' => '{base}/admin/companies','linkorder' => '5','menu_groups_id' => '1','parentid' => '0','published' => '0','home' => '','params' => ''),
            array('id' => '84','linkname' => 'Customers','linkalias' => 'customers','href' => '{base}/admin/customers','linkorder' => '2','menu_groups_id' => '1','parentid' => '0','published' => '1','home' => 'yes','params' => ''),
            array('id' => '107','linkname' => 'Reports','linkalias' => 'reports','href' => '{base}/admin/reports','linkorder' => '6','menu_groups_id' => '1','parentid' => '0','published' => '1','home' => 'no','params' => ''),
            array('id' => '105','linkname' => 'Setup','linkalias' => 'setup','href' => '{base}/admin/setup','linkorder' => '1','menu_groups_id' => '3','parentid' => '0','published' => '1','home' => 'yes','params' => ''),
            array('id' => '102','linkname' => 'Policies','linkalias' => 'policies','href' => '{base}/admin/policies','linkorder' => '5','menu_groups_id' => '1','parentid' => '0','published' => '1','home' => ' ','params' => ''),
            array('id' => '104','linkname' => 'Quotes','linkalias' => 'quotes','href' => '{base}/admin/quotes','linkorder' => '3','menu_groups_id' => '1','parentid' => '0','published' => '1','home' => '','params' => ''),
            array('id' => '106','linkname' => 'Menus','linkalias' => 'menus','href' => '{base}/admin/navigation','linkorder' => '2','menu_groups_id' => '5','parentid' => '0','published' => '1','home' => 'no','params' => ''),
            array('id' => '108','linkname' => 'Add Policy','linkalias' => 'add-policy','href' => '{base}/admin/policies/add','linkorder' => '1','menu_groups_id' => '1','parentid' => '102','published' => '1','home' => NULL,'params' => '{"icopath":"images\\/add_navicon.png"}'),
            array('id' => '109','linkname' => 'Policies Listing','linkalias' => 'policies-listing','href' => '{base}/admin/policies','linkorder' => '3','menu_groups_id' => '1','parentid' => '102','published' => '1','home' => NULL,'params' => '{"icopath":"images\\/policies_navicon.png"}'),
            array('id' => '110','linkname' => 'Add Quote','linkalias' => 'add-quotes','href' => '{base}/admin/quotes/add','linkorder' => '1','menu_groups_id' => '1','parentid' => '104','published' => '1','home' => 'yes','params' => '{"icopath":"images\\/add_navicon.png"}'),
            array('id' => '111','linkname' => 'Quote Listing','linkalias' => 'quote-listing','href' => '{base}/admin/quotes','linkorder' => '2','menu_groups_id' => '1','parentid' => '104','published' => '1','home' => 'no','params' => '{"icopath":"images\\/quotes_navicon.png"}'),
            array('id' => '112','linkname' => 'Global Settings','linkalias' => 'global','href' => '#','linkorder' => '2','menu_groups_id' => '3','parentid' => '0','published' => '1','home' => NULL,'params' => ''),
            array('id' => '113','linkname' => 'Navigation','linkalias' => 'navigation','href' => '{base}/admin/navigation/show','linkorder' => '3','menu_groups_id' => '3','parentid' => '112','published' => '1','home' => 'no','params' => ''),
            array('id' => '114','linkname' => 'Access Levels','linkalias' => 'access','href' => '{base}/admin/navigation/access','linkorder' => '2','menu_groups_id' => '3','parentid' => '112','published' => '1','home' => NULL,'params' => ''),
            array('id' => '115','linkname' => 'User Management','linkalias' => 'users','href' => '{base}/admin/users','linkorder' => '1','menu_groups_id' => '3','parentid' => '112','published' => '1','home' => NULL,'params' => ''),
            array('id' => '116','linkname' => 'System Policies','linkalias' => 'policies','href' => '{base}/admin/navigation/access/policies','linkorder' => '4','menu_groups_id' => '3','parentid' => '112','published' => '1','home' => 'no','params' => '{"icopath":"images\\/issue_policy_icon.png"}'),

            // customer menus
            array(
                'id' => 117,
                'linkname' => 'Dashboard',
                'linkalias' => 'dashboard',
                'href' => '{base}/profile/dashboard',
                'linkorder' => 1,
                'menu_groups_id' => 6,
                'parentid' => 0,
                'published' => 1,
                'home' => 'no',
                'params' => '{"icoclass":"fa fa fa-home fa-3x"}'
            ),array(
                'id' => 118,
                'linkname' => 'My Quotes',
                'linkalias' => 'my-quotes',
                'href' => '{base}/customer/my-quotes',
                'linkorder' => 1,
                'menu_groups_id' => 6,
                'parentid' => 0,
                'published' => 1,
                'home' => 'no',
                'params' => '{"icoclass":"fa fa fa-files-o fa-3x"}'
            ),
            array(
                'id' => 119,
                'linkname' => 'Get a Quote',
                'linkalias' => 'get-a-quote',
                'href' => '{base}/admin/quotes/add',
                'linkorder' => 1,
                'menu_groups_id' => 6,
                'parentid' => 118,
                'published' => 1,
                'home' => 'no',
                'params' => ''
            ),
            array(
                'id' => 120,
                'linkname' => 'My Policies',
                'linkalias' => 'my-policies',
                'href' => '{base}/customer/my-policies',
                'linkorder' => 2,
                'menu_groups_id' => 6,
                'parentid' => 0,
                'published' => 1,
                'home' => 'no',
                'params' => '{"icoclass":"fa fa fa-files-o fa-3x"}'
            ),
            array(
                'id' => 121,
                'linkname' => 'My Claims',
                'linkalias' => 'my-claims',
                'href' => '{base}/customer/my-claims',
                'linkorder' => 3,
                'menu_groups_id' => 6,
                'parentid' => 0,
                'published' => 1,
                'home' => 'no',
                'params' => '{"icoclass":"fa fa fa-files-o fa-3x"}'
            ),
        );

        SchemaTasks::insert($this->table, $menus);
    }

    /**
     * This would be for running more complex operations on the table
     */
    public function run()
    {
        // TODO: Implement run() method.
    }
}
