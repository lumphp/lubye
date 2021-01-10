<?php
namespace Lum\Lubye;

use Lum\Lubye\Utils\Constant;
use Lum\Lubye\Utils\Path;
use Lum\Lubye\Utils\Registry;

/**
 * Class Bootstrap
 *
 * @package Lum\Lubye
 */
class Bootstrap
{
    /**
     * 运行
     *
     * @param string $output
     * @param string $dataSource
     * @param Path $path
     */
    public function run(string $output = '', string $dataSource = '', Path $path)
    {
        $registry = Registry::getInstance();
        $config = $registry->get(Constant::PLAN_CONFIG_KEY);
        $generate_plan = new GenerateService($config, $path);
        $generate_plan->doPlan($output, $dataSource);
    }
}