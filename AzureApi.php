<?php

require_once 'WriteLog.php';

class AzureApi{

    private $AccessKey;

    private $AccessSecret;

    private $SubscriptionId;

    private $UserName;

    private $Password;

    private $TenantId;

    private $Location;

    private $AccessToken;

    private $TokenInfo;

    private $client;

    private $ResourceGroup;

    private $CloudId;

    private $ReplicaId;

    private $ControlUrl;

    private $LoginUrl;

    private $ResourceUrl;

    public function __construct( ) {

	}

    public function SetEndpointType( $type ) {

        $azureInfo = define_mgmt_setting();

        $this->ControlUrl  = $azureInfo->$type->Auzre_ControlUrl;
        $this->LoginUrl    = $azureInfo->$type->Auzre_LoginUrl;
        $this->ResourceUrl = $azureInfo->$type->Azure_Resource;
    }

    private function Getkey() {
        return $this->AccessKey;
    }

    private function GetSecret() {
        return $this->AccessSecret;
    }

    public function SetLocation( $Location ) {
        $this->Location = $Location;
    }

    public function SetClientId( $ClientId ) {
        $this->AccessKey = $ClientId;
    }

    public function SetClientSecret( $ClientSecret ) {
        $this->AccessSecret = $ClientSecret;
    }

    public function SetSubscriptionId( $SubscriptionId ) {
        $this->SubscriptionId = $SubscriptionId;
    }

    public function SetUserName( $UserName ) {
        $this->UserName = $UserName;
    }

    public function SetPassword( $Password ) {
        $this->Password = $Password;
    }

    public function SetReplicaId( $ReplicaId ) {
        $this->ReplicaId = $ReplicaId;
    }

    public function SetTenantId( $TenantId ) {
        $this->TenantId = $TenantId;
    }

    public function SetAccessToken( $AccessToken ) {
        $this->AccessToken = $AccessToken;
    }

    public function SetResourceGroup( $ResourceGroup ) {

        if( $ResourceGroup != null || $ResourceGroup != '')
            $this->ResourceGroup = $ResourceGroup;
    }

    public function GetResourceGroup( ) {
        return $this->ResourceGroup;
    }

    public function GetAccessToken() {
        return $this->AccessToken;
    }

    public function GetTokenInfo() {
        return $this->TokenInfo;
    }

