<?php

require_once 'qcloudapi-sdk-php-master/src/QcloudApi/QcloudApi.php';
require(__DIR__ . DIRECTORY_SEPARATOR . 'cos-php-sdk-v5-master/cos-autoloader.php');

require_once 'tencentcloud-sdk-php-master/TCloudAutoLoader.php';
require_once 'WriteLog.php';

use TencentCloud\Cbs\V20170312\CbsClient;
use TencentCloud\Cbs\V20170312\Models\CreateDisksRequest;
use TencentCloud\Cbs\V20170312\Models\TerminateDisksRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;

class TencentApi{

    private $cvm;

    private $cbs;

    private $snapshot;

    private $vpc;

    private $dfw;

    private $image;

    private $cosClient;

    private $SecretId;

    private $SecretKey;

    private $AppId;

    private $RegionId;
    
    private $LogJobId;

    public function __construct() {
    }

    public function SetAppId( $AppId ) {
        $this->AppId = $AppId;
    }

    public function SetKey( $SecretId, $SecretKey, $region = "bj" ) {
        $this->SecretId = $SecretId;
        $this->SecretKey = $SecretKey;
        $config = array(
                'SecretId'       => $this->SecretId,
                'SecretKey'      => $this->SecretKey,
                'RequestMethod'  => 'GET',
                'DefaultRegion'  => $region
            );

        //for new sdk
        $this->cred = new Credential( $SecretId, $SecretKey );

        $this->cvm = QcloudApi::load(QcloudApi::MODULE_CVM, $config);

        $this->cbs = QcloudApi::load(QcloudApi::MODULE_CBS, $config);

        $this->snapshot = QcloudApi::load(QcloudApi::MODULE_SNAPSHOT, $config);

        $this->vpc = QcloudApi::load(QcloudApi::MODULE_VPC, $config);

        $this->dfw = QcloudApi::load(QcloudApi::MODULE_DFW, $config);

        $this->image = QcloudApi::load(QcloudApi::MODULE_IMAGE, $config);

        $this->cosClient = new Qcloud\Cos\Client(array('region' => $region,
            'credentials'=> array(
            'appId'     => $this->AppId,
            'secretId'  => $this->SecretId,
            'secretKey' => $this->SecretKey
        )));
    }

    private function sendRequest( $action, $config , $client) {

        $response = $client->$action($config);
        
        //echo "\nRequest :" . $client->getLastRequest();
        //echo "\nResponse :" . $client->getLastResponse();
        //echo "\n";
        if ($response === false ) { 
            
            $this->writeErrorLog( $action, $client );

            return false;
        }
        else if(  isset( $response["Response"]["Error"] ) ) {

            $param = array(
                "config" => $config,
                "response" => $response,
                "callstack" => debug_backtrace()
            );

            Misc_Class::function_debug('Tencent', $action, $param);	

            return false;
        }

        return $response;
    }

    private function writeErrorLog( $action, $client, $isShowError = null ) {

        $error = $client->getError();

        $param = array(
            "Error code" => $error->getCode(),
            "Msg"       => $error->getMessage(),
            "ext"       => $error->getExt(),
            "Request"   => $client->getLastRequest(),
            "Response"  => $client->getLastResponse()
        );

        Misc_Class::function_debug('Tencent', $action, $param);	

        if( $isShowError ) {
            $replica  = new Replica_Class();
            $mesage = $replica->job_msg($param["Msg"]);
            $replica->update_job_msg( $this->LogJobId , $mesage, 'Service');
        }

        return $param;
    }

    public function CheckConnect( ) {

        $region = $this->cvm->DescribeRegions();

        if( $region === false) {

            $this->writeErrorLog( "DescribeRegions" );
            return false;
        }

        return true;
    }

    public function GetRegion() {

        $region = $this->cvm->DescribeRegions();

        if( $region === false)
            return false;

        return $region;
    }

