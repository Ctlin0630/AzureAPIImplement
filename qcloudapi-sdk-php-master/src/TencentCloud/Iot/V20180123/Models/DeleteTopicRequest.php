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
namespace TencentCloud\Iot\V20180123\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getTopicId() 获取TopicId
 * @method void setTopicId(string $TopicId) 设置TopicId
 * @method string getProductId() 获取产品Id
 * @method void setProductId(string $ProductId) 设置产品Id
 */

/**
 *DeleteTopic请求参数结构体
 */
class DeleteTopicRequest extends AbstractModel
{
    /**
     * @var string TopicId
     */
    public $TopicId;

    /**
     * @var string 产品Id
     */
    public $ProductId;
    /**
     * @param string $TopicId TopicId
     * @param string $ProductId 产品Id
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
        if (array_key_exists("TopicId",$param) and $param["TopicId"] !== null) {
            $this->TopicId = $param["TopicId"];
        }

        if (array_key_exists("ProductId",$param) and $param["ProductId"] !== null) {
            $this->ProductId = $param["ProductId"];
        }
    }
}
