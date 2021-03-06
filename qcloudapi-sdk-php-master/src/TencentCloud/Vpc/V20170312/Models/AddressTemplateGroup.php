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
namespace TencentCloud\Vpc\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getAddressTemplateGroupName() 获取IP地址模板集合名称。
 * @method void setAddressTemplateGroupName(string $AddressTemplateGroupName) 设置IP地址模板集合名称。
 * @method string getAddressTemplateGroupId() 获取IP地址模板集合实例ID，例如：ipmg-dih8xdbq。
 * @method void setAddressTemplateGroupId(string $AddressTemplateGroupId) 设置IP地址模板集合实例ID，例如：ipmg-dih8xdbq。
 * @method array getAddressTemplateIdSet() 获取IP地址模板ID。
 * @method void setAddressTemplateIdSet(array $AddressTemplateIdSet) 设置IP地址模板ID。
 * @method string getCreatedTime() 获取创建时间。
 * @method void setCreatedTime(string $CreatedTime) 设置创建时间。
 */

/**
 *IP地址模板集合
 */
class AddressTemplateGroup extends AbstractModel
{
    /**
     * @var string IP地址模板集合名称。
     */
    public $AddressTemplateGroupName;

    /**
     * @var string IP地址模板集合实例ID，例如：ipmg-dih8xdbq。
     */
    public $AddressTemplateGroupId;

    /**
     * @var array IP地址模板ID。
     */
    public $AddressTemplateIdSet;

    /**
     * @var string 创建时间。
     */
    public $CreatedTime;
    /**
     * @param string $AddressTemplateGroupName IP地址模板集合名称。
     * @param string $AddressTemplateGroupId IP地址模板集合实例ID，例如：ipmg-dih8xdbq。
     * @param array $AddressTemplateIdSet IP地址模板ID。
     * @param string $CreatedTime 创建时间。
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
        if (array_key_exists("AddressTemplateGroupName",$param) and $param["AddressTemplateGroupName"] !== null) {
            $this->AddressTemplateGroupName = $param["AddressTemplateGroupName"];
        }

        if (array_key_exists("AddressTemplateGroupId",$param) and $param["AddressTemplateGroupId"] !== null) {
            $this->AddressTemplateGroupId = $param["AddressTemplateGroupId"];
        }

        if (array_key_exists("AddressTemplateIdSet",$param) and $param["AddressTemplateIdSet"] !== null) {
            $this->AddressTemplateIdSet = $param["AddressTemplateIdSet"];
        }

        if (array_key_exists("CreatedTime",$param) and $param["CreatedTime"] !== null) {
            $this->CreatedTime = $param["CreatedTime"];
        }
    }
}
