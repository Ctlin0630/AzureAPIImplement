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
namespace TencentCloud\Mariadb\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getStartTime() 获取起始时间，形如 2018-03-24 23:59:59
 * @method void setStartTime(string $StartTime) 设置起始时间，形如 2018-03-24 23:59:59
 * @method string getEndTime() 获取结束时间，形如 2018-03-24 23:59:59
 * @method void setEndTime(string $EndTime) 设置结束时间，形如 2018-03-24 23:59:59
 * @method array getData() 获取监控数据
 * @method void setData(array $Data) 设置监控数据
 */

/**
 *监控数据
 */
class MonitorData extends AbstractModel
{
    /**
     * @var string 起始时间，形如 2018-03-24 23:59:59
     */
    public $StartTime;

    /**
     * @var string 结束时间，形如 2018-03-24 23:59:59
     */
    public $EndTime;

    /**
     * @var array 监控数据
     */
    public $Data;
    /**
     * @param string $StartTime 起始时间，形如 2018-03-24 23:59:59
     * @param string $EndTime 结束时间，形如 2018-03-24 23:59:59
     * @param array $Data 监控数据
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
        if (array_key_exists("StartTime",$param) and $param["StartTime"] !== null) {
            $this->StartTime = $param["StartTime"];
        }

        if (array_key_exists("EndTime",$param) and $param["EndTime"] !== null) {
            $this->EndTime = $param["EndTime"];
        }

        if (array_key_exists("Data",$param) and $param["Data"] !== null) {
            $this->Data = $param["Data"];
        }
    }
}
