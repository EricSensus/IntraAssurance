<?php
namespace Jenga\App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\ConfirmationQuestion;

use Jenga\App\Core\File;

class ElementActions extends Command {
    
    public $element;
    public $actions;
    public $input;
    public $output;
    public $io;
    
    protected function configure(){
        
        $this->setName('element:actions')
                ->setDescription('This command allows for setting the actions for each element '
                        . 'and assigning the class methods used to implement those actions')
                ->addArgument('element', 
                               InputArgument::OPTIONAL, 
                               'Specify the required element for the actions')
                ->addOption('add', 
                            null, 
                            InputOption::VALUE_OPTIONAL, 
                            'Adds a new action --add <action>')
                ->addOption('method', 
                            null, 
                            InputOption::VALUE_OPTIONAL, 
                            'Adds the connected class method --method <Controller@method>')
                ->addOption('action', 
                            null, 
                            InputOption::VALUE_OPTIONAL, 
                            'Selects a specific element action --action <action>')
                ->addOption('alias',
                            null,
                            InputOption::VALUE_OPTIONAL,
                            'Add new alias for the action --alias <alias>')
                ->addOption('remove', 
                            null, 
                            InputOption::VALUE_OPTIONAL, 
                            'Removes an element action')
                ->addOption('removeelement', 
                            null, 
                            InputOption::VALUE_OPTIONAL, 
                            'Removes all actions linked to an element')
                ->addOption('removealias', 
                            null, 
                            InputOption::VALUE_OPTIONAL, 
                            'Removes alias linked to an action')
                ->addOption('level', 
                            null, 
                            InputOption::VALUE_OPTIONAL, 
                            'Add an access level number to the element - 0 being the lowest')
                ->addOption('list', 
                            null, 
                            InputOption::VALUE_OPTIONAL, 
                            'Displays the registered actions in the current project');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output){
        
        $this->input = $input;
        $this->actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
        $this->output = $output;
        
        //check if argument is set
        if($input->hasArgument('element')){              
            $this->element = $input->getArgument('element');
        }
        
        //get the Symfony styling class
        $this->io = new SymfonyStyle($input, $output);

        if($this->_checkOptions($input->getOptions()) != FALSE){

            $optionslist = $this->_checkOptions($input->getOptions());

            foreach($optionslist as $option){

                if(method_exists($this, $option)){
                    $this->{$option}();
                }
            }
            
            exit();
        }
        elseif(!is_null($this->element)){

            if(array_key_exists($this->element, $this->actions)){
                print_r($this->actions[$this->element]);
            }
            elseif(!array_key_exists($this->element, $this->actions)){
                $this->io->error('The element [ '.  $this->element.' ] has NO registered actions.');
            }

            exit();
        }

        //a catch all which lists all the actions listed
        if(is_null($this->element) || $this->element == 'list'){

            print_r($this->actions);
            exit();
        }
    }
    
    /**
     * Checks the sent options and returns the value which isnt NULL
     * 
     * @param array $options
     * @return boolean
     */
    private function _checkOptions(array $options){
        
        $keywords = ['help','quiet','verbose','version','ansi','no-ansi','no-interaction'];
        
        foreach($options as $option => $value){
            
            if(!in_array($option, $keywords)){
                
                if(!is_null($value)){
                    
                    $optionslist[] = $option;
                }
            }
        }
        
        if(isset($optionslist)){
            return $optionslist;
        }
    }
    
    /**
     * Returns actions in an array based on the select element
     * @param type $element
     * @return type
     */
    private function _returnSelectActions($element){
        
        if(!is_null($element)){

            if(!is_null($this->actions[$element]) && count($this->actions[$element]) >= 1){                
                $select_actions = array_keys($this->actions[$element]['actions']);
            }
            else{
                
                $select_actions = [];
            }
        }
        
        return $select_actions;
    }
    
    private function _checkActionOption(&$entry, InputInterface $input){
        
        //check the action option
        if($input->hasOption('action')){

            $action = $input->getOption('action');

            if(!is_null($action))
                $entry = $input->getOption('action');
        }
    }
    
