<?php echo '<', '?php', "\n"; ?>
namespace app\model;

/**
 * <?php echo $model->getClassNote();?>模型
 *
 * @package app\model
 */
final class <?php echo $model->getClassName(); ?> extends BaseModel {
    protected $name='<?php echo $model->getTableModel()->getName();?>';
    protected $strict = false;
}