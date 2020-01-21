<?php
namespace Application\DI;

use Common\Exceptions\ArgumentInvalidException;
use Laminas\Di\Di;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Di\ServiceLocator\Generator;
use Laminas\Di\Definition\CompilerDefinition;
use Laminas\Di\DefinitionList;
use Laminas\Di\Definition;
use Application\Context;

class DIConfigurator
{
    private $definitionsPath     = '';
    private $modulesPath         = '';
    private $definitionFileName  = 'module-definitions.php';
    private $serviceManager      = null;
    private $dependenciesDummies =
        [ 'Doctrine\ORM\EntityManager'                     => []
        , 'Doctrine\Common\Persistence\ObjectManager'      => []
        , 'Laminas\EventManager\EventManagerInterface'        => []
        , 'Laminas\ServiceManager\ServiceLocatorInterface'    => []
        , 'Laminas\Di\ServiceLocatorInterface'                => []
        , 'Laminas\InputFilter\InputFilterInterface'          => []
        , 'Laminas\Form'                                      => []
        , 'Laminas\Form\Factory'                              => []
        , 'Laminas\Form\FormInterface'                        => []
        , 'DateTime'                                       => []
        , 'Application\Filters\Set\CommonFactoryInterface' => []
        , 'Application\DateService'                        => []
        ];

    public function __construct($definitionsPath, $modulesPath, ServiceManager $serviceManager)
    {
        $this->definitionsPath = $definitionsPath;
        $this->modulesPath     = $modulesPath;
        $this->serviceManager  = $serviceManager;
    }

    public function configure()
    {
        $compileDefinitions = !file_exists("{$this->definitionsPath}/{$this->definitionFileName}");
        $buildLocator       = !file_exists($this->definitionsPath . '/Context.php');

        if ($compileDefinitions) {
            $this->compileDefinitions();
        }

        if ($buildLocator) {
            $this->buildServiceLocator();
        }
    }

    public function getServiceLocator()
    {
        $context = new Context();
        $context->set('Doctrine\ORM\EntityManager'                  , $this->serviceManager->get('Doctrine\ORM\EntityManager'));
        $context->set('Doctrine\Common\Persistence\ObjectManager'   , $this->serviceManager->get('Doctrine\ORM\EntityManager'));
        $context->set('Laminas\EventManager\EventManagerInterface'     , $this->serviceManager->get('Laminas\EventManager\EventManagerInterface'));
        $context->set('Laminas\ServiceManager\ServiceLocatorInterface' , $this->serviceManager->get('Laminas\ServiceManager\ServiceLocatorInterface'));
        $context->set('Laminas\Di\ServiceLocatorInterface'             , $context);

        return $context;
    }

    private function compileDefinitions()
    {
        $includeModuleNames = [
            'Application'
        ,   'Common'
        ,   'Expense'
        //,   'User'
        ];

        $excludeModuleDirs = [
            'Application' => ['DI'      , 'Filters' , 'Entity']
        //,   'Expense'     => ['Form']
        ];

        $diCompiler         = new CompilerDefinition();
        $excludeDirsModules = array_keys($excludeModuleDirs);
        foreach ($includeModuleNames as $moduleName) {
            if (in_array($moduleName, $excludeDirsModules)) {
                $this->addSeveralDirs($moduleName, $excludeModuleDirs[$moduleName], $diCompiler);
            } else {
                $diCompiler->addDirectory("{$this->modulesPath}/{$moduleName}/src");
            }
        }

        $diCompiler->compile();

        $f = "{$this->definitionsPath}/{$this->definitionFileName}";
        file_put_contents(
            $f,
            '<?php return ' . var_export($diCompiler->toArrayDefinition()->toArray(), true) . ';'
        );
        chmod($f, 0666);
    }

    private function addSeveralDirs($moduleName, $excludeModuleDirs, CompilerDefinition $diCompiler)
    {
        if (is_string($excludeModuleDirs) || is_array($excludeModuleDirs)) {
            $moduleDirs    = glob("{$this->modulesPath}/{$moduleName}/src/{$moduleName}/*" , GLOB_ONLYDIR);
            $canIncludeDir =  function ($dir) use ($excludeModuleDirs) {
                return
                    is_string($excludeModuleDirs)
                        ? basename($dir) != $excludeModuleDirs
                        : ! in_array(basename($dir), $excludeModuleDirs);
            };
            foreach ($moduleDirs as $dir) {
                if ($canIncludeDir($dir)) {
                    $diCompiler->addDirectory($dir);
                }
            }
        } else {
            throw new ArgumentInvalidException('$excludeModuleDirs');
        }
    }

    private function buildServiceLocator()
    {
        $locatorFileName = $this->definitionsPath . '/Context.php';
        $definitionList  = new DefinitionList([
            new Definition\ArrayDefinition(include "{$this->definitionsPath}/{$this->definitionFileName}"),
            new Definition\ArrayDefinition($this->dependenciesDummies)
        ]);

        $di = new Di($definitionList);

        (new PreferencesProvider())->applyPreferences($di->instanceManager(), $this->serviceManager);

        $generator = new Generator($di);

        $generator->setNamespace('Application')
                  ->setContainerClass('Context');

        $file = $generator->getCodeGenerator();
        $file->setFilename($locatorFileName);
        $file->write();

        $this->cleanLocatorFile($locatorFileName, array_keys($this->dependenciesDummies));
    }

    /**
     * @todo clean unused methods definitions too
     * @param string  $fileName
     * @param array $dependenciesDummies
     */
    private function cleanLocatorFile($fileName, array $dependenciesDummies)
    {
        $patterns = [];
        foreach ($dependenciesDummies as $dependency) {
            $screened   = addslashes($dependency);
            $replaced   = stripslashes($dependency);
            $patterns[] = "|case '{$screened}'.*|i";
            $patterns[] = '|return \$this->get' . $replaced . '().*|i';
        }

        file_put_contents(
            $fileName,
            preg_replace($patterns, '', file_get_contents($fileName))
        );
        chmod($fileName, 0666);
    }
}