    public function CreateInstance( $param ) {

        $config = array(
            "Version" => "2017-03-12",
            "InstanceChargeType" => "POSTPAID_BY_HOUR"
        );

        if( isset( $param["Zone"] ) ){
            $config["Placement.Zone"] = $param["Zone"];
            $config["Region"] = $this->getCurrectRegion( $param["Zone"] );
        }

        if( isset( $param["InstanceType"] ) )
            $config["InstanceType"] = $param["InstanceType"];
        
        if( isset( $param["ImageId"] ) )
            $config["ImageId"] = $param["ImageId"];

        if( isset( $param["InstanceName"] ) )
            $config["InstanceName"] = $param["InstanceName"];

        if( isset( $param["VirtualPrivateCloud.VpcId"] ) )
            $config["VirtualPrivateCloud.VpcId"] = $param["VirtualPrivateCloud.VpcId"];

        if( isset( $param["VirtualPrivateCloud.SubnetId"] ) )
            $config["VirtualPrivateCloud.SubnetId"] = $param["VirtualPrivateCloud.SubnetId"];

        if( isset( $param["VirtualPrivateCloud.PrivateIpAddresses"] ) )
            $config["VirtualPrivateCloud.PrivateIpAddresses.0"] = $param["VirtualPrivateCloud.PrivateIpAddresses"];

        if( isset( $param["SecurityGroupIds.1"] ) )
            $config["SecurityGroupIds.1"] = $param["SecurityGroupIds.1"];
        
        if( isset( $param["InternetAccessible"] ) ) {
            $config["InternetAccessible.InternetMaxBandwidthOut"] = $param["InternetAccessible"];

            if( $param["InternetAccessible"] > 0 )
                $config["InternetAccessible.PublicIpAssigned"] = "true";
        }

        if( isset( $param["LoginSettings.Password"] ) )
            $config["LoginSettings.Password"] = $param["LoginSettings.Password"];

        if( isset( $param["SystemDisk.DiskType"] ) )
            $config["SystemDisk.DiskType"] = $param["SystemDisk.DiskType"];

        $instances = $this->sendRequest( "RunInstances", $config, $this->cvm );

        return $instances;
    }

    public function DeleteInstance( $param ) {

        $config = array(
            "Version" => "2017-03-12"
        );

        if( isset( $param["zone"] ) ) 
            $config["Region"] = $this->getCurrectRegion( $param["zone"] );

        if( isset( $param["InstanceIds"] ) ) {
            foreach( $param["InstanceIds"] as $key=>$instanceId ) 
                $config["InstanceIds.".$key] = $instanceId;
        }

        $instances = $this->sendRequest( "TerminateInstances", $config, $this->cvm );

        return $instances;
    }

    public function ListInstances( $instanceIds = null, $zone = null ) {

        $config = array("Version"=>"2017-03-12");

        if( isset( $instanceIds ) && isset( $zone ) ){

            $config["Region"] = $this->getCurrectRegion( $zone );

            if( $instanceIds )
                foreach( $instanceIds as $key=>$instanceId ) {
                    $config["InstanceIds.".$key] = $instanceId;
                }

            $instances = $this->sendRequest( "DescribeInstances", $config, $this->cvm );

            return $instances;
        }

        $regions = $this->GetRegion();

        $all_instances = array();
        $all_instances["Response"] = array();
        $all_instances["Response"]["InstanceSet"] = array();

        foreach( $regions["regionSet"] as $region ){
            $config["Region"] = $region["regionCode"];
        
            $instances = $this->sendRequest( "DescribeInstances", $config, $this->cvm );

            if($instances["Response"]["TotalCount"] > 0)
                $all_instances["Response"]["InstanceSet"] = array_merge($all_instances["Response"]["InstanceSet"], $instances["Response"]["InstanceSet"]);
            
        }

        return $all_instances;
    }

