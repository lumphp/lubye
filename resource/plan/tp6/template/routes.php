
//<?php echo $model->getClassNote(),"\n";?>
Route::group('<?php echo $model->getClassName();?>', function(){
    Route::rule('index','admin/<?php echo $model->getClassName();?>/index','post');
    Route::rule('changeStatus','admin/<?php echo $model->getClassName();?>/changeStatus','get');
    Route::rule('del','admin/<?php echo $model->getClassName();?>/del','get');
    Route::rule('save','admin/<?php echo $model->getClassName();?>/del','save');
})->middleware(['\app\http\middleware\Auth', '\app\http\middleware\AdminPermission', '\app\http\middleware\AdminLog']);