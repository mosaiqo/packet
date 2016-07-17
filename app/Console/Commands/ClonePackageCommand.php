<?php namespace Mosaiqo\Packet\Console\Commands;

use Illuminate\Console\Command;
use Mosaiqo\Packet\Helpers\PackageHelper;
use Mosaiqo\Packet\Helpers\TemplateHelper;

class ClonePackageCommand extends Command
{

    protected $helper;

    protected $template;

    protected $vendor;

    protected $package;

    protected $gitPackage;
    
    protected $repository;

    protected $HTTPSrepository;

    protected $GITrepository;

    protected $fullPath;

    protected $path;

    protected $templatesPath = __DIR__.'/../../../resources/templates';

	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:get 
                            {name : The package name.} 
                            {--repository= : The repository url.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clones an existen package from a repository';

    public function __construct (PackageHelper $helper, TemplateHelper $template)
    {
        parent::__construct();
        $this->helper = $helper;
        $this->template = $template;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->instantiateVariables();
        $this->checkIfPackageAllreadyExists();
        $this->checkIfRepositoryExists();
        $this->createDirectoryStructure();
        $this->cloneRepository();
        $this->initFlow();
        $this->initComposer();
    }

    protected function createDirectoryStructure ()
    {
        $this->createPackagesDirectory();
        $this->createVendorDirectory();
        $this->createPackageDirectory();        
    } 

    protected function instantiateVariables ()
    {
        // Common variables
        $this->repository = $this->option('repository')?:null;

        if(!$this->repository)
        {
            $vendor = config('packet.vendor');

            if($vendor == null || $vendor == '')
            {
                $vendor = $this->output->ask('You have to provide a vendor name');
                if($vendor == null || $vendor == '')
                {
                    throw new \Exception('You have to provide a vendor');
                }
            }

        }
        $this->vendor = strtolower($vendor);
        $this->package = str_replace("-", "", strtolower($this->argument('name')));
        $this->gitPackage = strtolower($this->argument('name'));
        $this->path = getcwd()."/".config('packet.path','packages')."/";
        $this->fullPath = $this->path.ucfirst($this->vendor).'/'. ucfirst($this->package);


        $repoHost = config('packet.repository');

        $this->repository  = $this->HTTPSrepository = "https://{$repoHost}/{$this->vendor}/{$this->gitPackage}.git";
        $this->GITrepository = "git@{$repoHost}:{$this->vendor}/{$this->gitPackage}.git";
    
    }


    protected function checkIfPackageAllreadyExists ()
    {

        $this->info('Checking for package '.$this->vendor.'\\'.$this->package.'...');
        $this->helper->checkPackageDoNotExist($this->path, $this->vendor, $this->package);    
    }


    protected function checkIfRepositoryExists ()
    {
        $this->info('Checking for repository '. $this->repository . '...');
       
        if(!$this->helper->ping($this->HTTPSrepository))
            throw new \Exception('The repository ['.$this->repository.'] does not exist.');
        
    }

    protected function createPackagesDirectory ()
    {
        $this->helper->makeDir($this->path);    
    }

    protected function createVendorDirectory ()
    {
        $this->helper->makeDir($this->path.ucfirst($this->vendor));    
    }

    protected function createPackageDirectory ()
    {
        $this->helper->makeDir($this->fullPath);    
    }

    protected function cloneRepository ()
    {
        $this->info('Cloning package...');
        exec("git clone {$this->GITrepository} $this->fullPath");
       
    }

    protected function initFlow()
    {
        chdir($this->fullPath);
        exec('git flow init -fd');
        exec("git pull origin develop");
    }

    protected function initComposer()
    {
        chdir($this->fullPath);
        exec('composer install');
    }
}