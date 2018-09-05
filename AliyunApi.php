<?php

include_once 'aliyun-openapi-php-sdk-master/aliyun-php-sdk-core/Config.php';
require_once 'aliyun-oss-php-sdk-2.2.3.phar';
require_once 'WriteLog.php';

use Ecs\Request\V20140526 as Ecs;
use Ram\Request\V20150501 as Ram;

class AliyunApi{

    private $accessKey;

    private $accessSecret;

    private $RegionId;

    private $client;

    private $url;

    private $signature;

    private $ossClient;

    private $LogJobId;

    public function __construct() {
	}

    public function SetKey( $accessKey, $accessSecret, $region = "ap-southeast-1" ) {
        $this->accessKey = $accessKey;
        $this->accessSecret = $accessSecret;
        $iClientProfile = DefaultProfile::getProfile( $region, $this->accessKey, $this->accessSecret);
        $this->client = new DefaultAcsClient($iClientProfile);
    }

    private function getkey() {
        return $this->accessKey;
    }

    private function getSecret() {
        return $this->accessSecret;
    }

    public function setRegionId( $RegionId ) {
        $this->RegionId = $RegionId;
    }

    public function CreateInstanceI( $param ) {
        $request = new Ecs\CreateInstanceRequest();
        
        $request->setMethod("POST"); 

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["zone"] ) )
            $request->setZoneId( $param["zone"] );
       
       if( isset( $param["imageId"] ) )
            $request->setImageId( $param["imageId"] );

        if( isset( $param["instanceType"] ) )
            $request->setInstanceType( $param["instanceType"] );
        
        if( isset( $param["securityGroup"] ) )
            $request->setSecurityGroupId( $param["securityGroup"] );

        if( isset( $param["switch"] ) )
            $request->setVSwitchId( $param["switch"] );

        if( isset( $param["password"] ) )
            $request->setPassword( $param["password"] );

        if( isset( $param["instanceName"] ) )
            $request->setInstanceName( preg_replace( "/^[^a-zA-Z]+|[^a-zA-Z0-9_.-]/", "", $param["instanceName"] ) );
        
        if( isset( $param["internetMaxBandwidthIn"] ) )
            $request->setInternetMaxBandwidthIn( $param["internetMaxBandwidthIn"] );
            
        if( isset( $param["internetMaxBandwidthOut"] ) )
            $request->setInternetMaxBandwidthOut( $param["internetMaxBandwidthOut"] );

        if( isset( $param["description"] ) )
            $request->setDescription( $param["description"] );

        if( isset( $param["hostName"] ) )
            $request->setHostName( preg_replace( "/^[^a-zA-Z]+|[^a-zA-Z0-9.-]/", "", $param["hostName"] ) );

        if( isset( $param["systemDiskDiskName"] ) )
            $request->setSystemDiskDiskName( preg_replace( "/^[^a-zA-Z]+|[^a-zA-Z0-9_.-]/", "", $param["systemDiskDiskName"] ) );

        if( isset( $param["privateIp"] ) )
            $request->setPrivateIpAddress( $param["privateIp"] );

        //$request->setDataDisk1Category('cloud_efficiency');

        $response = $this->SendRequest($request, "CreateInstanceRequest", $param, true);

        return $response;
    }

    public function StartInstance( $param ) {
        $request = new Ecs\StartInstanceRequest();
        
        $request->setMethod("POST"); 

        if( isset( $param["instanceId"] ) )
            $request->setInstanceId( $param["instanceId"] );

       $response = $this->SendRequest($request, "StartInstanceRequest", $param);

        return $response;
    }

    public function GetInstanceStatus( $param ) {
        $request = new Ecs\DescribeInstanceStatusRequest();
        
        $request->setMethod("POST"); 

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        $request->setPageSize( 100 );

        $response = $this->SendRequest($request, "DescribeInstanceStatusRequest", $param);

        return $response;
    }
    
    public function DeleteInstance( $param ) {

        $request = new Ecs\DeleteInstanceRequest();

        if( isset( $param["instanceId"] ) )
            $request->setInstanceId( $param["instanceId"] );

        $response = $this->SendRequest($request, "DeleteInstanceRequest", $param);

        return $response;
    }

    public function StopInstance( $param ) {

        $request = new Ecs\StopInstanceRequest();

        if( isset( $param["instanceId"] ) )
            $request->setInstanceId( $param["instanceId"] );
        
        $response = $this->SendRequest($request, "StopInstanceRequest", $param);

        return $response;
    }

    public function ListInstances( $instanceId = null ) {

        $request = new Ecs\DescribeInstancesRequest();
        $request->setRegionId( $this->RegionId );

        if( $instanceId ) {
            $request->setInstanceIds( json_encode(array( $instanceId )) );
        }

        $request->setPageSize( 100 );

        $response = $this->SendRequest($request, "DescribeInstancesRequest", $instanceId);

        return $response;
    }

    public function GetInstanceDetail() {
        $req = new Ecs\DescribeInstanceAttributeRequest();
        $req->setInstanceId("<InstanceId>");
        try {
            $resp = $this->client->getAcsResponse($req);
            if(!isset($resp->Code))
            {
                // 查询成功
                // 查看实例信息相关代码
                // ......
                echo($resp->RequestId);
                print_r($resp);
            }
            else
            {
                // 查询失败
                $code = $resp->Code;
                $message = $resp->Message;
            }
        }
        catch (Exception $e)
        {
            // TODO: handle exception
        }
    }

    public function GetDiskInfo( $param ) {

        $request = new Ecs\DescribeDisksRequest();

        $request->setMethod("GET");

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["zone"] ) )
            $request->setZoneId( $param["zone"] );

        if( isset( $param["diskId"] ) )
            $request->setDiskIds( $param["diskId"] );

        if( isset( $param["diskType"] ) )
            $request->setDiskType( $param["diskType"] );

        if( isset( $param["instanceId"] ) )
            $request->setInstanceId( $param["instanceId"] );

        $request->setPageSize( 100 );

        $response = $this->SendRequest($request, "DescribeDisksRequest", $param);

        return $response;
    }

    public function CreateSnapshot( $param ) {

        $request = new Ecs\CreateSnapshotRequest();

        $request->setMethod("GET");

        if( isset( $param["diskId"] ) )
            $request->setDiskId( $param["diskId"] );

        if( isset( $param["snapshotName"] ) )
            $request->setSnapshotName( preg_replace( "/^[^a-zA-Z]+|[^a-zA-Z0-9_.-]/", "", $param["snapshotName"] ) );

        if( isset( $param["description"] ) )
            $request->setDescription( $param["description"] );
        
        $response = $this->SendRequest($request, "CreateSnapshotRequest", $param);

        return $response;
    }

    public function DeleteSnapshotI( $SnapshotId ) {

        $request = new Ecs\DeleteSnapshotRequest();

        $request->setSnapshotId( $SnapshotId );

        $response = $this->SendRequest($request, "DeleteSnapshotRequest", $SnapshotId);

        return $response;
    }
    
    public function CreateImage( $param ) {

        $DiskDeviceMappings = array(array(
            "DiskType" => "system",
            "Size" => $param["size"],
            "SnapshotId" => $param["snapshotId"],
            "Device" => ""
        ));

        $request = new Ecs\CreateImageRequest();

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );
   
        if( isset( $param["snapshotId"] ) )
            $request->setSnapshotId( $param["snapshotId"] );
        
        if( isset( $param["arch"] ) )
            $request->setArchitecture( $param["arch"] );
        
        if( isset( $param["platfrom"] ) )
            $request->setPlatform( $param["platfrom"] );
 
        $request->setDiskDeviceMappings( $DiskDeviceMappings );

        $response = $this->SendRequest($request, "CreateImageRequest", $param);

        return $response;
    }

    public function GetImage( $param ) {

        $request = new Ecs\DescribeImagesRequest();

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );
   
        if( isset( $param["snapshotId"] ) )
            $request->setSnapshotId( $param["snapshotId"] );

        if( isset( $param["imageId"] ) )
            $request->setImageId( $param["imageId"] );

        $request->setPageSize( 100 );

        $response = $this->SendRequest($request, "DescribeImagesRequest", $param);

        return $response;
    }

    public function DeleteImage( $param ) {

        $request = new Ecs\DeleteImageRequest();

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );
   
        if( isset( $param["imageId"] ) )
            $request->setImageId( $param["imageId"] );

        $request->setForce( true );

        $response = $this->SendRequest($request, "DeleteImageRequest", $param);

        return $response;
    }

    public function ImportImage( $param ) {

        $request = new Ecs\ImportImageRequest();

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );
   
        if( isset( $param["imageName"] ) )
            $request->setImageName( preg_replace( "/^[^a-zA-Z]+|[^a-zA-Z0-9_.-]/", "", $param["imageName"] ) );

        if( isset( $param["platform"] ) )
            $request->setPlatform( $param["platform"] );

        if( isset( $param["oSType"] ) )
            $request->setOSType( $param["oSType"] );

        if( isset( $param["architecture"] ) )
            $request->setArchitecture( $param["architecture"] );

        $DiskDeviceMappings = array(array(
            "Format" => $param["format"],
            "OSSBucket" => $param["bucket"],
            "OSSObject" => $param["object"],
            "DiskImSize" => $param["imSize"],
            "Device" => "",
            "DiskImageSize" => $param["imageSize"]
        ));

        $request->setDiskDeviceMappings( $DiskDeviceMappings );

        $response = $this->SendRequest($request, "ImportImageRequest", $param);

        return $response;
    }

    public function getTaskAttribute( $param ) {

        $request = new Ecs\DescribeTaskAttributeRequest();

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["taskId"] ) )
            $request->setTaskId( $param["taskId"] );

        $response = $this->SendRequest($request, "DescribeTaskAttributeRequest", $param);

        return $response;
    }

    public function getTaskList( $param ) {

        $request = new Ecs\DescribeTasksRequest();

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        $response = $this->SendRequest($request, "DescribeTasksRequest", $param);

        return $response;
    }

    public function AttachDiskToInstance( $param ) {

        $request = new Ecs\AttachDiskRequest();

        if( isset( $param["instanceId"] ) )
            $request->setInstanceId( $param["instanceId"] );

        if( isset( $param["diskId"] ) )
            $request->setDiskId( $param["diskId"] );

        $response = $this->SendRequest($request, "AttachDiskRequest", $param);

        return $response;
    }

    public function DettachDiskToInstance( $param ) {

        $request = new Ecs\DetachDiskRequest();

        if( !isset( $param["instanceId"] ) || !isset( $param["diskId"] ) || $param["instanceId"] == '')
            return null;

        $request->setInstanceId( $param["instanceId"] );

        $request->setDiskId( $param["diskId"] );

        $response = $this->SendRequest($request, "DetachDiskRequest", $param);

        return $response;
    }

    public function CheckConnect() {
        return $this->GetRegion();
    }

    public function GetRegion() {
        $request = new Ecs\DescribeRegionsRequest();
        $request->setMethod("GET");
        $response = $this->SendRequest($request, "DescribeRegionsRequest");
        return $response;
    }

    public function GetSecurityGroup( $param ) {

        $request = new Ecs\DescribeSecurityGroupsRequest();

        $request->setMethod("GET");

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["sgId"] ) )
            $request->setSecurityGroupIds( $param["sgId"] );

        $request->setPageSize( 50 );
        
        $response = $this->SendRequest($request, "DescribeSecurityGroupsRequest", $param);

        return $response;
    }

    public function DescribeAvailableResource( $param ) {
        $request = new Ecs\DescribeAvailableResourceRequest();
        $request->setMethod("GET");

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["destinationResource"] ) )
            $request->setDestinationResource( $param["destinationResource"] );

        if( isset( $param["zoneId"] ) )
            $request->setZoneId( $param["zoneId"] );

        if( isset( $param["instanceChargeType"] ) )
            $request->setInstanceChargeType( $param["instanceChargeType"] );

        if( isset( $param["ioOptimized"] ) )
            $request->setIoOptimized( $param["ioOptimized"] );

        $response = $this->SendRequest($request, "DescribeAvailableResource", $param);
        return $response;
    }

    public function GetSnapshotListI( $param ) {
        $request = new Ecs\DescribeSnapshotsRequest();
        $request->setMethod("GET");

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["diskId"] ) )
            $request->setDiskId( $param["diskId"] );

        if( isset( $param["snapshotId"] ) )
            $request->setSnapshotIds( $param["snapshotId"] );

        if( isset( $param["snapshotName"] ) )
            $request->setSnapshotName( $param["snapshotName"] );

        $request->setPageSize( 100 );

        $response = $this->SendRequest($request, "DescribeSnapshotsRequest", $param);
        return $response;
    }

    public function DescribeRegionsRequest() {
        $request = new Ecs\DescribeRegionsRequest();
        $request->setMethod("GET");
        $response = $this->client->getAcsResponse($request);
       // return $response;
        echo '<pre>';
        echo json_encode($response);
        echo '</pre>';
    }

    public function getImagesList(){

        $request = new Ecs\DescribeImagesRequest();
        $request->setMethod("GET");
        $request->setRegionId( $this->RegionId );
        $response = $this->SendRequest($request, "DescribeImagesRequest");
        return $response;
    }

    public function replaceSystemDisk( $InstanceId, $ImageId ) {

        $request = new Ecs\ReplaceSystemDiskRequest();
        $request->setMethod("GET");
        $request->setInstanceId( $InstanceId );
        $request->setImageId( $ImageId );
        $response = $this->SendRequest($request, "replaceSystemDisk");
        return $response;
    }

    public function getInstanceTypeList() {
        $request = new Ecs\DescribeInstanceTypesRequest();
        $request->setMethod("GET");
        $response = $this->SendRequest($request, "DescribeInstanceTypesRequest");
        return $response;
    }

    public function getVpcList( $param ) {

        $request = new Ecs\DescribeVpcsRequest();

        $request->setMethod("GET");

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["vpcId"] ) )
            $request->setVpcId( $param["vpcId"] );

        $request->setPageSize( 50 );

        $response = $this->SendRequest($request, "DescribeVpcsRequest");

        return $response;
    }

    public function getVSwitchList( $param ) {

        $request = new Ecs\DescribeVSwitchesRequest();

        $request->setMethod("GET");

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["vpcId"] ) )
            $request->setVpcId( $param["vpcId"] );

        $request->setPageSize( 50 );

        $response = $this->SendRequest($request, "DescribeVSwitchesRequest");

        return $response;
    }

    public function CreateDiskI( $param ) {

        $request = new Ecs\CreateDiskRequest();
        $request->setMethod("POST");

        if( isset( $param["region"] ) )
            $request->setRegionId( $param["region"] );

        if( isset( $param["zone"] ) )
            $request->setZoneId( $param["zone"] );

         if( isset( $param["size"] ) )
            $request->setSize( $param["size"] );
        
        if( isset( $param["description"] ) ) {
            $request->setDescription( $param["description"] );
        }

        if( isset( $param["snapId"] ) ) {
            $request->setSnapshotId( $param["snapId"] );
        }

        if( isset( $param["diskName"] ) ) {
            $request->setDiskName( preg_replace( "/^[^a-zA-Z]+|[^a-zA-Z0-9_.-]/", "", $param["diskName"] ) );
        }

        $request->setDiskCategory('cloud_efficiency');

        $response = $this->SendRequest($request, "CreateDiskRequest", $param);

        return $response;
    }

    public function DeleteDiskI( $diskId ) {

        $request = new Ecs\DeleteDiskRequest();

        $request->setMethod("POST");

        $request->setDiskId( $diskId );

        $response = $this->SendRequest($request, "DeleteDiskRequest", $diskId);

        return $response;
    }

    public function getZones( $Region = "ap-southeast-1" ) {
        $request = new Ecs\DescribeZonesRequest();
        $request->setMethod("GET");
        $request->setRegionId( $Region );
        $response = $this->SendRequest($request, "DescribeZonesRequest");
        return $response;
    }

    public function GenSignatureAndUrl( $param_array ) {
        array_push($param_array,"Version=2014-05-26");
        array_push($param_array,"AccessKeyId=".$this->accessKey);
        array_push($param_array,"SignatureMethod=HMAC-SHA1");
        array_push($param_array,"Format=XML");
        array_push($param_array,"Timestamp=".urlencode(gmdate("Y-m-d\TH:i:s\Z")));
        array_push($param_array,"SignatureVersion=1.0");
        array_push($param_array,"SignatureNonce=".uniqid());
        
        asort( $param_array );
        $url = '';
        foreach( $param_array as $param )
            $url .= $param.'&';
        $url = rtrim($url,'&');

        $this->signature = base64_encode( hash_hmac( 'sha1','GET&%2F&'.urlencode ($url), $this->accessSecret.'&', true ));
        $this->url = 'http://ecs.aliyuncs.com/?'.$url.'&Signature='.$this->signature;

    }

    public function AllocatePublicIpAddress( $param ) {

        $request = new Ecs\AllocatePublicIpAddressRequest();

        $request->setMethod("GET");

        if( isset( $param["instanceId"] ) )
            $request->setInstanceId( $param["instanceId"] );

        $response = $this->SendRequest($request, "AllocatePublicIpAddressRequest",$param);

        return $response;
    }

    public function ModifyInstanceAttribute( $param ) {

        $request = new Ecs\ModifyInstanceAttributeRequest();

        $request->setMethod("GET");

        if( isset( $param["instanceId"] ) )
            $request->setInstanceId( $param["instanceId"] );

        if( isset( $param["password"] ) )
            $request->setPassword( $param["password"] );

        $response = $this->SendRequest($request, "ModifyInstanceAttributeRequest",$param);

        return $response;
    }

