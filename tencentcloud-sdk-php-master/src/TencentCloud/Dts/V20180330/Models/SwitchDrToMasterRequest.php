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
namespace TencentCloud\Dts\V20180330\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method SyncInstanceInfo getDstInfo() 获取灾备实例的信息
 * @method void setDstInfo(SyncInstanceInfo $DstInfo) 设置灾备实例的信息
 * @method string getDatabaseType() 获取数据库的类型  （如 mysql）
 * @method void setDatabaseType(string $DatabaseType) 设置数据库的类型  （如 mysql）
 */

/**
 *SwitchDrToMaster请求参数结构体
 */
class SwitchDrToMasterRequest extends AbstractModel
{
    /**
     * @var SyncInstanceInfo 灾备实例的信息
     */
    public $DstInfo;

    /**
     * @var string 数据库的类型  （如 mysql）
     */
    public $DatabaseType;
    /**
     * @param SyncInstanceInfo $DstInfo 灾备实例的信息
     * @param string $DatabaseType 数据库的类型  （如 mysql）
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
        if (array_key_exists("DstInfo",$param) and $param["DstInfo"] !== null) {
            $this->DstInfo = new SyncInstanceInfo();
            $this->DstInfo->deserialize($param["DstInfo"]);
        }

        if (array_key_exists("DatabaseType",$param) and $param["DatabaseType"] !== null) {
            $this->DatabaseType = $param["DatabaseType"];
        }
    }
}