    //check the add option
    public function add(){
        
        $select_actions = $this->_returnSelectActions($this->element);
        $entry = $this->input->getOption('add');

        if(!is_null($entry)){

            //remove action is it exists
            if(in_array($entry, $select_actions)){
                unset($this->actions[$this->element]['actions'][$entry]);
            }

            if($this->input->hasOption('method')){

                $method = $this->input->getOption('method');
                $this->actions[$this->element]['actions'][$entry] = $method;
            }
            else{
                $this->io->error('The linked class method must be specified using --method');
            }

            $this->io->success('The action [ '.$entry.' ] has been added to [ '.$this->element.' ]');
            File::put( APP_PROJECT .DS. 'element_actions_levels.php', serialize($this->actions) );

            @ob_clean();
            $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
            print_r($actions[$this->element]);
        }
    }
    
    //check the alias option, the action should be defined by either the --add or --action options
    public function alias(){
        
        if($this->input->hasOption('action')){
            $entry = $this->input->getOption ('action');
        }        
        elseif($this->input->hasOption('add')){
            $entry = $this->input->getOption ('add');
        }
        
        $alias = $this->input->getOption('alias');

        if(!is_null($alias)){
            $sel_element = $this->actions[$this->element];

            if(!array_key_exists('alias', $sel_element)){
                $this->actions[$this->element]['alias'] = [];
            }

            //add the alias
            $this->actions[$this->element]['alias'][$entry] = $alias;

            $this->io->success('The alias [ '.$alias.' ] for action [ '.$entry.' ] has been added');
            File::put( APP_PROJECT .DS. 'element_actions_levels.php', serialize($this->actions) );

            @ob_clean();
            $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
            print_r($actions[$this->element]);
        }
    }
    
    //check the remove option
    public function remove(){
        
        //$this->_checkActionOption($entry, $input);

        $select_actions = $this->_returnSelectActions($this->element);
        $entry = $this->input->getOption('remove');

        if(!is_null($entry)){

            if(in_array($entry, $select_actions)){

                unset($this->actions[$this->element]['actions'][$entry]);

                $this->io->success('The action [ '.$entry.' ] has been removed');
                File::put( APP_PROJECT .DS. 'element_actions_levels.php', serialize($this->actions) );

                @ob_clean();
                $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
                print_r($actions[$this->element]);
            }
            else{

                $this->io->error('The element action [ '.$entry.' ] has not been found in [' .  $this->element. ']');
            }
        }
    }
    
    //check the remove element option
    public function removeElement() {
        
        $entry = $this->input->getOption('removeelement');
        
        if(!is_null($entry)){

            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Do you want to remove entire element from registry?',false);

            if($helper->ask($this->input, $this->output, $question)){

                if(array_key_exists($entry, $this->actions)){

                    unset($this->actions[$entry]);

                    $this->io->success('The element [ '.$entry.' ] has been removed');
                    File::put( APP_PROJECT .DS. 'element_actions_levels.php', serialize($this->actions) );

                    @ob_clean();
                    $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
                    print_r($actions);
                }
                else{

                    $this->io->error('The element [ '.$entry.' ] has not been found in the element:actions registry');
                }
            }
        }
    }
    
    //check the removealias option
    public function removeAlias() {
        
        //$this->_checkActionOption($entry, $input);
        $remalias = $this->input->getOption('removealias');

        if(!is_null($remalias) && array_key_exists($remalias, $this->actions[$this->element]['alias'])){

            //add the alias
            unset($this->actions[$this->element]['alias'][$remalias]);

            $this->io->success('The alias for action [ '.$remalias.' ] has been removed');
            File::put( APP_PROJECT .DS. 'element_actions_levels.php', serialize($this->actions) );

            @ob_clean();
            $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
            print_r($actions[$this->element]);
        }
        elseif(!is_null($remalias)){

            $this->io->error('No listed alias for action [ '.$remalias.' ]');
        }
    }
    
    //check the access level option
    public function level() {
        
        $entry = (int) $this->input->getOption('level');

        if(!is_null($entry)){

            $this->actions[$this->element]['level'] = $entry;

            $this->io->success('The level has been added to [ '.  $this->element.' ]');
            File::put( APP_PROJECT .DS. 'element_actions_levels.php', serialize($this->actions) );

            @ob_clean();
            $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
            print_r($actions[$this->element]);
        }
    }
}