/**
 * @brief  send request throught sdk and write log when get exception
 * @param[in]   request             the sdk request object
 * @param[in]   action              the action for sdk
 * @param[in]   p                   reserv parameter, write info to log
 * @return  result from aliyun ( object )
 */

    public function SendRequest( $request , $action, $p = null, $isShowError = false ) {

        $param = array( "action" => $action );

        if( isset( $p ) )
            $param["param"] = $p;

        $response = false;

        try{
            $response = $this->client->getAcsResponse($request);

            if( isset( $response->Code ) ) {
                $param["Code"] = $response->Code;
                Misc_Class::function_debug('Aliyun', $action, $response->Code);
            }

            return $response;
        }
        catch (Exception $e) {

            $error = $e->getMessage();
            switch( $action ){
                case "DetachDiskRequest":
                case "DeleteDiskRequest":

                if( strpos( $error, "InvalidOperation.Conflict") !== false )
                    throw new Exception($error."\n");
                break;

                default:
                break;
            };

            $param["Msg"] = $error;
            $param["CallStack"] = debug_backtrace();
            Misc_Class::function_debug('Aliyun', $action, $param);	
            if( $isShowError ) {
                $replica  = new Replica_Class();
                $mesage = $replica->job_msg($param["Msg"]);
                $replica->update_job_msg( $this->LogJobId , $mesage, 'Service');
            }
        }

        return $response;
    }
    
    public function GetSignature() {
        return $this->signature;
    }

    public function GetUrl() {
        return $this->url;
    }

