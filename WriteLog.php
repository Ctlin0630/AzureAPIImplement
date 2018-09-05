<?php

function function_debug($FOLDERNAME,$FILENAME,$DETAIL_INFO)
{
	#CONVERT ARRAY TO OBJECT
	$DETAIL_INFO = (object)$DETAIL_INFO;
	
	#FLAG
	$IS_ENABLE = true;
	
	if ($IS_ENABLE == TRUE)
	{
		try
		{
			$LOG_PATH = $_SERVER['DOCUMENT_ROOT'].'/_include/_debug/'.$FOLDERNAME;
			$LOG_FILE = $_SERVER['DOCUMENT_ROOT'].'/_include/_debug/'.$FOLDERNAME.'/'.$FILENAME.'.txt';
			
			#CHECK AND CREATE FOLDER EXISTS
			if(!file_exists($LOG_PATH))
			{
				mkdir($LOG_PATH);
			}				
			
			#CHECK AND CREATE FILE EXISTS
			if(!file_exists($LOG_FILE))
			{
				$fp = fopen($LOG_FILE,'w');
				if(!$fp)
				{
					throw new Exception('File open failed.');
				}
				else
				{
					fclose($fp);
				}
			}
			else
			{
				#LOG ARCHIVE
				//self::smart_log_archive($LOG_FILE);
			}
							
			#ADD MGMT LOG TIME
			$DETAIL_INFO -> mgmt_log_time = date('Y-m-d H:i:s');
					
			#MASKING PASSWORD
			if (isset($DETAIL_INFO -> username) or isset($DETAIL_INFO -> password))
			{
				$DETAIL_INFO -> username = 'string_masking';
				$DETAIL_INFO -> password = 'string_masking';					
			}
			
			$current  = file_get_contents($LOG_FILE);
			$current .= print_r($DETAIL_INFO,TRUE);
			$current .= "\n";
			file_put_contents($LOG_FILE, $current);				
		}
		catch (Throwable $e)
		{
			return false;
		}
	}
}
	
