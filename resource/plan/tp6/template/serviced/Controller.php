<?php echo '<', '?php', "\n"; ?>
namespace <?php echo $model->getNamespace();?>controller;

use <?php echo $model->getBaseNamespace();?>AbstractBaseController;
use <?php echo $model->getBaseNamespace();?>util\BaseErrCode;
use <?php echo $model->getNamespace();?>model\<?php echo $model->getClassName();?>;
use Exception;
use think\response\Json;

final class <?php echo $model->getClassName();?>Controller extends AbstractBaseController {
    /**
     * 获取<?php echo $model->getClassNote();?>列表
     *
     * @return array|Json
     */
    public function index() {
        try {
            $limit=intval($this->request->post('size', config('apiadmin.PAGE_SIZE')));
            $start=intval($this->request->post('page', 1));
            //$keywords=$this->request->post('keywords', '');
            $status=$this->request->post('status', '');
            $obj=new <?php echo $model->getClassName();?>;
            if (!empty($status)) {
                $obj=$obj->where(['is_enable'=> 'Y'===$status?'Y':'N']);
            }
            $listObj=$obj->paginate([
                'list_rows'=>$limit,
                'page'=>$start
            ],false)->toArray();
            return $this->buildSuccess([
                'list'=>isset($listObj['data']) && $listObj['data'] ? $listObj['data'] : null,
                'count'=>intval(isset($listObj['total']) && $listObj['total'] ? $listObj['total'] : 0)
            ]);
        } catch(Exception $e) {
        }
        return $this->buildFailed(ReturnCode::DB_READ_ERROR);
    }

    /**
     * 获取全部有效的<?php echo $model->getClassNote(),"\n";?>
     *
     * @return array|Json
     */
    public function getAll() {
        try {
            $listInfo=(new <?php echo $model->getClassName();?>)->where(['is_enable'=>'Y'])->select();
            return $this->buildSuccess([
                'list'=>$listInfo
            ]);
        } catch(Exception $e) {
            //TODO:log exception
        }
        return $this->buildFailed(BaseErrCode::DB_READ_ERROR);
    }

    /**
     * 保存<?php echo $model->getClassNote(),"\n";?>
     * @return array|Json
     */
    public function save() {
        try {
            $postData=$this->request->except(['accountId'],'post');
            $id=trim(isset($postData['id']) ? $postData['id'] : '');
            if ($id) {
                <?php echo $model->getClassName();?>::update($postData, [
                    'id'=>$id
                ]);
                return $this->buildSuccess();
            }
            $model=new <?php echo $model->getClassName();?>;
            if ($model->save($postData) && isset($model->id) && $model->id) {
                $hidden=[ 'create_time',  'update_time',  'state', 'is_enable' ];
                return $this->buildSuccess($model->hidden($hidden)->toArray());
            }
        } catch(Exception $e) {
        }
        return $this->buildFailed(BaseErrCode::DB_SAVE_ERROR);
    }

    /**
     * <?php echo $model->getClassNote();?>状态编辑
     *
     * @return array|Json
     */
    public function changeStatus() {
        $id=trim($this->request->post('id'));
        $status=$this->request->post('status');
        $res=<?php echo $model->getClassName();?>::update([
            'is_enable'=>'Y' === $status ? 'Y' : 'N'
        ], ['id'=>$id]);
        if ($res === false) {
            return $this->buildFailed(BaseErrCode::DB_SAVE_ERROR);
        }
        return $this->buildSuccess();
    }

    /**
     *  <?php echo $model->getClassNote();?>删除
     *
     * @return array|Json
     */
    public function del() {
        $id=intval($this->request->post('id'));
        if (!$id) {
            return $this->buildFailed(BaseErrCode::EMPTY_PARAMS, '缺少必要参数');
        }
        //TODO:
        <?php echo $model->getClassName();?>::update(['is_enable'=>'N'], ['id'=>$id]);
        return $this->buildSuccess();
    }
}