    public function ListInstancesStatus( $instanceIds = null, $zone = null ) {

        $config = array("Version"=>"2017-03-12");

        if( isset( $instanceIds ) && isset( $zone ) ){

            $config["Region"] = $this->getCurrectRegion( $zone );

            if( $instanceIds )
                foreach( $instanceIds as $key=>$instanceId ) {
                    $config["InstanceIds.".$key] = $instanceId;
                }

            $instances = $this->sendRequest( "DescribeInstancesStatus", $config, $this->cvm );

            return $instances;
        }

        $regions = $this->GetRegion();

        $all_instances = array();
        $all_instances["Response"] = array();
        $all_instances["Response"]["InstanceStatusSet"] = array();

        foreach( $regions["regionSet"] as $region ){
            $config["Region"] = $region["regionCode"];

            $instances = $this->sendRequest( "DescribeInstancesStatus", $config, $this->cvm );

            if($instances["Response"]["TotalCount"] > 0)
                $all_instances["Response"]["InstanceStatusSet"] = array_merge($all_instances["Response"]["InstanceStatusSet"], $instances["Response"]["InstanceStatusSet"]);
            
        }

        return $all_instances;
    }

    public function CreateDisk( $param ) {

        $config = array(
            "storageType"   => "cloudBasic",
            "goodsNum"      => 1,
            "payMode"       => "POSTPAID_BY_HOUR",
            "period"        => 1
        );

        if( isset( $param["zone"] ) ) {
            $config["zone"] = $param["zone"];
        }

        if( isset( $param["size"] ) ) {
            $config["storageSize"] = $param["size"];
        }

        if( isset( $param["snapId"] ) ) {
            $config["snapshotId"] = $param["snapId"];
        }

        $disk = $this->sendRequest( "CreateCbsStorages", $config, $this->cbs );

        return $disk;
    }

    public function CreateDiskV2( $param ) {
    
        $region = "";

        if( isset( $param["zone"] ) ) {
            $region = $this->getCurrectRegion($param["zone"]);
        }

        $CbsClient = new CbsClient($this->cred, $region);
        
        $config = new CreateDisksRequest();

        $config->DiskChargeType = "POSTPAID_BY_HOUR";

        if( isset( $param["zone"] ) ) {
            $config->Placement = array( 
                "Zone" => $param["zone"]
            );
        }

        if( isset( $param["size"] ) ) {
            $config->DiskSize = $param["size"];
        }

        if( isset( $param["snapId"] ) ) {
            $config->SnapshotId = $param["snapId"];
        }

        if( isset( $param["diskType"] ) ) {
            $config->DiskType = $param["diskType"];
        }

        try{
            $disk = $CbsClient->CreateDisks($config);
        }
        catch(TencentCloudSDKException $e) {
            Misc_Class::function_debug('Tencent', "CreateDiskV2", $e);
        }
        //$disk = $this->sendRequest( "CreateDisks", $config, $CbsClient );

        return json_decode( $disk->toJsonString(), true );
    }

    public function DeleteDiskV2( $param ) {

        $CbsClient = new CbsClient($this->cred, $this->getCurrectRegion( $param["zone"] ));
        
        $config = new TerminateDisksRequest();

        if( isset( $param["diskId"]) )
        $config->DiskIds = array( $param["diskId"] );

        try{
            $disk = $CbsClient->TerminateDisks($config);
        }
        catch(TencentCloudSDKException $e) {
            Misc_Class::function_debug('Tencent', "DeleteDiskV2", $e);
            return false;
        }
        //$disk = $this->sendRequest( "CreateDisks", $config, $CbsClient );

        return json_decode( $disk->toJsonString(), true );
    }

    public function GetDiskInfo( $param ) {

        $config = array( );

        if( isset( $param["diskIds"] ) ) {
            foreach( $param["diskIds"] as $key=>$diskId ) 
                $config["storageIds.".$key] = $diskId;
        }

        if( isset( $param["zone"] ) ) {
            $config["Region"] = $this->getCurrectRegion( $param["zone"] );
        }

        $ret = $this->sendRequest( "DescribeCbsStorages", $config, $this->cbs );

        return $ret;
    }

