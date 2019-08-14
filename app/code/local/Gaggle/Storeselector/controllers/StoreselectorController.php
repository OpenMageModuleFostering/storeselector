<?php
class Gaggle_Storeselector_StoreselectorController extends Mage_Core_Controller_Front_Action
{
	public function getmarkersAction()
	{
		
		$location=array();
		$stores=array();
		$center=$this->getRequest()->getParam('location');
		//var_dump($center);
		$distance=$this->getRequest()->getParam('distance');
		$stores=$this->getStores($distance,$center);
		foreach($stores as $store)
		{
			$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($store['name'])."&sensor=false";
			$result_string = file_get_contents($url);
			$result = json_decode($result_string, true);
			$location[] =array(/* 'name'=>$_post->getName(),
								'image'=>$image,
								'country'=>$_post->getCountry(),
								'city'=>$_post->getCity(),
								'state'=>$_post->getState(),	*/
								'store_code'=>$store['code'],
								'formatted_address'=>$result['results'][0]['formatted_address'], 
								'lat'=>$result['results'][0]['geometry']['location']['lat'],
								'lng'=>$result['results'][0]['geometry']['location']['lng']
								);
		}						
		echo json_encode($location);
				
			
		
							
	}
	public function getStores($distance,$center)
	{
		$locations=array();
		$i=0;
		foreach (Mage::app()->getWebsite()->getGroups() as $group) {
			$stores = $group->getStores();
				foreach ($stores as $store) {
			
					if($this->getDistance($this->getLocation($store),$center)<=$distance && $this->getDistance($this->getLocation($store),$center)!=0)
					{	
						 $locations[$i]['name']=$this->getLocation($store);
						 $locations[$i++]['code']=$store->getCode();
					}
			}
		}
		//die;
		return $locations;
	}
	public function getLocation($store)
	{
		//echo Mage::getStoreConfig('storeselector_options/store/location',$store->getId());
		return Mage::getStoreConfig('storeselector_options/store/location',$store->getId());
	}
	public function getDistance($addr1,$addr2)
	{
		$addr1=urlencode($addr1);
		$addr2=urlencode($addr2);
		$url = "http://maps.google.com/maps/api/directions/xml?origin=".$addr1."&destination=".$addr2."&sensor=false";
		$data = file_get_contents($url);
		$xml = simplexml_load_string($data);
		
		return (float)$xml->route->leg->distance->text;
	}
	public function selectstoreAction()
	{
		Mage::getSingleton('core/cookie')->set('selectedstore',$this->getRequest()->getParam('code'),time()+86400,'/');
		Mage::getSingleton('core/cookie')->set('selectedstore_address',$this->getRequest()->getParam('address'),time()+86400,'/');
		echo 'success';
	}
}