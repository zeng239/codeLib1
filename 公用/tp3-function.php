<?php
//for TP3.2	

//读取模型记录值
//$modelName 模型名/表名
//$fieldName 字段名
//$condition 查询条件 如:['id'=>1]
//$is_array 是否返回多条记录，默认是单个记录，为true返回满足条件所有记录array
function getFieldValue($modelName, $fieldName, $condition, $is_array=false){
	return M($modelName)->where($condition)->getField($fieldName, $is_array);
}