    public function DefaultConfig( $TENANT_ID, $SUBSCRIPTION_ID, $CLIENT_ID, $CLIENT_SECRET) {

        $this->SetSubscriptionId( $SUBSCRIPTION_ID );
		//$this->SetUserName( $USER_NAME );
		//$this->SetPassword( $PASSWORD );
		$this->SetTenantId( $TENANT_ID );
        $this->SetClientId( $CLIENT_ID );
        $this->SetClientSecret( $CLIENT_SECRET );
		//$this->SetLocation( 'westus' );
		//$this->SetResourceGroup( 'saasame_rg' );
    }
    public function GetOAuth2AuthCode() {
        $endpoint = "https://'.$this->LoginUrl.'/".$this->TenantId."/oauth2/authorize?";

        $endpoint .= "client_id=".$this->AccessKey;
        $endpoint .= "&response_type=code";
        $endpoint .= "&redirect_uri=http%3A%2F%2Flocalhost%2Fmyapp%2F";
        $endpoint .= "&response_mode=query";
        $endpoint .= "&resource=https%3A%2F%2Fservice.contoso.com%2F";
        $endpoint .= "&state=12345";

        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
 
        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // evaluate for success response
        if ($status != 200) {
            throw new Exception("Error: call to URL $endpoint failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) . "\n");
        }
        curl_close($curl);

        $this->AccessToken = json_decode($json_response, true)['access_token'];

        return $json_response;
    }

    public function GetOAuth2TokenByAssigned( ) {
        
        $endpoint = "https://'.$this->LoginUrl.'/".$this->TenantId."/oauth2/token";

       
        $params = array('grant_type'    => 'password',
                        'resource'      => 'https://management.core.windows.net/',
                        'client_id'     => $this->AccessKey,
                        'client_secret' => $this->AccessSecret,
                        'username'      => $this->UserName,
                        'password'      => $this->Password,
                        'scope'         => 'openid'
                        );

        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER,'Content-Type: application/x-www-form-urlencoded');
        curl_setopt($curl, CURLOPT_HEADER,'Accept: application/json');

        // Remove comment if you have a setup that causes ssl validation to fail
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $postData = "";

        //This is needed to properly form post the credentials object
        foreach($params as $k => $v) {
            $postData .= $k . '='.urlencode($v).'&';
        }

        $postData = rtrim($postData, '&');

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // evaluate for success response
        if ($status != 200) {
            throw new Exception("Error: call to URL $endpoint failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) . "\n");
        }
        curl_close($curl);

        $this->AccessToken = json_decode($json_response, true)['access_token'];

        return $json_response;
    }

    public function GetOAuth2Token( ) {
        
        $endpoint = "https://".$this->LoginUrl."/".$this->TenantId."/oauth2/token?api-version=1.0";
       
        $params = array('grant_type'    => 'client_credentials',
                        'resource'      => 'https://'.$this->ResourceUrl,
                        'client_id'     => $this->AccessKey,
                        'client_secret' => $this->AccessSecret
                        );

        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER,'Content-Type: application/x-www-form-urlencoded');
        curl_setopt($curl, CURLOPT_HEADER,'Accept: application/json');

        $postData = "";

        //This is needed to properly form post the credentials object
        foreach($params as $k => $v) {
            $postData .= $k . '='.urlencode($v).'&';
        }

        $postData = rtrim($postData, '&');

        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // evaluate for success response
        if ($status != 200) {
            throw new Exception("Error: call to URL $endpoint failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) . "\n");
        }
        curl_close($curl);

        $this->TokenInfo = $json_response;

        $this->AccessToken = json_decode($json_response, true)['access_token'];

        return $json_response;
    }

    private function ProcSendData($URL, $Mehod, $REST_DATA, $TIME_OUT = 0, $err_ret = false) {

        try{
            return $this->CurlToAzure( $URL, $Mehod, $REST_DATA, $TIME_OUT, $err_ret );
        }
        catch( Exception $e ){ //handle the api version error

            $errorMsg = $e->getMessage();

            $spos = strpos( $errorMsg, "The supported api-versions are" );

            if( $spos === false )
                throw new Exception( $errorMsg );
            
            if( strpos( $errorMsg, "for api version " ) !== false ){
                $nstr = substr( $errorMsg, strpos( $errorMsg, "for api version " ) + strlen("for api version ") );
            }
            else if( strpos( $errorMsg, "and API version " ) !== false )
                $nstr = substr( $errorMsg, strpos( $errorMsg, "and API version " ) + strlen("and API version ") );

            $data = explode( "'", $nstr );

            $nowVersion = $data[1];

            $nstr1 = substr( $errorMsg, $spos + strlen("The supported api-versions are ") );

            $data = explode( "'", $nstr1 );

            $newVersion = explode( ",", $data[1] );

            $nURL = str_replace( $nowVersion, trim(end($newVersion)), $URL );

            return $this->CurlToAzure( $nURL, $Mehod, $REST_DATA, $TIME_OUT, $err_ret );
        }
    }

    public function CurlToAzure($URL, $Mehod, $REST_DATA, $TIME_OUT = 0, $err_ret = false)
    {
        $ch = curl_init($URL);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $retry_count = 0;

        do {

            if( !isset( $this->AccessToken ) ) 
                $this->GetOAuth2Token( );

            $header = array('Content-Type: application/json',
                'Authorization: Bearer '.$this->AccessToken,
                'Host: '.$this->ControlUrl.'');
        
            if( isset($REST_DATA) ) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $REST_DATA);
            }
        
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $Mehod);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $TIME_OUT);
            $output = curl_exec($ch);	

            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($status == 200 || $status == 201 || $status == 202 || $status == 204) {
                curl_close($ch);
                return $output;
            }

            // evaluate for success response
            switch( $status )
            {
                case 401:
                {
                    $token_info = $this->GetOAuth2Token();
                    if( isset( $this->CloudId ) ) {
                        $AzureModel = new Azure_Model();
                        $AzureModel->update_cloud_token_info( $this->CloudId, $token_info );
                    }
                    break;
                }
                case 404:
                {
                    //do not throw exception case
                    if( $err_ret )
                        return json_encode( array( "success" => false, "code" => $status ), JSON_UNESCAPED_SLASHES);
                    
                    // do not write log case
                    if( strpos( $output, "The supported api-versions are" ) === false )
                        $this->gen_debug_file($URL, $Mehod, $REST_DATA, $output, $status, "Azure_debug");

                    throw new Exception("Error: call to URL $URL failed with status $status, response $output, curl_error " . curl_error($ch) . ", curl_errno " . curl_errno($ch) . "\n");
                    
                }
                default:
                    if( strpos( $output, "The supported api-versions are" ) === false )
                        $this->gen_debug_file($URL, $Mehod, $REST_DATA, $output, $status, "Azure_debug");
                        
                    throw new Exception("Error: call to URL $URL failed with status $status, response $output, curl_error " . curl_error($ch) . ", curl_errno " . curl_errno($ch) . "\n");
                    break;
            }

            $retry_count++;

        }while( $retry_count < 3 );
        
        curl_close($ch);
        
        return $output;
    }
    
    public function gen_debug_file($URL, $Mehod, $REST_DATA, $output, $status, $path = "Azure log") {

        $req = array(
            "URL" => $URL,
            "Method" => $Mehod,
            "REST_DATA" => $REST_DATA
        );

        $out = array(
            "return" => json_decode( $output, true),
            "status" => $status,
            "callstack" => debug_backtrace()
        );

        Misc_Class::openstack_debug($path,(object)$req,json_encode( $out ));
    }

    public function ListVMInResourceGroup( ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/virtualmachines?'.
            'api-version=2016-04-30-preview';
        
        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function ListVMISubscription( ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/providers/Microsoft.Compute/virtualmachines?'.
            'api-version=2016-04-30-preview';
        
        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetVMInformation( $VMName ) {
        $URL = "https://".$this->ControlUrl."/".
        "subscriptions/".$this->SubscriptionId."/".
        "resourceGroups/".$this->ResourceGroup."/".
        "providers/Microsoft.Compute/".
        "virtualMachines/".$VMName."?".
        "api-version=2016-03-30";

        return $this->ProcSendData( $URL, 'GET', null, 0, true );
    }

    public function GetClassicVMInformation( ) {
        $URL = "https://".$this->ControlUrl."/".
        "subscriptions/".$this->SubscriptionId."/".
       // "resourceGroups/".$this->ResourceGroup."/".
        "providers/Microsoft.ClassicCompute/".
        "virtualMachines?".
        "api-version=2017-04-01";

        return $this->ProcSendData( $URL, 'GET', null, 0 );
    }

    public function GetVMInstanceInformation( $VMName ) {

        $URL = "https://".$this->ControlUrl."/".
        "subscriptions/".$this->SubscriptionId."/".
        "resourceGroups/".$this->ResourceGroup."/".
        "providers/Microsoft.Compute/".
        "virtualMachines/".$VMName."/".
        "InstanceView?".
        "api-version=2016-03-30";

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function ListDisksInSubscription() {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/providers/Microsoft.Compute/disks?'.
            'api-version=2016-04-30-preview';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetDisksDetail( $name ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Compute/'.
            'disks/'.$name.'?'.
            'api-version=2016-04-30-preview';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function CreateDiskI( $DiskName , $size, $tags = null) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Compute/'.
            'disks/'.$DiskName.'?'.
            'api-version=2016-04-30-preview';

        $json = array( "name" => $DiskName,
                        "location" => $this->Location,
                        "properties" => array( 
                            "creationData" => array(  
                                "createOption" => "Empty"
                            ),
                            "diskSizeGB" => $size
                        )
                    );

        if( isset( $tags ) )
            $json["tags"] = $tags;

        return $this->ProcSendData( $URL, 'PUT', json_encode($json) );
    }

    public function CreateDiskFromUnmanagementDisk( $DiskName , $connectionString, $container, $filename, $size ,$tags = null ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Compute/'.
            'disks/'.$DiskName.'?'.
            'api-version=2017-03-30';

        $blobEndpoint = $this->parseConnectionString( $connectionString );

        $json = array( "name" => $DiskName,
                        "location" => $this->Location,
                        "properties" => array( 
                            "creationData" => array(  
                                "createOption" => "Import",
                                "sourceUri" => "https://".$blobEndpoint["AccountName"].".blob.".$blobEndpoint["EndpointSuffix"]."/".$container."/".$filename,
                                //"storageAccountId" => "subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Storage/storageAccounts/".$storageAccount
                            ),
                            "diskSizeGB" => $size
                        )
                    );

        if( isset( $tags ) )
            $json["tags"] = $tags;

        return $this->ProcSendData( $URL, 'PUT', json_encode($json) );
    }

    public function CreateDiskFromSnapshot( $DiskName , $SnapshotName, $tags = null) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Compute/'.
            'disks/'.$DiskName.'?'.
            'api-version=2016-04-30-preview';

        $json = array( "name" => $DiskName,
                        "location" => $this->Location,
                        "properties" => array( 
                            "creationData" => array(  
                                "createOption" => "Copy",
                                "sourceResourceId" => "subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Compute/snapshots/".$SnapshotName
                            )
                        )
                    );

        if( isset( $tags ) )
            $json["tags"] = $tags;

        return $this->ProcSendData( $URL, 'PUT', json_encode($json) );
    }

    public function DeleteDiskI( $DiskName ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Compute/'.
            'disks/'.$DiskName.'?'.
            'api-version=2016-04-30-preview';

        return $this->ProcSendData( $URL, 'DELETE', '' );
    }

    public function CreateSnapshotI( $SnapshotName , $DiskName, $tags) {

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Compute/'.
            'snapshots/'.$SnapshotName.'?'.
            'api-version=2016-04-30-preview';

        $json = array( "name" => $SnapshotName,
                        "location" => $this->Location,
                        "properties" => array( 
                            "creationData" => array(
                                "createOption" => "Copy",
                                "sourceUri" => "/subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Compute/disks/".$DiskName
                            )
                        )
                    );

        if( isset( $tags ) )
            $json["tags"] = $tags;
        
        return $this->ProcSendData( $URL, 'PUT', json_encode($json) );
    }

    public function DeleteSnapshotI( $snapshotName ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/'.
            'snapshots/'.$snapshotName.'?'.
            'api-version=2016-04-30-preview';

        return $this->ProcSendData( $URL, 'DELETE', '' );
    }

    public function ListSnapshotsInSubscriptions( ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/providers/Microsoft.Compute/'.
            'snapshots/?'.
            'api-version=2016-04-30-preview';

        return $this->ProcSendData( $URL, 'GET', '' );
    }

    public function GetSnapshotDetail( $snapshotName ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Compute/'.
            'snapshots/'.$snapshotName.'?'.
            'api-version=2016-04-30-preview';

        return $this->ProcSendData( $URL, 'GET', '' );
    }

    public function ListAvailabilitySetInRG( ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/availabilitySets?'.
            'api-version=2017-12-01';
        
        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function DeleteVM( $VMName ) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/'.
            'virtualMachines/'.$VMName.'?'.
            'api-version=2016-04-30-preview';

        return $this->ProcSendData( $URL, 'DELETE', '' );
    }

    public function CreateVMFromVHD( $VMName , $DiskName, $VmSize, $NetworkInterfaceName, $StorageInfo, $dataDiskArray = null, $param = null) {

        $blobEndpoint = $this->parseConnectionString( $StorageInfo["connectionString"] );
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/'.
            'virtualMachines/'.$VMName.'?'.
            'api-version=2016-04-30-preview';

        $dataDiskConfig = null;
        
        if( $dataDiskArray ) {

            $dataDiskConfig = array();

            foreach( $dataDiskArray as $key => $disk ) {
                $diskConfig = array(
                    "name" => $disk,
                    "lun" => $key,
                    "createOption" => "Attach",
                    "vhd" => array(
                        "uri"=>"https://".$blobEndpoint["AccountName"].
                        ".blob.".$blobEndpoint["EndpointSuffix"]."/".$StorageInfo["container"].
                        "/".$disk
                    )
                );

                array_push( $dataDiskConfig , $diskConfig );
            }
        }

        $json = $this->GetVMConfigFromDisk( $VMName , $DiskName, $VmSize, $NetworkInterfaceName, $dataDiskConfig, $param );

        unset( $json["properties"]["storageProfile"]["osDisk"]["managedDisk"] );

        $json["properties"]["storageProfile"]["osDisk"]["createOption"] = "Attach";
        $json["properties"]["storageProfile"]["osDisk"]["caching"] = "ReadWrite";
        $json["properties"]["storageProfile"]["osDisk"]["name"] = $DiskName;

        $json["properties"]["storageProfile"]["osDisk"]["vhd"] = array( 
            "uri" => "https://".$blobEndpoint["AccountName"].
            ".blob.".$blobEndpoint["EndpointSuffix"]."/".$StorageInfo["container"].
            "/".$DiskName
        );

        return $this->ProcSendData( $URL, 'PUT', json_encode($json, JSON_UNESCAPED_SLASHES) );
    }

    public function CreateVMFromDisk( $VMName , $DiskName, $VmSize, $NetworkInterfaceName, $dataDiskArray = null, $param = null) {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/'.
            'virtualMachines/'.$VMName.'?'.
            'api-version=2016-04-30-preview';

        $dataDiskConfig = null;
        
        if( $dataDiskArray ) {

            $dataDiskConfig = array();

            foreach( $dataDiskArray as $key => $disk ) {
                $diskConfig = array(
                   // "name" => $disk,
                    "lun" => $key,
                    "createOption" => "Attach",
                    "managedDisk" => array(
                        "id" => "/subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Compute/disks/".$disk,
                        "storageAccountType" => "Standard_LRS"
                    )
                );

                if( isset( $param["DiskType"] ) && $param["DiskType"] == "SSD" )
                    $diskConfig["managedDisk"]["storageAccountType"] = "Premium_LRS";

                array_push( $dataDiskConfig , $diskConfig );
            }
        }

        $json = $this->GetVMConfigFromDisk( $VMName , $DiskName, $VmSize, $NetworkInterfaceName, $dataDiskConfig, $param );

        return $this->ProcSendData( $URL, 'PUT', json_encode($json, JSON_UNESCAPED_SLASHES) );
    }

    public function GetVMConfigFromDisk( $VMName , $diskName , $VmSize, $NetworkInterfaceName, $dataDisk = null, $param = null ) {
        $json = array( "name" => $VMName,
                        "location" => $this->Location,
                        "tags" => array(
                            "factory" => "Created by Saasame." 
                            ),
                        "properties" => array(
                            //"licenseType" => "Windows_Server",
                            "hardwareProfile" => array( 
                                "vmSize" => $VmSize 
                                ),
                            "storageProfile" => array(
                                "osDisk" => array(
                                    "osType" => "Windows",
                                    "createOption" => "Attach",
                                    "managedDisk" => array(
                                        //"id" => "[resourceId('Microsoft.Compute/disks', [concat('".$diskName."', copyindex())])]",
                                        "id" => "/subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Compute/disks/".$diskName,
                                        "storageAccountType" => "Standard_LRS"
                                    )
                                )
                            ),
                            "networkProfile" => array(
                                "networkInterfaces" => array(
                                    array(
                                        "id" => "/subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Network/networkInterfaces/".$NetworkInterfaceName,
                                        "properties" => array(
                                            "primary" => true
                                        )
                                    )
                                )
                            )/*,
                            "diagnosticsProfile" => array(
                                "bootDiagnostics" => array(
                                    "enabled" => true,
                                    "storageUri" => " http://testsaasame.blob.core.windows.net/"
                                )
                            )*/
                        )
                    );

        if( $dataDisk ) 
            $json["properties"]["storageProfile"]["dataDisks"] = $dataDisk ;
        
        if( isset( $param["HostName"] ) )
            $json["tags"]["HostName"] = $param["HostName"];

        if( isset( $param["OsType"] ) )
            $json["properties"]["storageProfile"]["osDisk"]["osType"] = $param["OsType"];

        if( isset( $param["AvailabilitySetId"] ) && $param["AvailabilitySetId"] != false)
            $json["properties"]["availabilitySet"]["id"] = $param["AvailabilitySetId"];

        if( isset( $param["DiskType"] ) && $param["DiskType"] == "SSD" )
            $json["properties"]["storageProfile"]["osDisk"]["managedDisk"]["storageAccountType"] = "Premium_LRS";
            
        return $json;
    }


    public function GetVMSize() {

        $URL = 'https://'.$this->ControlUrl.'/subscriptions/'.$this->SubscriptionId.'/providers/Microsoft.Compute/locations/'.$this->Location.'/vmSizes?api-version=2016-04-30-preview';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetNetworkLsit() {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'providers/Microsoft.Network/'.
            'networkInterfaces?'.
            'api-version=2017-03-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function CreateNetworkInterface( $name, $netSecurityGroup, $virtualNetwork, $subnet, $publicIp = null, $privateIp = null, $config = null) {
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'networkInterfaces/'.$name.'?'.
            'api-version=2017-03-01';

        if( !$config )
            $json = $this->GetNetworkInterfaceConfig( $netSecurityGroup, $virtualNetwork, $subnet, $publicIp, $privateIp);
        else
            $json = $config;
            
        return $this->ProcSendData( $URL, 'PUT', json_encode($json, JSON_UNESCAPED_SLASHES) );
    }

    public function GetNetworkInterfaceConfig( $netSecurityGroup, $virtualNetwork, $subnet, $publicIp, $privateIp ) {
        $json = array(  "location" => $this->Location,
                        "tags" => array(
                            "factory" => "Created by SaaSaMe." 
                            ),
                        "properties" => array(
                            "networkSecurityGroup" => array( 
                                "id" => "/subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Network/networkSecurityGroups/".$netSecurityGroup
                                ),
                            "ipConfigurations" => array(
                                array(
                                    "name" => "ipconfig1",
                                    "properties" => array(
                                        "subnet" =>array(
                                            "id" => "/subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Network/virtualNetworks/".$virtualNetwork."/subnets/".$subnet
                                        ),
                                        "privateIPAllocationMethod" => "Dynamic",
                                        "publicIPAddress" => array(
                                            "id" => "/subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Network/publicIPAddresses/".$publicIp
                                        )
                                    )
                                )
                            )
                        )
                    );

        if( !$publicIp )
            unset( $json["properties"]["ipConfigurations"][0]["properties"]["publicIPAddress"] );

        if( $privateIp ){
            $json["properties"]["ipConfigurations"][0]["properties"]["privateIPAllocationMethod"] = "Static";
            $json["properties"]["ipConfigurations"][0]["properties"]["privateIPAddress"] = $privateIp;
        }

        return $json;
    }

    public function CheckIpaddressAvailability( $virtualNetwork, $ipaddress ) {

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'virtualNetworks/'.$virtualNetwork.'/'.
            'CheckIPAddressAvailability?ipAddress='.$ipaddress.'&'.
            'api-version=2018-01-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function DeleteNetworkInterface( $name ) {
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'networkInterfaces/'.$name.'?'.
            'api-version=2016-09-01';

        return $this->ProcSendData( $URL, 'DELETE', null );
    }

    public function CreatePublicIp( $name, $ipStatic = false ) {
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'publicIPAddresses/'.$name.'?'.
            'api-version=2016-09-01';

        $json = $this->GetPublicIpConfig( $ipStatic );

        return $this->ProcSendData( $URL, 'PUT', json_encode($json, JSON_UNESCAPED_SLASHES) );
    }

    public function DeletePublicIp( $name ) {
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'publicIPAddresses/'.$name.'?'.
            'api-version=2016-09-01';

        return $this->ProcSendData( $URL, 'DELETE', null );
    }

    public function GetPublicIpConfig( $ipStatic ) {

        $json = array(  "location" => $this->Location,
                        "tags" => array(
                            "factory" => "Created by SaaSaMe." 
                        ),
                        "properties" => array(
                            "publicIPAllocationMethod" => "Dynamic"
                        )
                    );

        if( $ipStatic )
            $json["properties"]["publicIPAllocationMethod"] = "Static";

        return $json;
    }

    public function GetNetworkInterface( $NetworkInterfaceName ) {
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'networkInterfaces/'.$NetworkInterfaceName.'?'.
            'api-version=2016-09-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetPublicIPLsit() {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'providers/Microsoft.Network/'.
            'publicIPAddresses?'.
            'api-version=2017-03-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetNetworkSecurityGroupsLsit() {
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'providers/Microsoft.Network/'.
            'networkSecurityGroups?'.
            'api-version=2017-03-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetSubnetInVirtualNetwork( $virtualNetwork ) {
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'virtualNetworks/'.$virtualNetwork.'/'.
            'subnets?'.
            'api-version=2016-09-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetSubnetDetail( $virtualNetwork, $subnet ) {

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'virtualNetworks/'.$virtualNetwork.'/'.
            'subnets/'.$subnet.'?'.
            'api-version=2016-09-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetVirtualNetworkInResourceGroup( ) {
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'resourceGroups/'.$this->ResourceGroup.'/'.
            'providers/Microsoft.Network/'.
            'virtualNetworks?'.
            'api-version=2016-09-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function GetVirtualNetworkInSubscription( ) {

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'providers/Microsoft.Network/'.
            'virtualNetworks?'.
            'api-version=2016-09-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function AddDiskConfig( &$VM_info, $DiskName ) {

        $map = array();
        foreach( $VM_info['properties']['storageProfile']['dataDisks'] as $DiskInfo ) {
            $map[ $DiskInfo['lun'] ] = true; 
        }

        for( $i = 0;; $i++ ){
            if( !array_key_exists( $i , $map) )
                break;
        }

        $diskInfo = array(
            "lun" => $i,
            "name" => $DiskName,
            "createOption" => "attach",
            "managedDisk" => array(
                "id" => "/subscriptions/".$this->SubscriptionId."/resourceGroups/".$this->ResourceGroup."/providers/Microsoft.Compute/disks/".$DiskName,
                "storageAccountType" => "Standard_LRS"
            )
        );

        array_push( $VM_info['properties']['storageProfile']['dataDisks'], $diskInfo );
    }

    public function AddVHDConfig( &$VM_info, $diskSize, $storageAccount, $container, $filename, $blobEndpoint  ) {

        $map = array();
        foreach( $VM_info['properties']['storageProfile']['dataDisks'] as $DiskInfo ) {
            $map[ $DiskInfo['lun'] ] = true; 
        }

        for( $i = 0;; $i++ ){
            if( !array_key_exists( $i , $map) )
                break;
        }

        $diskInfo = array(
            "lun" => $i,
            "diskSizeGB" => $diskSize,
            "createOption" => "attach",
            "name" => $filename,
            "vhd" => array(
                "uri" => "https://".$storageAccount.".blob.".$blobEndpoint."/".$container."/".$filename
            )
        );

        array_push( $VM_info['properties']['storageProfile']['dataDisks'], $diskInfo );
    }

    public function RemoveDiskConfig( &$VM_info, $rmDisk ) {

        for($i = 0; $i < count( $VM_info["properties"]["storageProfile"]["dataDisks"] ); $i++ ) {
            if( strcmp( $VM_info["properties"]["storageProfile"]["dataDisks"][$i]["name"], $rmDisk ) == 0 )
                array_splice($VM_info["properties"]["storageProfile"]["dataDisks"], $i, 1);
        }
    }

    public function AttachDiskToVM( $tarVMName, $DiskNameArray ) {

        $VM_info = json_decode( $this->GetVMInformation( $tarVMName ), true );

        foreach( $DiskNameArray as $DiskName )
            $this->AddDiskConfig( $VM_info, $DiskName );

        $json = array( 
            "properties" =>array(
                "storageProfile" => array(
                    "dataDisks" => $VM_info['properties']['storageProfile']['dataDisks']
                )
            ),
            "location" => $this->Location
        );

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/virtualMachines/'.$tarVMName.'?'.
            'api-version=2016-04-30-preview';

        while( true ){

            try{
                return $this->ProcSendData( $URL, 'PUT', json_encode($json, JSON_UNESCAPED_SLASHES) );
            }   
            catch( Exception $e ){

                $errorMsg = $e->getMessage();

                if( strpos( $errorMsg, "AttachDiskWhileBeingDetached" ) === false )
                    throw new Exception( $errorMsg );

                sleep(15);
            }
        }
    }

    public function AttachVHDToVM( $tarVMName, $DiskInfos, $connectionString ) {

        $VM_info = json_decode( $this->GetVMInformation( $tarVMName ), true );

        $blobEndpoint = $this->parseConnectionString( $connectionString );

        foreach( $DiskInfos as $DiskInfo )
            $this->AddVHDConfig( $VM_info, $DiskInfo["diskSize"], 
                    $DiskInfo["storageAccount"], $DiskInfo["container"], $DiskInfo["filename"],
                    $blobEndpoint["EndpointSuffix"] );

        $json = array( 
            "properties" =>array(
                "storageProfile" => array(
                    "dataDisks" => $VM_info['properties']['storageProfile']['dataDisks']
                )
            ),
            "location" => $this->Location
        );

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/virtualMachines/'.$tarVMName.'?'.
            'api-version=2016-04-30-preview';

        while( true ){

            try{
                return $this->ProcSendData( $URL, 'PUT', json_encode($json, JSON_UNESCAPED_SLASHES) );
            }   
            catch( Exception $e ){

                $errorMsg = $e->getMessage();

                if( strpos( $errorMsg, "AttachDiskWhileBeingDetached" ) === false )
                    throw new Exception( $errorMsg );

                sleep(15);
            }
        }
    }

    public function DetachDiskFromVM( $tarVMName, $DiskNameArray ) {
  
        $VM_info = json_decode( $this->GetVMInformation( $tarVMName ), true );

        foreach( $DiskNameArray as $DiskName )
            $this->RemoveDiskConfig( $VM_info, $DiskName );

        $json = array( 
            "properties" =>array(
                "storageProfile" => array(
                    "dataDisks" => $VM_info['properties']['storageProfile']['dataDisks']
                )
            ),
            "location" => $this->Location
        );

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$this->ResourceGroup.
            '/providers/Microsoft.Compute/virtualMachines/'.$tarVMName.'?'.
            'api-version=2016-04-30-preview';

        while( true ){

            try{
                return $this->ProcSendData( $URL, 'PUT', json_encode($json, JSON_UNESCAPED_SLASHES) );
            }
            catch( Exception $e ){

                $errorMsg = $e->getMessage();

                if( strpos( $errorMsg, "AttachDiskWhileBeingDetached" ) === false )
                    throw new Exception( $errorMsg );

                sleep(15);
            }
        }
    }

    public function CreateResourceGroup( $rgName ) {

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$rgName.'?'.
            'api-version=2016-09-01';

        $json = array(
            "location" => $this->Location
        );
        return $this->ProcSendData( $URL, 'PUT', json_encode($json, JSON_UNESCAPED_SLASHES) );
    }

    public function DeleteResourceGroup( $rgName ) {

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.
            '/resourceGroups/'.$rgName.'?'.
            'api-version=2016-09-01';

        return $this->ProcSendData( $URL, 'DELETE','' );
    }

    public function ListLocation() {
        
        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions/'.$this->SubscriptionId.'/'.
            'locations?'.
            'api-version=2016-06-01';

        return $this->ProcSendData( $URL, 'GET', null );
    }

    public function ListSubscription() {

        $URL = 'https://'.$this->ControlUrl.'/'.
            'subscriptions?'.
            'api-version=2014-04-01-preview';

        return $this->ProcSendData( $URL, 'GET', null );
    }
}

?>