<?php

namespace EdpSuperluminal;

use Zend\Code\Scanner\FileScanner;

class CacheBuilder
{
    protected $knownClasses = array();

    /**
     * @var CacheCodeGenerator
     */
    protected $cacheCodeGenerator;

    /**
     * @var ShouldCacheClassSpecification
     */
    protected $shouldCacheClass;

    /**
     * @param CacheCodeGenerator $cacheCodeGenerator
     * @param ShouldCacheClassSpecification $shouldCacheClass
     */
    public function __construct(CacheCodeGenerator $cacheCodeGenerator, ShouldCacheClassSpecification $shouldCacheClass)
    {
        $this->cacheCodeGenerator = $cacheCodeGenerator;
        $this->shouldCacheClass = $shouldCacheClass;
    }

    /**
     * Cache declared interfaces and classes to a single file
     *
     * @param string
     * @return void
     */
    public function cache($classCacheFilename)
    {
        if (file_exists($classCacheFilename)) {
            $this->reflectClassCache($classCacheFilename);
            $code = file_get_contents($classCacheFilename);
        } else {
            $code = "<?php\n";
        }

        $classes = array_merge(get_declared_interfaces(), get_declared_classes());

        foreach ($classes as $class) {
            if (!$this->shouldCacheClass->isSatisfiedBy($class, $this->knownClasses)) {
                continue;
            }

            $this->knownClasses[] = $class;

            $code .= $this->cacheCodeGenerator->getCacheCode($class);
        }

        file_put_contents($classCacheFilename, $code);

        // minify the file
        file_put_contents($classCacheFilename, php_strip_whitespace($classCacheFilename));
    }

    /**
     * Determine what classes are present in the cache
     *
     * @param $classCacheFilename
     * @return void
     */
    protected function reflectClassCache($classCacheFilename)
    {
        $scanner = new FileScanner($classCacheFilename);
        $this->knownClasses = array_unique($scanner->getClassNames());
    }
}