    public function AttachDiskToInstance( $param ) {

        $config = array( );

        if( isset( $param["zone"] ) ) {
            $region = $this->getCurrectRegion($param["zone"]);
        }
        
        if( isset( $param["diskIds"] ) ) {
            foreach( $param["diskIds"] as $key=>$diskId ) 
                $config["storageIds.".$key] = $diskId;
        }

        if( isset( $param["instanceId"] ) ) {
            $config["uInstanceId"] = $param["instanceId"];
        }

        if( isset( $region ) ) {
            $config["Region"] = $region;
        }

        $ret = $this->sendRequest( "AttachCbsStorages", $config, $this->cbs );

        return $ret;
    }
    
    public function DettachDiskFromInstance( $param ) {

        $config = array( );

        if( isset( $param["diskIds"] ) ) {
            foreach( $param["diskIds"] as $key=>$diskId ) 
                $config["storageIds.".$key] = $diskId;
        }

        if( isset( $param["zone"] ) )
            $config["Region"] = $this->getCurrectRegion( $param["zone"] );

        $ret = $this->sendRequest( "DetachCbsStorages", $config, $this->cbs );

        return $ret;
    }

    public function CreateSnapshot( $param ) {

        $config = array();

        if( isset( $param["diskId"] ) ) {
            $config["storageId"] = $param["diskId"];
        }

        if( isset( $param["snapshotName"] ) ) {
            $config["snapshotName"] = $param["snapshotName"];
        }

        if( isset( $param["region"] ) ) {
            $config["Region"] = $param["region"];
        }

        $snapshot = $this->sendRequest( "CreateSnapshot", $config, $this->snapshot );

        return $snapshot;
    }

    public function GetSnapshotList( $param ) {

        $config = array( "limit" => 100 );

        if( isset( $param["diskIds"] ) )
        foreach( $param["diskIds"] as $key=>$diskId ) {
            $config["storageIds.".$key] = $diskId;
        }

        if( isset( $param["snapshotIds"] ) )
        foreach( $param["snapshotIds"] as $key=>$snapshotId ) {
            $config["snapshotIds.".$key] = $snapshotId;
        }

        if( isset( $param["zone"] ) )
            $config["Region"] = $this->getCurrectRegion( $param["zone"] );

        $ret = $this->sendRequest( "DescribeSnapshots", $config, $this->snapshot );

        return $ret;

    }

    public function GetNetworkInterface( $param ) {

        $config = array( "limit" => 50 );

        if( isset( $param["instanceId"] ) )
            $config["instanceId"] = $param["instanceId"];

        if( isset( $param["zone"] ) )
            $config["Region"] = $this->getCurrectRegion( $param["zone"] );

        $ret = $this->sendRequest( "DescribeNetworkInterfaces", $config, $this->vpc );

        return $ret;
    }

    public function DeleteSnapshot( $param ) {

        $config = array( );

        if( $param["snapshotIds"] )
        foreach( $param["snapshotIds"] as $key=>$snapshotId ) {
            $config["snapshotIds.".$key] = $snapshotId;
        }

        $snapshot = $this->sendRequest( "DeleteSnapshot", $config, $this->snapshot );

        return $snapshot;
    }

    public function GetInstanceTypes( $param ) {

        $config = array(
            "Version"=>"2017-03-12"
        );

        if( $param["zone"] ) {
            $config["Filters.1.Name"] = "zone";
            $config["Filters.1.Values.1"] = $param["zone"];
            $config["Region"] = $this->getCurrectRegion( $param["zone"] );
        }

        $types = $this->sendRequest( "DescribeInstanceTypeConfigs", $config, $this->cvm );

        return $types;
    }

    public function GetVPCList( $param ) {

        $config = array(
            "limit"=>100
        );

        if( isset( $param["vpcId"] ) )
            $config["vpcId"] = $param["vpcId"];

        if( isset( $param["zone"] ) )
            $config["Region"] = $param["zone"];

        $VPCs = $this->sendRequest( "DescribeVpcEx", $config, $this->vpc );

        return $VPCs;
    }

