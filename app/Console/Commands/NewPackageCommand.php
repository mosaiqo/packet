<?php namespace Mosaiqo\Packet\Console\Commands;

use Illuminate\Console\Command;
use Mosaiqo\Packet\Helpers\PackageHelper;
use Mosaiqo\Packet\Helpers\TemplateHelper;

class NewPackageCommand extends Command
{

    protected $helper;

    protected $template;

    protected $vendor;

    protected $package;
    
    protected $packageDescription;

    protected $fullPath;

    protected $path;

    protected $templatesPath = __DIR__.'/../../../resources/templates';

	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:new 
                            {name : The package name.} 
                            {--vendor= : The vendor name if not provided it will look for the config file.}
                            {--description=This is a fake description change it. : The description for your package.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new package';

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
        $this->createDirectoryStructure();
        $this->copySkeleton();
        $this->initComposer();
        $this->initRepository();
        $this->initFlow();
    }

    protected function createDirectoryStructure ()
    {
        $this->createPackagesDirectory();
        $this->createVendorDirectory();
        $this->createPackageDirectory();
        $this->createSrcDirectory();
        $this->createTestsDirectory();
        $this->createResourcesDirectory();
        $this->createAssetsDirectory();
        $this->createTranslationsDirectory();
        $this->createViewsDirectory();
        $this->createConfigDirectory();
        $this->createPublicAssetsDirectory();
        $this->createDatabaseDirectory();   
        
    } 

    protected function instantiateVariables ()
    {
        // Common variables
        $vendor = $this->option('vendor')?:config('packet.vendor');

        if($vendor == null || $vendor == '')
        {
            $vendor = $this->output->ask('You have to provide a vendor name');
            if($vendor == null || $vendor == '')
            {
                throw new \Exception('You have to provide a vendor');
            }
        }

        $this->vendor = strtolower($vendor);
        $this->packageDescription = $this->option('description');
        $this->package = strtolower($this->argument('name'));
        $this->path = getcwd()."/".config('packet.path','packages')."/";
        $this->fullPath = $this->path.ucfirst($this->vendor).'/'.ucfirst($this->package);
    
    }

