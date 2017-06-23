<?php
namespace Jenga\App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Jenga\App\Core\App;
use Jenga\App\Project\Security\Acl\ACLCompiler;

/**
 * Builds the project ACL level from the acl\level and acl\alias annotations
 *
 * @author sngumo
 */
class AclCompile extends Command {
    
    protected function configure() {
        
        $this->setName('acl:compile')
                ->setDescription('Builds the project wide ACL levels from the acl\role, acl\level, acl\action and acl\alias annotations');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $io = new SymfonyStyle($input, $output);
        $acl = App::get(ACLCompiler::class);
        
        if($acl){
            $io->newLine();    
            $io->success('ACL roles and levels compiled into Jenga....', TRUE);
        }
    }
}
