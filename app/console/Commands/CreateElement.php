<?php
namespace Jenga\App\Console\Commands;

/**
 * The command creates a basic element in the Jenga project
 *
 * @author sngumo
 */

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Style\SymfonyStyle;

use Jenga\App\Core\App;
use Jenga\App\Core\File;
use Jenga\App\Project\Elements\XmlElements;

class CreateElement extends Command {
    
    public $mouldpath;
    public $file;

    protected function configure() {
        
        $this->setName('create:element')
                ->setDescription('Creates a basic element in the Jenga project')
                ->addArgument('element_name',  InputArgument::REQUIRED)
                ->addOption('path', null, 
                        InputOption::VALUE_OPTIONAL, 
                        'Specify the folder path where the element would be created. '
                        . 'Start with forward slash from the project folder onwards e.g. /foldername');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $element = str_replace(' ', '', $input->getArgument('element_name'));
        
        $path = $input->getOption('path');
        $io = new SymfonyStyle($input, $output);
        
        //create the mould path
        $this->mouldpath = ABSOLUTE_PATH .DS. 'app' .DS. 'build' .DS. 'moulds' .DS. 'element';
        $this->file = App::get(File::class);
            
        $xml = [];

        $foldername = strtolower($element);
        $xml['element'] = $foldername;
        
        //process element path
        if(!is_null($path)){
            
            if(strpos($path, '/') !== FALSE){
                
                $path = ltrim($path,'/');
                $foldername = str_replace('/', DS, $path);
            }
            else if(strpos($path, '\\') !== FALSE){
                
                $path = ltrim($path,'\\');
                $foldername = str_replace('\\', DS, $path);
            }
        }
        
        $xml['path'] = $foldername;
        
        //reorient the map path shown in the map.xml
        if(strstr($foldername,'\\') !== FALSE){
            $mappath = str_replace('\\', '/', $foldername);
        }
        else{
            $mappath = $foldername;
        }
        
        $xml['mappath'] = $mappath;

        //create the element
        $io->title('Creating '.ucfirst($element).' Element');

        if(@mkdir(ABSOLUTE_PROJECT_PATH .DS. $foldername, 0777, TRUE)){

            $xml['element_path'] = $foldername;
            $rootfolder = ABSOLUTE_PROJECT_PATH .DS. $foldername;

            //create models folder
            if(mkdir($rootfolder .DS. 'models',0777)){    

                $this->_createModelClass($rootfolder .DS. 'models', $element);
                $io->write(' [..] '.ucfirst($element).' model created ', TRUE);

                $xml['model_folder'] = 'models';
            }

            //create controllers folder
            if(mkdir($rootfolder .DS. 'controllers',0777)){

                $this->_createControllerClass($rootfolder .DS. 'controllers', $element);
                $io->write(' [..] '.ucfirst($element).' controller created ', TRUE);

                $xml['controller_folder'] = 'controllers';
            }

            //create views folder
            if(mkdir($rootfolder .DS. 'views',0777)){

                $this->_createViewClass($rootfolder .DS. 'views', $element);
                $io->write(' [..] '.ucfirst($element).' view created ', TRUE);

                $xml['views_folder'] = 'views';
            }

            //create schema folder
            if(mkdir($rootfolder .DS. 'schema',0777)){

                $this->_createSchemaClass($rootfolder .DS. 'schema', $element);
                $io->write(' [..] '.ucfirst($element).' schema created ', TRUE);

                $xml['schema_folder'] = 'schema';
            }

            //create panels inside views folder
            if(mkdir($rootfolder .DS. 'views' .DS. 'panels',0777)){

                $panel = $this->file->get($this->mouldpath .DS. 'element.mould');
                $this->file->put($rootfolder .DS. 'views' .DS. 'panels' .DS. strtolower($element).'.php', $panel);
                $io->write(' [..] '.ucfirst($element).' panel created ', TRUE);
            }

            //insert the new element into maps.xml
            $this->_addNewElement($xml);
        }
        else{
            $io->warning('The [ '.ucfirst($element).' ] element folder not created');
        }
                
    }
    
    /**
     * Create the model class
     * @param type $modelpath
     */
    private function _createModelClass($modelpath, $element){
        
        $model = $this->file->get($this->mouldpath .DS. 'model.mould');
        
        $modelnamespace = 'Jenga\\MyProject\\'.ucfirst($element).'\\Models';
        $modelclass = ucfirst($element).'Model';
        
        $mdl = str_replace('{{{mdl_namespace}}}', $modelnamespace, $model);
        $mdldata = str_replace('{{{mdl_classname}}}', $modelclass, $mdl);
        
        return $this->file->put($modelpath .DS. $modelclass.'.php', $mdldata);
    }
    
    /**
     * Create the controller class
     * @param type $controllerpath
     */
    private function _createControllerClass($controllerpath, $element){
        
        $controller = $this->file->get($this->mouldpath .DS. 'controller.mould');
        
        $controllernamespace = 'Jenga\\MyProject\\'.ucfirst($element).'\\Controllers';
        $controllerclass = ucfirst($element).'Controller';
        
        $ctrl = str_replace('{{{ctrl_namespace}}}', $controllernamespace, $controller);
        $ctrldata = str_replace('{{{ctrl_classname}}}', $controllerclass, $ctrl);
        
        return $this->file->put($controllerpath .DS. $controllerclass.'.php', $ctrldata);
    }
    
    /**
     * Create the view class
     * @param type $viewpath
     */
    private function _createViewClass($viewpath, $element){
        
        $view = $this->file->get($this->mouldpath .DS. 'view.mould');
        
        $viewnamespace = 'Jenga\\MyProject\\'.ucfirst($element).'\\Views';
        $viewclass = ucfirst($element).'View';
        
        $viewstr = str_replace('{{{view_namespace}}}', $viewnamespace, $view);
        $viewdata = str_replace('{{{view_classname}}}', $viewclass, $viewstr);
        
        return $this->file->put($viewpath .DS. $viewclass.'.php', $viewdata);
    }
    
    /**
     * Create the schema class
     * @param type $schemapath
     */
    private function _createSchemaClass($schemapath, $element){
        
        $schema = $this->file->get($this->mouldpath .DS. 'schema.mould');
        
        $schemanamespace = 'Jenga\\MyProject\\'.ucfirst($element).'\\Schema';
        $schemaclass = ucfirst($element).'Schema';
        
        $schemastr = str_replace('{{{schm_namespace}}}', $schemanamespace, $schema);
        $schemadata = str_replace('{{{schm_classname}}}', $schemaclass, $schemastr);
        
        return $this->file->put($schemapath .DS. $schemaclass.'.php', $schemadata);
    }
    
    /**
     * Adds the new element into the map.xml file
     * 
     * @param type $xml
     */
    private function _addNewElement($xml){
        
        $xmldoc = App::get(XmlElements::class);
        
        if($xmldoc->loadXMLFile('map.xml', PROJECT_PATH, TRUE)){
            
            //create folder list
            $folder['model'] = $xml['model_folder'];
            $folder['controller'] = $xml['controller_folder'];
            $folder['view'] = $xml['views_folder'];
            $folder['schema'] = $xml['schema_folder'];
            
            //create element attributes
            $attrs['name'] = $xml['element'];
            $attrs['path'] = $xml['mappath'];
            $attrs['acl'] = 0;
            $attrs['visibility'] = 'public';
            
            $xmldoc->addElement($xml['element'], $folder, $attrs);
        }
    }
}
