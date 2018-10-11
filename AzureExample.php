<?php
include_once 'AzureApi.php';
include_once 'Azure_Model.php';

    $AzureClient = new AzureApi();

    $client_id = "Application ID";
    $client_secret = "Security Key";
    $tenant = "Directory Id";
    $subscription_id="Subscription Id";

    $AzureClient->SetClientId($client_id);
    $AzureClient->SetClientSecret($client_secret);
    $AzureClient->SetLocation( 'Southeast Asia' );
    $AzureClient->SetResourceGroup( 'ResourceGroup' );
    $AzureClient->SetSubscriptionId( $subscription_id );

    $AzureClient->SetTenantId( $tenant );

    $AzureClient->SetEndpointType( 'azure_international' );
    $r = $AzureClient->ListSubscription();
    print_r(json_encode( $r) );
    exit;
//test
?>