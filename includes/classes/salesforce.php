<?php
/**
 * Custom functions to consume Salesforce api
 */

define("SOAP_CLIENT_BASEDIR", AIVP_PATH."includes/salesforce/soapclient");
require_once (SOAP_CLIENT_BASEDIR.'/SforceEnterpriseClient.php');

class AIVPSalesforce {

	private $USERNAME = "glenn@executivemosaic.com";
	private $PASSWORD = "Vivian2015";
	private $conn = NULL;

	function __construct(){
		$this->conn = new SforceEnterpriseClient();
		$this->conn->createConnection(SOAP_CLIENT_BASEDIR.'/enterprise.wsdl.xml');
		$this->conn->login($this->USERNAME, $this->PASSWORD);
	}
	
	function upload_video( $video ) {
		try{
			$sObject = new stdClass;

			if(isset($video['HASHED_ID']))
				$sObject->VideoId__c = $video['HASHED_ID'];
			if(isset($video['THUMBNAIL_URL']))
				$sObject->VideoStillImageFile__c = $video['THUMBNAIL_URL'];
				$sObject->Media_Image__c = $video['THUMBNAIL_URL'];
			if(isset($video['CREATED_DATE']))
				$sObject->VideoCreated__c = $video['CREATED_DATE'];
			if(isset($video['DESCRIPTION']))
				$sObject->VideoDescription__c = $video['DESCRIPTION'];
				$sObject->Media_Summary__c = $video['DESCRIPTION'];
			if(isset($video['TITLE']))
				$sObject->Media_Title__c = $video['TITLE'];
				$sObject->VideoTitle__c = $video['TITLE'];

			$sObject->News_Category__c = 'Video ' . get_bloginfo( 'name' );

			$createResponse = $this->conn->create(array($sObject), 'Archintel__c');
			$ids = array();
			foreach ($createResponse as $createResult) {
				array_push($ids, $createResult->id);
			}
			return $ids[0];
		}catch (Exception $e) {
			return array('status' => 'error', 'message' => $e->faultstring);
			// return false;
		}
	}
    
}