    public function GetSubnetList( $param ) {

        $config = array(
            "limit"=>100
        );

        if( isset( $param["zone"] ) )
            $config["Region"] = $param["zone"];

        $subnets = $this->sendRequest( "DescribeSubnetEx", $config, $this->vpc );

        return $subnets["data"];
    }

    public function GetSecurityGroups( $param ) {

        $config = array(
            "limit"=>100
        );

        if( isset( $param["zone"] ) )
            $config["Region"] = $param["zone"];

        $securityGroups = $this->sendRequest( "DescribeSecurityGroupEx", $config, $this->dfw );

        return $securityGroups;
    }

    public function ImportImage( $param ) {

        $config = array(
            "Version" => "2017-3-12",
            "Force" => "true"
        );

        if( isset( $param["OsType"] ) )
            $config["OsType"] = $param["OsType"];

        if( isset( $param["OsVersion"] ) )
            $config["OsVersion"] = $param["OsVersion"];

        if( isset( $param["ImageUrl"] ) )
            $config["ImageUrl"] = $param["ImageUrl"];
        
        if( isset( $param["ImageName"] ) )
            $config["ImageName"] = $param["ImageName"];

        if( isset( $param["ImageDescription"] ) )
            $config["ImageDescription"] = $param["ImageDescription"];

        if( isset( $param["Architecture"] ) )
            $config["Architecture"] = $param["Architecture"];

        $securityGroups = $this->sendRequest( "ImportImage", $config, $this->image );

        return $securityGroups;
    }

    public function DeleteImage( $param ) {

        $config = array(
            "Version" => "2017-3-12"
        );

        foreach( $param["ImageIds"] as $key=>$imageId ) {
            $config["ImageIds.".$key] = $imageId;
        }

        if( isset( $param["zone"] ) )
            $config["Region"] = $this->getCurrectRegion( $param["zone"] );

        $ret = $this->sendRequest( "DeleteImages", $config, $this->image );

        return $ret;
    }

    public function GetImages( $param ) {

        $config = array(
            "Version" => "2017-3-12",
            "Limit" => 100
        );

        if( isset( $param["ImageName"] ) ) {
            $config["Filters.1.Name"] = "image-name";
            $config["Filters.1.Values.1"] = $param["ImageName"];
        }

        $Images = $this->sendRequest( "DescribeImages", $config, $this->image );

        return $Images;
    }

    public function GetObjectUrl( $bucket, $object, $region) {

        try {
            $bucket =  $bucket;
            $key = $object;
            $region = $region;
            $url = "/{$key}";
            $request = $this->cosClient->get($url);
            $signedUrl = $this->cosClient->getObjectUrl($bucket, $key, '+10 minutes');
            return $signedUrl;

        } catch (\Exception $e) {
            Misc_Class::function_debug("Tencent", "GetObjectUrl", $e->getMessage());
            return false;
        }

    }

    public function testGetBucketACL(){
        try {
            $this->cosClient->createBucket(array('Bucket' => 'testbucket'));
            sleep(5);
            $this->cosClient->PutBucketAcl(array(
                'Bucket' => 'testbucket',
                'Grants' => array(
                    array(
                        'Grantee' => array(
                            'DisplayName' => 'qcs::cam::uin/327874225:uin/327874225',
                            'ID' => 'qcs::cam::uin/327874225:uin/327874225',
                            'Type' => 'CanonicalUser',
                        ),
                        'Permission' => 'FULL_CONTROL',
                    ),
                // ... repeated
                ),
                'Owner' => array(
                    'DisplayName' => 'qcs::cam::uin/3210232098:uin/3210232098',
                    'ID' => 'qcs::cam::uin/3210232098:uin/3210232098',
                ),));

        } catch (\Exception $e) {
            $this->assertFalse(true, $e);
        }
    }
}

?>