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
 * @method string getVpcId() 获取待操作的VPC实例ID。可通过DescribeVpcs接口返回值中的VpcId获取。
 * @method void setVpcId(string $VpcId) 设置待操作的VPC实例ID。可通过DescribeVpcs接口返回值中的VpcId获取。
 * @method string getRouteTableName() 获取路由表名称，最大长度不能超过60个字节。
 * @method void setRouteTableName(string $RouteTableName) 设置路由表名称，最大长度不能超过60个字节。
 */

/**
 *CreateRouteTable请求参数结构体
 */
class CreateRouteTableRequest extends AbstractModel
{
    /**
     * @var string 待操作的VPC实例ID。可通过DescribeVpcs接口返回值中的VpcId获取。
     */
    public $VpcId;

    /**
     * @var string 路由表名称，最大长度不能超过60个字节。
     */
    public $RouteTableName;
    /**
     * @param string $VpcId 待操作的VPC实例ID。可通过DescribeVpcs接口返回值中的VpcId获取。
     * @param string $RouteTableName 路由表名称，最大长度不能超过60个字节。
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
        if (array_key_exists("VpcId",$param) and $param["VpcId"] !== null) {
            $this->VpcId = $param["VpcId"];
        }

        if (array_key_exists("RouteTableName",$param) and $param["RouteTableName"] !== null) {
            $this->RouteTableName = $param["RouteTableName"];
        }
    }
}
