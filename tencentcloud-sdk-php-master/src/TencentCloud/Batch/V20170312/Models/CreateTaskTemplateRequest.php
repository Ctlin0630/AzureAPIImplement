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
namespace TencentCloud\Batch\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getTaskTemplateName() 获取任务模板名称
 * @method void setTaskTemplateName(string $TaskTemplateName) 设置任务模板名称
 * @method Task getTaskTemplateInfo() 获取任务模板内容，参数要求与任务一致
 * @method void setTaskTemplateInfo(Task $TaskTemplateInfo) 设置任务模板内容，参数要求与任务一致
 * @method string getTaskTemplateDescription() 获取任务模板描述
 * @method void setTaskTemplateDescription(string $TaskTemplateDescription) 设置任务模板描述
 */

/**
 *CreateTaskTemplate请求参数结构体
 */
class CreateTaskTemplateRequest extends AbstractModel
{
    /**
     * @var string 任务模板名称
     */
    public $TaskTemplateName;

    /**
     * @var Task 任务模板内容，参数要求与任务一致
     */
    public $TaskTemplateInfo;

    /**
     * @var string 任务模板描述
     */
    public $TaskTemplateDescription;
    /**
     * @param string $TaskTemplateName 任务模板名称
     * @param Task $TaskTemplateInfo 任务模板内容，参数要求与任务一致
     * @param string $TaskTemplateDescription 任务模板描述
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
        if (array_key_exists("TaskTemplateName",$param) and $param["TaskTemplateName"] !== null) {
            $this->TaskTemplateName = $param["TaskTemplateName"];
        }

        if (array_key_exists("TaskTemplateInfo",$param) and $param["TaskTemplateInfo"] !== null) {
            $this->TaskTemplateInfo = new Task();
            $this->TaskTemplateInfo->deserialize($param["TaskTemplateInfo"]);
        }

        if (array_key_exists("TaskTemplateDescription",$param) and $param["TaskTemplateDescription"] !== null) {
            $this->TaskTemplateDescription = $param["TaskTemplateDescription"];
        }
    }
}
