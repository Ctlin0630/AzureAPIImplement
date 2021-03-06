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
namespace TencentCloud\Cws\V20180312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method array getUrls() 获取站点的url列表
 * @method void setUrls(array $Urls) 设置站点的url列表
 * @method string getUserAgent() 获取访问网站的客户端标识
 * @method void setUserAgent(string $UserAgent) 设置访问网站的客户端标识
 */

/**
 *CreateSites请求参数结构体
 */
class CreateSitesRequest extends AbstractModel
{
    /**
     * @var array 站点的url列表
     */
    public $Urls;

    /**
     * @var string 访问网站的客户端标识
     */
    public $UserAgent;
    /**
     * @param array $Urls 站点的url列表
     * @param string $UserAgent 访问网站的客户端标识
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
        if (array_key_exists("Urls",$param) and $param["Urls"] !== null) {
            $this->Urls = $param["Urls"];
        }

        if (array_key_exists("UserAgent",$param) and $param["UserAgent"] !== null) {
            $this->UserAgent = $param["UserAgent"];
        }
    }
}
