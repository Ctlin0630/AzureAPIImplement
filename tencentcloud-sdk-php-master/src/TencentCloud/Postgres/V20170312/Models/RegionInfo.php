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
 * @method string getRegion() 获取该地域对应的英文名称
 * @method void setRegion(string $Region) 设置该地域对应的英文名称
 * @method string getRegionName() 获取该地域对应的中文名称
 * @method void setRegionName(string $RegionName) 设置该地域对应的中文名称
 * @method integer getRegionId() 获取该地域对应的数字编号
 * @method void setRegionId(integer $RegionId) 设置该地域对应的数字编号
 * @method string getRegionState() 获取可用状态，UNAVAILABLE表示不可用，AVAILABLE表示可用
 * @method void setRegionState(string $RegionState) 设置可用状态，UNAVAILABLE表示不可用，AVAILABLE表示可用
 */

/**
 *描述地域的编码和状态等信息
 */
class RegionInfo extends AbstractModel
{
    /**
     * @var string 该地域对应的英文名称
     */
    public $Region;

    /**
     * @var string 该地域对应的中文名称
     */
    public $RegionName;

    /**
     * @var integer 该地域对应的数字编号
     */
    public $RegionId;

    /**
     * @var string 可用状态，UNAVAILABLE表示不可用，AVAILABLE表示可用
     */
    public $RegionState;
    /**
     * @param string $Region 该地域对应的英文名称
     * @param string $RegionName 该地域对应的中文名称
     * @param integer $RegionId 该地域对应的数字编号
     * @param string $RegionState 可用状态，UNAVAILABLE表示不可用，AVAILABLE表示可用
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
        if (array_key_exists("Region",$param) and $param["Region"] !== null) {
            $this->Region = $param["Region"];
        }

        if (array_key_exists("RegionName",$param) and $param["RegionName"] !== null) {
            $this->RegionName = $param["RegionName"];
        }

        if (array_key_exists("RegionId",$param) and $param["RegionId"] !== null) {
            $this->RegionId = $param["RegionId"];
        }

        if (array_key_exists("RegionState",$param) and $param["RegionState"] !== null) {
            $this->RegionState = $param["RegionState"];
        }
    }
}
