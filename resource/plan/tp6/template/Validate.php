<?php echo '<', '?php', "\n"; ?>
namespace app\validate;

use think\Validate;

/**
 * 账户验证
 *
 * @package app\validate
 */
final class <?php echo $model->getClassName();?>Validate extends Validate {
    //TODO:要验证的数据
    protected $rule=[
    ];
    //TODO:自定义错误提示
    protected $message=[
    ];
    //TODO:后台验证场景, '前台验证场景'???
    protected $scene=[
        'save'=>[]
    ];
}