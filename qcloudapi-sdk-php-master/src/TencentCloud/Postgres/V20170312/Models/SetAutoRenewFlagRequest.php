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
namespace TencentCloud\Postgres\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method array getDBInstanceIdSet() 获取实例ID数组
 * @method void setDBInstanceIdSet(array $DBInstanceIdSet) 设置实例ID数组
 * @method integer getAutoRenewFlag() 获取续费标记。0-正常续费；1-自动续费；2-到期不续费
 * @method void setAutoRenewFlag(integer $AutoRenewFlag) 设置续费标记。0-正常续费；1-自动续费；2-到期不续费
 */

/**
 *SetAutoRenewFlag请求参数结构体
 */
class SetAutoRenewFlagRequest extends AbstractModel
{
    /**
     * @var array 实例ID数组
     */
    public $DBInstanceIdSet;

    /**
     * @var integer 续费标记。0-正常续费；1-自动续费；2-到期不续费
     */
    public $AutoRenewFlag;
    /**
     * @param array $DBInstanceIdSet 实例ID数组
     * @param integer $AutoRenewFlag 续费标记。0-正常续费；1-自动续费；2-到期不续费
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
        if (array_key_exists("DBInstanceIdSet",$param) and $param["DBInstanceIdSet"] !== null) {
            $this->DBInstanceIdSet = $param["DBInstanceIdSet"];
        }

        if (array_key_exists("AutoRenewFlag",$param) and $param["AutoRenewFlag"] !== null) {
            $this->AutoRenewFlag = $param["AutoRenewFlag"];
        }
    }
}
