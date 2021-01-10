import axios from '@/libs/api.request'

/**
 * 获取<?php echo $model->getClassNote();?>列表数据
 * @returns {wx.RequestTask | never}
 */
export const getList = (params) => {
    return axios.request({
        url: '<?php echo $model->getClassName();?>/index',
        method: 'get',
        params:params||{}
    })
}

/**
 * 显示/隐藏<?php echo $model->getClassNote(),"\n";?>
 * @param status
 * @param id
 * @returns {wx.RequestTask | never}
 */
export const changeStatus = (status, id) => {
    return axios.request({
        url: '<?php echo $model->getClassName();?>/changeStatus',
        method: 'get',
        params: {
            status: status,
            id: id
        }
    })
}

/**
 * 新增<?php echo $model->getClassNote(),"\n";?>
 * @param data
 * @returns {wx.RequestTask | never}
 */
export const add = (data) => {
    return axios.request({
        url: '<?php echo $model->getClassName();?>/add',
        method: 'post',
        data
    })
}

/**
 * 编辑<?php echo $model->getClassNote(),"\n";?>
 * @param data
 * @returns {wx.RequestTask | never}
 */
export const edit = (data) => {
    return axios.request({
        url: '<?php echo $model->getClassName();?>/edit',
        method: 'post',
        data
    })
}

/**
 * 删除<?php echo $model->getClassNote(),"\n";?>
 * @param status
 * @param id
 * @returns {wx.RequestTask | never}
 */
export const del = (id) => {
    return axios.request({
        url: '<?php echo $model->getClassName();?>/del',
        method: 'get',
        params: {
            id: id
        }
    })
}
