<?php
class Gaggle_Storeselector_Block_Storeselector extends Mage_Core_Block_Template
{
	/*
	* Function for checking store selector is enabled from admin configuration.
	*/
	public function isEnable()
	{
		
		return Mage::getStoreConfig('storeselector_options/activation/module',Mage::app()->getStore()->getId());

	}
	
	/*
	* Function for getting range form admin configuration according to store
	*/
	public function getRangeOptions()
	{
		$range=Mage::getStoreConfig('storeselector_options/activation/range',Mage::app()->getStore()->getId());
		$range=explode(',',$range);
		$html='';
		foreach($range as $km)
		{
			$html.='<option value="'.$km.'">'.$km.' KM </option>';
		}
		return $html;
	}
	
	public function shouldShow()
	{
		
		if(Mage::getSingleton('core/cookie')->get('selectedstore'))
		{
			return false;
		}
		else
		{
			if($this->isEnable())
			{
				return true;
			}
		}
		
	}
	
	public function getMapForStore($height,$width)
	{
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode(Mage::getStoreConfig('storeselector_options/store/location',Mage::app()->getStore()->getId()))."&sensor=false";
		$result_string = file_get_contents($url);
		$result = json_decode($result_string, true);
		$lat=$result['results'][0]['geometry']['location']['lat'];
		$lng=$result['results'][0]['geometry']['location']['lng'];
		$url='http://maps.googleapis.com/maps/api/staticmap?center='.$lat.','.$lng.'&zoom=15&size='.$height.'x'.$width.'&markers=color:red|label:S|'.$lat.','.$lng.'';
		return $url;
	}
	
}