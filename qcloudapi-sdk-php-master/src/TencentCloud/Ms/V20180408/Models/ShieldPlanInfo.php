<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
namespace TencentCloud\Ms\V20180408\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method integer getTotalCount() 获取加固策略数量
 * @method void setTotalCount(integer $TotalCount) 设置加固策略数量
 * @method array getPlanSet() 获取加固策略具体信息数组
 * @method void setPlanSet(array $PlanSet) 设置加固策略具体信息数组
 */

/**
 *加固策略信息
 */
class ShieldPlanInfo extends AbstractModel
{
    /**
     * @var integer 加固策略数量
     */
    public $TotalCount;

    /**
     * @var array 加固策略具体信息数组
     */
    public $PlanSet;
    /**
     * @param integer $TotalCount 加固策略数量
     * @param array $PlanSet 加固策略具体信息数组
     */
    function __construct()
    {

    }
    /**
     * 内部实现，用户禁止调用
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("TotalCount",$param) and $param["TotalCount"] !== null) {
            $this->TotalCount = $param["TotalCount"];
        }

        if (array_key_exists("PlanSet",$param) and $param["PlanSet"] !== null) {
            $this->PlanSet = [];
            foreach ($param["PlanSet"] as $key => $value){
                $obj = new PlanDetailInfo();
                $obj->deserialize($value);
                array_push($this->PlanSet, $obj);
            }
        }
    }
}