/**
* Role Permission Control
*/
    public function GetLoginProfile() {
    
        $request = new Ram\GetLoginProfileRequest();
        
        $request->setUserName("rex@saasame.com");

        $response = $this->SendRequest( $request , "GetLoginProfileRequest" );
    
        return $response;
    }

    public function CreateRoleRequest( $param ) {

        $request = new Ram\CreateRoleRequest();

        if( isset( $param["roleName"] ) )
            $request->setRoleName( $param["roleName"] );
        
        if( isset( $param["description"] ) )
            $request->setDescription( $param["description"] );

        if( isset( $param["policy"] ) )
            $request->setAssumeRolePolicyDocument( $param["policy"] );

        $response = $this->SendRequest( $request , "CreatePolicyRequest" );

        return $response;
    }

    public function AttachPolicyToRoleRequest( $param ) {

        $request = new Ram\AttachPolicyToRoleRequest();

        if( isset( $param["policyType"] ) )
            $request->setPolicyType( $param["policyType"] );
        
        if( isset( $param["policyName"] ) )
            $request->setPolicyName( $param["policyName"] );

        if( isset( $param["roleName"] ) )
            $request->setRoleName( $param["roleName"] );

        $response = $this->SendRequest( $request , "CreatePolicyRequest" );

        return $response;
    }

    public function SetImageExportRoleforOss( $cloud_uuid ) {

        $this->AuthAli( $cloud_uuid );

        $param = array(
            "roleName" => "AliyunECSImageExportDefaultRole",
            "description" => "The ECS service will use this role to export image file.",
            "policy" => '{
                "Statement": [
                    {
                        "Action": "sts:AssumeRole",
                        "Effect": "Allow",
                        "Principal": {
                            "Service": [
                                "ecs.aliyuncs.com"
                            ]
                        }
                    }
                ],
                "Version": "1"
              }'
        );

        $this->CreateRoleRequest( $param );

        $param = array(
            "policyType" => "System",
            "policyName" => "AliyunECSImageExportRolePolicy",
            "roleName" => "AliyunECSImageExportDefaultRole"
        );

        $this->AttachPolicyToRoleRequest( $param );

    }

    public function SetImageImportRoleforOss( $cloud_uuid ) {

        $this->AuthAli( $cloud_uuid );

        $param = array(
            "roleName" => "AliyunECSImageImportDefaultRole",
            "description" => "The ECS service will use this role to import image file.",
            "policy" => '{
                "Statement": [
                    {
                        "Action": "sts:AssumeRole",
                        "Effect": "Allow",
                        "Principal": {
                            "Service": [
                                "ecs.aliyuncs.com"
                            ]
                        }
                    }
                ],
                "Version": "1"
            }'
        );

        $this->CreateRoleRequest( $param );

        $param = array(
            "policyType" => "System",
            "policyName" => "AliyunECSImageImportRolePolicy",
            "roleName" => "AliyunECSImageImportDefaultRole"
        );

        $this->AttachPolicyToRoleRequest( $param );
    }

    public function ListRoles() {

        $request = new Ram\ListRolesRequest();

        $response = $this->SendRequest( $request , "ListRolesRequest" );

        return $response;
    }

    public function ListPoliciesForRole( $param ) {
        
        $request = new Ram\ListPoliciesForRoleRequest();

        if( $param["roleName"] )
            $request->setRoleName( $param["roleName"] );

        $response = $this->SendRequest( $request , "ListPoliciesForRoleRequest" );

        return $response;
    }
}

?>