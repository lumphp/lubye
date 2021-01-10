<?php
namespace Lum\Lubye;

use Lum\Lubye\Models\GeneratePlanModel;
use Lum\Lubye\Utils\Config;
use Lum\Lubye\Utils\Constant;
use Lum\Lubye\Utils\DB;
use Lum\Lubye\Utils\Path;
use Lum\Lubye\Utils\Registry;

/**
 * Class GenerateService
 *
 * @package Lum\Lubye
 */
class GenerateService
{
    /**
     * @var Config
     */
    protected $config;
    protected $data_source;
    protected $templates;
    protected $model;
    private $path;

    /**
     * GenerateService constructor.
     *
     * @param \Lum\Lubye\Utils\Config $config
     * @param Path $path
     */
    public function __construct($config, Path $path)
    {
        $this->config = $config;
        $this->path = $path;
    }

    /**
     * @param string $outputDir
     * @param string $dataSourceName
     *
     * @return $this
     */
    public function doPlan(string $outputDir = '', string $dataSourceName = '')
    {
        //获取plan配置
        $plan_config = $this->config;
        $rootPath = $this->path->getRootPath();
        $planName = $plan_config->get('plan');
        $framework = $plan_config->get('framework');
        $planTemplate = $planName;
        if ($framework) {
            $planTemplate = str_replace('.', '/', $framework.'/'.$planName);
        }
        //获取plan模板
        $template_path = vsprintf('%splan/%s/template/', [$this->path->getResourcePath(), $planTemplate]);
        //获取数据源名称
        $data_source_name = $dataSourceName ? $dataSourceName : $plan_config->get('dataSource');
        //输出
        $output_path = sprintf($plan_config->get('outputPath'), $rootPath, $outputDir);
        echo "\ntemplatePath=", $template_path;
        echo "\noutputPath=", $output_path;
        $registry = Registry::getInstance();
        $db_config = $registry->get(Constant::DB_CONFIG_KEY);
        $connections = $db_config->get('connections');
        $dataSource = $this->bindDataSource($data_source_name, $connections);
        $generate_plan_model = new GeneratePlanModel(
            $dataSource, $plan_config->get('generator'), $template_path
        );
        $generate_plan_model->build()->output($output_path);

        return $this;
    }

    /**
     * 绑定数据源
     *
     * @param string $data_source
     * @param \Lum\Lubye\Utils\Config $db_config *
     *
     * @return array
     */
    protected function bindDataSource($data_source, $db_config) : array
    {
        $all_tables = [];
        if ($data_source) {
            $conn_config = $db_config->get($data_source);
            $all_tables = $this->getTablesByConnectionName($conn_config);
        }
        if (!$all_tables) {
            return [];
        }
        $result = [];
        foreach ($all_tables as $table => $tableInfo) {
            $columns = $this->getColumns($tableInfo);
            $result[$table] = [
                'comment' => $tableInfo->Comment,
                'primaryKey' => $columns['primaryKey'],
                'columns' => $columns['allFields'],
            ];
        }

        return $result;
    }

    /**
     * @param $tableInfo
     *
     * @return array
     */
    protected function getColumns($tableInfo) : array
    {
        $result = [];
        if (!$tableInfo) {
            return $result;
        }
        $columns = isset($tableInfo->Columns) ? $tableInfo->Columns : null;
        if (!$columns || !is_array($columns)) {
            return $result;
        }
        $primary_key = [];
        foreach ($columns as $column) {
            $is_primary = $column->Key == 'PRI' ? true : false;
            if ($is_primary) {
                $primary_key[$column->Field] = $column->Field;
            }
            $typeInfo = explode('(', $column->Type);
            $type = $typeInfo[0] ?? $column->Type;
            $result[strtolower($column->Field)] = [
                'name' => $column->Field,
                'type' => $type,
                'propertyType' => $this->getPropertyType($type),
                'comment' => $column->Comment,
            ];
        }

        return ['primaryKey' => $primary_key, 'allFields' => $result];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getPropertyType(string $type) : string
    {
        $typeMap = [
            'tinyint' => 'int',
            'smallint' => 'int',
            'mediumint' => 'int',
            'int' => 'int',
            'integer' => 'int',
            'bigint' => 'int',
            'float' => 'float',
            'double' => 'double',
            'real' => 'double',
            'decimal' => 'double',
            'numeric' => 'double',
        ];
        $type = $typeMap[$type] ?? 'string';

        return $type;
    }

    /**
     * get all tables and files by connection name
     *
     * @param \Lum\Lubye\Utils\Config $conn_config
     *
     * @return array
     */
    protected function getTablesByConnectionName($conn_config) : array
    {
        $db = DB::getInstance($conn_config);
        $st = $db->execute('SHOW TABLE STATUS');
        $result = [];
        while ($row = $st->fetchObject()) {
            $sql = sprintf('SHOW FULL COLUMNS FROM `%s`', $row->Name);
            $st_columns = $db->execute($sql);
            $columns = [];
            if ($st_columns) {
                while ($column = $st_columns->fetchObject()) {
                    $columns[$column->Field] = $column;
                }
            }
            $row->Columns = $columns;
            $result[strtolower($row->Name)] = $row;
        }

        return $result;
    }
}