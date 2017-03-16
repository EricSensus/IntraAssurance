<?php
namespace Jenga\App\Console\Commands;

/**
 * This command deletes an element from the Jenga project
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
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Elements\XmlElements;

class DeleteElement extends Command {
    
    protected function configure() {
        
        $this->setName('delete:element')
                ->setDescription('Deletes an element from the Jenga project')
                ->addArgument('element_name',  InputArgument::REQUIRED);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $element = $input->getArgument('element_name');
        $io = new SymfonyStyle($input, $output);
        
        $xmldoc = App::get(XmlElements::class);
        $file = App::get(File::class);
        
        if($xmldoc->loadXMLFile('map.xml', PROJECT_PATH)){
            
            if($xmldoc->selectXMLElement($element)){
                
                $elementpath = Project::elements()[$element]['path'];
                
                //process element oath
                if(strstr($elementpath,'/') !== FALSE){
                    $elementpath = str_replace('/', DS, $elementpath);
                }
                
                //remove the actual folder
                if($file->deleteFolder(ABSOLUTE_PROJECT_PATH .DS. $elementpath, TRUE)){
                    
                    //remove the XML node in maps
                    $xmldoc->deleteXMLElement();                    
                    $io->success($element.' has been deleted.');
                }
                else{
                    $io->error($element.' folder was not deleted in the Jenga project folder');
                }
            }
            else{
                $io->error($element.'has not been found in your Jenga Project');
            }
        }
    }
}
