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
 * @method integer getSafeType() 获取软件安全类型，分别为0-未知、 1-安全软件、2-风险软件、3-病毒软件
 * @method void setSafeType(integer $SafeType) 设置软件安全类型，分别为0-未知、 1-安全软件、2-风险软件、3-病毒软件
 * @method string getVirusName() 获取病毒名称， utf8编码，非病毒时值为空
 * @method void setVirusName(string $VirusName) 设置病毒名称， utf8编码，非病毒时值为空
 * @method string getVirusDesc() 获取病毒描述，utf8编码，非病毒时值为空
 * @method void setVirusDesc(string $VirusDesc) 设置病毒描述，utf8编码，非病毒时值为空
 */

/**
 *病毒信息
 */
class VirusInfo extends AbstractModel
{
    /**
     * @var integer 软件安全类型，分别为0-未知、 1-安全软件、2-风险软件、3-病毒软件
     */
    public $SafeType;

    /**
     * @var string 病毒名称， utf8编码，非病毒时值为空
     */
    public $VirusName;

    /**
     * @var string 病毒描述，utf8编码，非病毒时值为空
     */
    public $VirusDesc;
    /**
     * @param integer $SafeType 软件安全类型，分别为0-未知、 1-安全软件、2-风险软件、3-病毒软件
     * @param string $VirusName 病毒名称， utf8编码，非病毒时值为空
     * @param string $VirusDesc 病毒描述，utf8编码，非病毒时值为空
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
        if (array_key_exists("SafeType",$param) and $param["SafeType"] !== null) {
            $this->SafeType = $param["SafeType"];
        }

        if (array_key_exists("VirusName",$param) and $param["VirusName"] !== null) {
            $this->VirusName = $param["VirusName"];
        }

        if (array_key_exists("VirusDesc",$param) and $param["VirusDesc"] !== null) {
            $this->VirusDesc = $param["VirusDesc"];
        }
    }
}