    protected function checkIfPackageAllreadyExists ()
    {
        $this->info('Creating package '.$this->vendor.'\\'.$this->package.'...');
        $this->helper->checkPackageDoNotExist($this->path, $this->vendor, $this->package);    
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

    protected function createSrcDirectory ()
    {
        $directory = config('packet.directories.src', 'app');
        $this->helper->makeDir($this->fullPath."/{$directory}");    
    }

    protected function createTestsDirectory ()
    {
        $tests = config('packet.directories.tests', 'tests');
        $this->helper->makeDir($this->fullPath."/{$tests}");    
    }

    protected function copySkeleton ()
    {
       $this->info('Copying skeleton...');
       
       $this->copyGitignore();
       $this->copyLicense();
       $this->copyComposer();
       $this->copyContributing();
       $this->copyReadme();
       $this->copyChangelog();
       $this->copySkeletonClass();
       $this->copyServiceProvider();
       $this->copyPHPUnit();
    }

    protected function copyGitignore ()
    {
      $this->template->render([], $this->templatesPath.'/.gitignore',
            $this->fullPath. '/.gitignore'
        );      
    }

    protected function copyLicense ()
    {
        $this->template->render([
            'author_email' => config('packet.email'),
            'author_name' => config('packet.name'),
            'date' => date('Y')
            ], $this->templatesPath.'/LICENSE.md',
            $this->fullPath. '/LICENSE.md'
        );    
    }

    protected function copyComposer()
    {
        $this->template->render([
            'package' => $this->package,
            'packageUC' => ucfirst($this->package),
            'vendor' => $this->vendor,
            'vendorUC' =>  ucfirst($this->vendor),
            'repository' =>  config('packet.repository', 'github.com'),
            'description' => $this->packageDescription,
            'author_email' => config('packet.email'),
            'author_website' => config('packet.homepage'),
            'author_role' => config('packet.role'),
            'author_name' => config('packet.name'),
            'src' => config('packet.directories.src', 'app'),
            'tests' => config('packet.directories.tests', 'tests'),
            'license' => config('packet.license', 'MIT')
            ], $this->templatesPath.'/composer.json',
            $this->fullPath. '/composer.json'
        );    
    }

    protected function copyContributing()
    {
        $this->template->render([
            'package' => $this->package,
            'vendor' => $this->vendor,
            'repository' =>  config('packet.repository', 'github.com'),
            ], $this->templatesPath.'/CONTRIBUTING.md',
            $this->fullPath. '/CONTRIBUTING.md'
        );    
    }


    protected function copyReadme()
    {
        $this->template->render([
            'package' => $this->package,
            'packageUC' => ucfirst($this->package),
            'vendor' => $this->vendor,
            'vendorUC' =>  ucfirst($this->vendor),
            'repository' =>  config('packet.repository', 'github.com'),
            'description' => $this->packageDescription,
            'author_email' => config('packet.email'),
            'author_username' => config('packet.username'),
            'author_website' => config('packet.homepage'),
            'author_role' => config('packet.role'),
            'author_name' => config('packet.name'),
            'license' => config('packet.license', 'MIT')
            ], $this->templatesPath.'/README.md',
            $this->fullPath. '/README.md'
        );    
    }

    protected function copyChangelog()
    {
        $this->template->render([
            'package' => $this->package,
            ], $this->templatesPath.'/CHANGELOG.md',
            $this->fullPath. '/CHANGELOG.md'
        );    
    }

    protected function copySkeletonClass()
    {
        $package = ucfirst($this->package);
        $directory = config('packet.directories.src', 'app');
        $this->template->render([
            'package' => $package,
            'vendor' => ucfirst($this->vendor)
            ], $this->templatesPath.'/src/SkeletonClass.php',
            $this->fullPath. "/{$directory}/{$package}.php"
        );    
    }


    protected function copyServiceProvider()
    {
        $package = ucfirst($this->package);
        $directory = config('packet.directories.src', 'app');
        $this->template->render([
            'package' => $package,
            'vendor' => ucfirst($this->vendor)
            ], $this->templatesPath.'/src/ServiceProvider.php',
            $this->fullPath. "/{$directory}/{$package}ServiceProvider.php"
        );    
    }

    protected function copyPHPUnit()
    {
        $package = ucfirst($this->package);
        $this->template->render([
            'package' => $package,
            'vendor' => ucfirst($this->vendor),
            'test_directory' => config('packet.directories.tests', 'tests')
            ], $this->templatesPath.'/phpunit.xml',
            $this->fullPath. "/phpunit.xml"
        );    
    }
    
    protected function createAssetsDirectory ()
    {
        $directory = config('packet.directories.resources.src', 'resources');
        $assets = config('packet.directories.resources.assets', 'assets');
        $this->helper->makeDir($this->fullPath . "/{$directory}/{$assets}");  
    }

    protected function createConfigDirectory()
    {
        $directory = config('packet.directories.resources.config', 'config');
        $this->helper->makeDir($this->fullPath . "/{$directory}");   
    }
    protected function createResourcesDirectory()
    {
        $directory = config('packet.directories.resources.src', 'resources');
        $this->helper->makeDir($this->fullPath . "/{$directory}");
    }

    protected function createPublicAssetsDirectory()
    {
        $directory = config('packet.directories.public', 'public');
        $this->helper->makeDir($this->fullPath . "/{$directory}");  
    }
    protected function createTranslationsDirectory()
    {
        $directory = config('packet.directories.resources.src', 'resources');
        $lang = config('packet.directories.resources.lang', 'lang');

        $this->helper->makeDir($this->fullPath . "/{$directory}/{$lang}");  
        $this->helper->makeDir($this->fullPath . "/{$directory}/{$lang}/en");  
    }

    protected function createViewsDirectory()
    {
        $directory = config('packet.directories.resources.src', 'resources');
        $views = config('packet.directories.resources.views', 'views');

        $this->helper->makeDir($this->fullPath . "/{$directory}/{$views}");
    }

    protected function createDatabaseDirectory()
    {
        $directory = config('packet.directories.database.src', 'database');
        $this->helper->makeDir($this->fullPath . "/{$directory}"); 

        $migrations = config('packet.directories.database.migrations', 'migrations');
        $this->helper->makeDir($this->fullPath .  "/{$directory}/{$migrations}");

        $factories = config('packet.directories.database.factories', 'factories');  
        $this->helper->makeDir($this->fullPath .  "/{$directory}/{$factories}");  

        $seeds = config('packet.directories.database.seeds', 'seeds');  
        $this->helper->makeDir($this->fullPath .  "/{$directory}/{$seeds}");  
    }
    
    protected function initRepository()
    {
        chdir($this->fullPath);
        exec('git init');
        exec('git add -A');
        exec('git commit -m "Initial Commit"');
    }

    protected function initFlow()
    {
        chdir($this->fullPath);
        exec('git flow init -fd');
    }

    protected function initComposer()
    {
        chdir($this->fullPath);
        exec('composer install');
    }
}