function define_mgmt_setting()
	{
		#DEFINE DEFAULT REGION
		$DEFINE_INI_FILE = __DIR__ .'\_transport\transport_mgmt.ini';

		if (file_exists($DEFINE_INI_FILE) != TRUE)
		{
			fopen($DEFINE_INI_FILE, 'w');
		}
		
		#IF THE FILE CANNOT BE OPENED REGEN THE INI
		if ((@parse_ini_file($DEFINE_INI_FILE, true)) == FALSE)
		{
			unlink($DEFINE_INI_FILE);
			$DEFAULT_REF = define_mgmt_ini();
			file_put_contents($DEFINE_INI_FILE,$DEFAULT_REF);
		}
		else
		{
			upgrade_mgmt_setting(); #CHECK FOR THE UPGRADE
		}
	
		return json_decode(json_encode(parse_ini_file($DEFINE_INI_FILE,true)),false);
	}

	function define_mgmt_ini()
	{
		$REF_SETUP  = "[report]\r\n";
		$REF_SETUP .= "address=\r\n";
		$REF_SETUP .= "enable=0\r\n";	
		$REF_SETUP .= "\r\n";
		
		/* Web Sockets */
		$REF_SETUP .= "[webdav]\r\n";
		$REF_SETUP .= "port=443\r\n";
		$REF_SETUP .= "ssl=1\r\n";
		$REF_SETUP .= "\r\n";
		
		$REF_SETUP .= "[verify]\r\n";
		$REF_SETUP .= "port=443\r\n";
		$REF_SETUP .= "ssl=1\r\n";
		$REF_SETUP .= "\r\n";
		
		$REF_SETUP .= "[scheduler]\r\n";
		$REF_SETUP .= "port=443\r\n";
		$REF_SETUP .= "ssl=1\r\n";
		$REF_SETUP .= "\r\n";
		
		$REF_SETUP .= "[loader]\r\n";
		$REF_SETUP .= "port=443\r\n";
		$REF_SETUP .= "ssl=1\r\n";
		$REF_SETUP .= "\r\n";
		
		$REF_SETUP .= "[launcher]\r\n";
		$REF_SETUP .= "port=443\r\n";
		$REF_SETUP .= "ssl=1\r\n";
		$REF_SETUP .= "\r\n";
		/* Web Sockets */
		
		/* Azure */
		$REF_SETUP .= "[azure_china]\r\n";
		$REF_SETUP .= "Auzre_ControlUrl=management.chinacloudapi.cn\r\n";
		$REF_SETUP .= "Auzre_LoginUrl=login.chinacloudapi.cn\r\n";
		$REF_SETUP .= "Azure_Resource=management.core.chinacloudapi.cn/\r\n";
		$REF_SETUP .= "\r\n";
		
		$REF_SETUP .= "[azure_international]\r\n";
		$REF_SETUP .= "Auzre_ControlUrl=management.azure.com\r\n";
		$REF_SETUP .= "Auzre_LoginUrl=login.microsoftonline.com\r\n";
		$REF_SETUP .= "Azure_Resource=management.core.windows.net/\r\n";
		$REF_SETUP .= "\r\n";
		
		$REF_SETUP .= "[azure_enpoint]\r\n";
		$REF_SETUP .= "International=azure_international\r\n";
		$REF_SETUP .= "China=azure_china\r\n";
		$REF_SETUP .= "\r\n";
		/* Azure */
		
		/* AWS */
		$REF_SETUP .= "[aws_region]\r\n";
		$REF_SETUP .= "default=us-east-1\r\n";
		$REF_SETUP .= "\r\n";
		
		$REF_SETUP .= "[aws_ec2_type]\r\n";
		$REF_SETUP .= "t2.nano='{\"Name\":\"t2.nano\",\"vCPU\":1,\"ECU\":\"Variable\",\"Memory\":\"0.5 GiB\",\"InstanceStorage\":\"EBS Only\"}'\r\n";
		$REF_SETUP .= "t2.micro='{\"Name\":\"t2.micro\",\"vCPU\":1,\"ECU\":\"Variable\",\"Memory\":\"1 GiB\",\"InstanceStorage\":\"EBS Only\"}'\r\n";
		$REF_SETUP .= "t2.small='{\"Name\":\"t2.small\",\"vCPU\":1,\"ECU\":\"Variable\",\"Memory\":\"2 GiB\",\"InstanceStorage\":\"EBS Only\"}'\r\n";
		$REF_SETUP .= "t2.medium='{\"Name\":\"t2.medium\",\"vCPU\":2,\"ECU\":\"Variable\",\"Memory\":\"4 GiB\",\"InstanceStorage\":\"EBS Only\"}'\r\n";
		$REF_SETUP .= "t2.large='{\"Name\":\"t2.large\",\"vCPU\":2,\"ECU\":\"Variable\",\"Memory\":\"8 GiB\",\"InstanceStorage\":\"EBS Only\"}'\r\n";
		$REF_SETUP .= "\r\n";		
		/* AWS */
				
		/* Alibaba Cloud */
		$REF_SETUP .= "[alibaba_cloud]\r\n";
		$REF_SETUP .= "recover_mode=1\r\n";
		$REF_SETUP .= "\r\n";
		/* Alibaba Cloud  */
		
		/* OpenStack */
		$REF_SETUP .= "[openstack]\r\n";
		$REF_SETUP .= "create_volume_from_image=0\r\n";
		$REF_SETUP .= "volume_create_type=SATA\r\n";
 		
		return $REF_SETUP;
	}

	function upgrade_mgmt_setting()
	{
		#DEFINE DEFAULT REGION
		$DEFINE_INI_FILE = __DIR__ .'\_transport\transport_mgmt.ini';
		
		$REGEN_INI = false;		
		$MGMT_INI = parse_ini_file($DEFINE_INI_FILE,true);
		
		if (isset($MGMT_INI['azure_select_enpoint']))
		{
			unset($MGMT_INI['azure_china']);
			unset($MGMT_INI['azure_international']);
			unset($MGMT_INI['azure_select_enpoint']);
			$REGEN_INI = true;
		}
		
		if (!isset($MGMT_INI['azure_china']) OR !isset($MGMT_INI['azure_international']) OR !isset($MGMT_INI['azure_enpoint']))
		{
			$MGMT_INI['azure_china']['Auzre_ControlUrl'] = 'management.chinacloudapi.cn';
			$MGMT_INI['azure_china']['Auzre_LoginUrl']   = 'login.chinacloudapi.cn';
			$MGMT_INI['azure_china']['Azure_Resource'] 	 = 'management.core.chinacloudapi.cn/';
			
			$MGMT_INI['azure_international']['Auzre_ControlUrl'] = 'management.azure.com';
			$MGMT_INI['azure_international']['Auzre_LoginUrl']   = 'login.microsoftonline.com';
			$MGMT_INI['azure_international']['Azure_Resource'] 	 = 'management.core.windows.net/';
			
			$MGMT_INI['azure_enpoint']['International'] = 'azure_international';
			$MGMT_INI['azure_enpoint']['China'] = 'azure_china';			
			$REGEN_INI = true;
		}
			
		if (!isset($MGMT_INI['aws_region']) OR !isset($MGMT_INI['aws_region']['default']))
		{
			$MGMT_INI['aws_region']['default'] = 'us-east-1';			
			$REGEN_INI = true;
		}

		if (!isset($MGMT_INI['aws_ec2_type']) OR count($MGMT_INI['aws_ec2_type']) == 0)
		{
			$MGMT_INI['aws_ec2_type']['t2.nano'] = "{\"Name\":\"t2.nano\",\"vCPU\":1,\"ECU\":\"Variable\",\"Memory\":\"0.5 GiB\",\"InstanceStorage\":\"EBS Only\"}";
			$MGMT_INI['aws_ec2_type']['t2.micro'] = "{\"Name\":\"t2.micro\",\"vCPU\":1,\"ECU\":\"Variable\",\"Memory\":\"1 GiB\",\"InstanceStorage\":\"EBS Only\"}";
			$MGMT_INI['aws_ec2_type']['t2.small'] = "{\"Name\":\"t2.small\",\"vCPU\":1,\"ECU\":\"Variable\",\"Memory\":\"2 GiB\",\"InstanceStorage\":\"EBS Only\"}";
			$MGMT_INI['aws_ec2_type']['t2.medium'] = "{\"Name\":\"t2.medium\",\"vCPU\":2,\"ECU\":\"Variable\",\"Memory\":\"4 GiB\",\"InstanceStorage\":\"EBS Only\"}";
			$MGMT_INI['aws_ec2_type']['t2.large'] = "{\"Name\":\"t2.large\",\"vCPU\":2,\"ECU\":\"Variable\",\"Memory\":\"8 GiB\",\"InstanceStorage\":\"EBS Only\"}";
			$REGEN_INI = true;
		}
		
		if (!isset($MGMT_INI['openstack']) OR !isset($MGMT_INI['openstack']['create_volume_from_image']))
		{
			$MGMT_INI['openstack']['create_volume_from_image'] = 0;			
			$REGEN_INI = true;
		}
		
		if (!isset($MGMT_INI['openstack']['volume_create_type']))
		{
			$MGMT_INI['openstack']['volume_create_type'] = 'SATA';			
			$REGEN_INI = true;
		}
		
		if ($REGEN_INI == TRUE)
		{
			$UPGRADE_INI = '';
			foreach ($MGMT_INI as $INI_TYPE => $INI_ITEM)
			{
				$UPGRADE_INI .= "[".$INI_TYPE."]\r\n";
				foreach ($INI_ITEM as $INI_KEY => $INI_VALUE)
				{
					if ($INI_TYPE == 'aws_ec2_type')
					{
						$UPGRADE_INI .= $INI_KEY."='".$INI_VALUE."'\r\n";
					}
					else
					{
						$UPGRADE_INI .= $INI_KEY."=".$INI_VALUE."\r\n";
					}
				}
				$UPGRADE_INI .= "\r\n";
			}

			file_put_contents($DEFINE_INI_FILE,$UPGRADE_INI);
		}
	}

	function encrypt( $source, $key )
	{
		$maxlength = 128;
		
		$output = '';
		
		while($source){
			
			$input= substr( $source, 0, $maxlength );
			
			$source=substr( $source, $maxlength );
			
			openssl_public_encrypt( $input, $encrypted, $key, OPENSSL_PKCS1_PADDING );

			$output.=$encrypted;
		}
		
		$output = base64_encode($output);
		
		return $output;
	}
	
	#DECRYPT LOOP
	function decrypt( $source, $key )
	{
		$maxlength = 256;
		
		$output = '';
		
		$source = base64_decode($source);

		while($source){
			
			$input= substr($source,0,$maxlength);
			
			$source=substr($source,$maxlength);
			
			openssl_private_decrypt($input,$out,$key);

			$output.=$out;
		}
		
		return $output;

	}
	
	#ENCRYPT DECRYPT
	function encrypt_decrypt($ACTION,$STRING)
	{
		$CERT_ROOT = getenv('WEBROOT').'/apache24/conf/ssl';				
		$PUBLIC_KEY_PATH = $CERT_ROOT.'/server.crt';
		$PRIVATE_KEY_PATH = $CERT_ROOT.'/server.key';
		
		switch ($ACTION)
		{
			case 'encrypt':
				#FILE OPEN PRIVATE KEY
				$OpenPublicKey = fopen($PUBLIC_KEY_PATH,"r");
				$PublicKey = fread($OpenPublicKey,8192);
				fclose($OpenPublicKey);
				
				return encrypt( $STRING, $PublicKey);
			break;
			
			case 'decrypt':
				#OPEN PRIVATE KEY FILE
				$OpenPrivatKey = fopen($PRIVATE_KEY_PATH,"r");
				$PrivateKey = fread($OpenPrivatKey,8192);
				fclose($OpenPrivatKey);
				
				return decrypt( $STRING, $PrivateKey);		
			break;
		}
	}

	function current_utc_time()
	{
		return gmdate("Y-m-d H:i:s", time());		
	}
?>