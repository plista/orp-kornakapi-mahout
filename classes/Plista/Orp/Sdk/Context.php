<?php
namespace Plista\Orp\Sdk;

/*
 * This class contains the input vectors and the function to access the informations from the json_string
 */

class Context {

	/**
	 * input vectors
	 */
	const GENDER = 1;
	const AGE = 2;
	const INCOME = 3;
	const BROWSER = 4;
	const ISP = 5;
	const OS = 6;
	const GEO_USER = 7;
	const PUBLISHER_FILTER = 8;
	const TIME_WEEKDAY = 9;
	const CHANNEL = 10;
	const CATEGORY = 11;
	const POSITION = 12;
	const DO_NOT_TRACK = 13;
	const WIDGET_KIND = 14;
	const WEATHER = 15;
	const GEO_PUBLISHER = 16;
	const LANG_USER = 17;
	const POSITION_IN_WIDGET = 18;
	const SUBID = 19;
	const TIME_TO_ACTION = 20;
	const WIDGET_PAGE = 21;
	const GEO_USER_ZIP = 22;
	const TIME_HOUR = 23;
	const USER_PUBLISHER_IMPRESSIONS = 24;
	const ITEM_SOURCE = 25;
	const RETARGETING = 26;
	const PUBLISHER = 27;
	const USER_CAMPAIGN_IMPRESSIONS = 28;
	const SSP = 29;
	const PIXEL_3RD_PARTY = 30;
	const GEO_USER_RADIUS = 31;
	const KEYWORD = 33;
	const REDUCED_BID = 34;
	const ADBLOCKER = 35;
	const BID_CPM = 36;
	const RELEASE = 37;
	const PRESENTDAY = 38;
	const WIDGET_ID = 39;
	const DIMENSION_SCREEN = 40;
	const CONTEST_TEAM = 41;
	const ITEM_STATUS = 42;
	const PIXEL_4TH_PARTY = 43;
	const PUBLISHER_REFERER = 44;
	const SSP_PUBLISHERID = 45;
	const CATEGORY_SEM = 46;
	const DEVICE_TYPE = 47;
	const GEO_TYPE = 48;
	const TIME_MINUTE_30 = 49;
	const CPO = 50;
	const ITEM_AGE = 51;
	const FILTER_ALLOWOSR = 52;
	const URL = 53;
	const SSP_QUALIFIER = 54;
	const SSP_NETWORK = 55;
	const BROWSER_3RD_PARTY_SUPPORT = 56;
	const USER_COOKIE = 57; // the user id taken from the cookie
	const CHANNEL_SEM = 58;
	const TRANSPORT_PROTOCOL = 59;
	const FILTER = 60;

	private $data;

	public function __construct(array $data) {
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getGender() {
		return $this->data['clusters'][self::GENDER];
	}

	/**
	 * @return array
	 */
	public function getAge() {
		return $this->data['clusters'][self::AGE];
	}

	/**
	 * @return array
	 */
	public function getIncome() {
		return $this->data['clusters'][self::INCOME];
	}

	/**
	 * @return int
	 */
	public function getBrowser() { //4
		return $this->data['simple'][self::BROWSER];
	}

	/**
	 * @return int
	 */
	public function getIsp() {
		return $this->data['simple'][self::ISP];
	}

	/**
	 * @return int
	 */
	public function getOs() {
		return $this->data['simple'][self::OS];
	}

	/**
	 * @return int
	 */
	public function getGeo_user() {
		return $this->data['simple'][self::GEO_USER];
	}

	public function getPublisher_filter() { // 8
		return $this->data['lists'][self::PUBLISHER_FILTER];
	}

	/**
	 * @return int
	 */
	public function getTime_weekday() {
		return $this->data['simple'][self::TIME_WEEKDAY];
	}

	public function getChannel() {
		return $this->data['lists'][self::CHANNEL];
	}

	public function getCategory() {
		return $this->data['lists'][self::CATEGORY];
	}

	/**
	 * @return int
	 */
	public function getPosition() {
		return $this->data['simple'][self::POSITION];
	}

	/**
	 * @return int
	 */
	public function getDo_not_track() {
		return $this->data['simple'][self::DO_NOT_TRACK];
	}

	/**
	 * @return int
	 */
	public function getWidget_kind() {
		return $this->data['simple'][self::WIDGET_KIND];
	}

	/**
	 * @return int
	 */
	public function getWeather() {
		return $this->data['simple'][self::WEATHER];
	}

	/**
	 * @return int
	 */
	public function getGeo_publisher() {
		return $this->data['simple'][self::GEO_PUBLISHER];
	}

	/**
	 * @return int
	 */
	public function getLang_user() {
		return $this->data['simple'][self::LANG_USER];
	}

	/**
	 * @return int
	 */
	public function getPosition_in_widget() {
		return $this->data['simple'][self::POSITION_IN_WIDGET];
	}

	/**
	 * @return int
	 */
	public function getSubid() {
		return $this->data['simple'][self::SUBID];
	}

	/**
	 * @return int
	 */
	public function getTime_to_action() {
		return $this->data['simple'][self::TIME_TO_ACTION];
	}

	/**
	 * @return int
	 */
	public function getWidget_page() {
		return $this->data['simple'][self::WIDGET_PAGE];
	}

	/**
	 * @return int
	 */
	public function getGeo_user_zip() {
		return $this->data['simple'][self::GEO_USER_ZIP];
	}

	/**
	 * @return int
	 */
	public function getTime_hour() {
		return $this->data['simple'][self::TIME_HOUR];
	}

	/**
	 * @return int
	 */
	public function getUser_publisher_impression() {
		return $this->data['simple'][self::USER_PUBLISHER_IMPRESSIONS];
	}

	/**
	 * @return int
	 */
	public function getItem_source() {
		if(!empty($this->data['simple'][self::ITEM_SOURCE])){
			return $this->data['simple'][self::ITEM_SOURCE];
		}
		return null;
	}

	/**
	 * @return int
	 */
	public function getRetargeting() {
		return $this->data['simple'][self::RETARGETING];
	}

	/**
	 * @return int
	 */
	public function getPublisher() { //27
		return $this->data['simple'][self::PUBLISHER];
	}

	/**
	 * @return int
	 */
	public function getUser_campaign_impression() {
		return $this->data['simple'][self::USER_CAMPAIGN_IMPRESSIONS];
	}

	/**
	 * @return int
	 */
	public function getSsp() {
		return $this->data['simple'][self::SSP];
	}

	/**
	 * @return int
	 */
	public function getPixel_3rd_pard() {
		return $this->data['simple'][self::PIXEL_3RD_PARTY];
	}

	/**
	 * @return int
	 */
	public function getGeo_user_radius() {
		return $this->data['simple'][self::GEO_USER_RADIUS];
	}

	/**
	 * @return array
	 */
	public function getKeyword() { // 33
		return $this->data['clusters'][self::KEYWORD];
	}

	/**
	 * @return int
	 */
	public function getReduced_bid() {
		return $this->data['simple'][self::REDUCED_BID];
	}

	/**
	 * @return int
	 */
	public function getAdblocker() {
		return $this->data['simple'][self::ADBLOCKER];
	}

	/**
	 * @return int
	 */
	public function getBid_cpm() {
		return $this->data['simple'][self::BID_CPM];
	}

	/**
	 * @return int
	 */
	public function getRelease() {
		return $this->data['simple'][self::RELEASE];
	}

	/**
	 * @return int
	 */
	public function getPresentday() {
		return $this->data['simple'][self::PRESENTDAY];
	}

	/**
	 * @return int
	 */
	public function getWidget_id() {
		return $this->data['simple'][self::WIDGET_ID];
	}

	/**
	 * @return int
	 */
	public function getDimension_screen() {
		return $this->data['simple'][self::DIMENSION_SCREEN];
	}

	/**
	 * @return int
	 */
	public function getContest_team() {
		return $this->data['simple'][self::CONTEST_TEAM];
	}

	/**
	 * @return int
	 */
	public function getItem_status() {
		return $this->data['simple'][self::ITEM_STATUS];
	}

	/**
	 * @return int
	 */
	public function getPixel_4th_party() {
		return $this->data['simple'][self::PIXEL_4TH_PARTY];
	}

	/**
	 * @return int
	 */
	public function getPublisher_referer() {
		return $this->data['simple'][self::PUBLISHER_REFERER];
	}

	/**
	 * @return int
	 */
	public function getSsp_publisherid() {
		return $this->data['simple'][self::SSP_PUBLISHERID];
	}

	/**
	 * @return array
	 */
	public function getCategory_sem() {
		return $this->data['clusters'][self::CATEGORY_SEM];
	}

	/**
	 * @return int
	 */
	public function getDevice_type() {
		return $this->data['simple'][self::DEVICE_TYPE];
	}

	/**
	 * @return int
	 */
	public function getGeo_type() {
		return $this->data['simple'][self::GEO_TYPE];
	}

	/**
	 * @return int
	 */
	public function getTime_minute_30() {
		return $this->data['simple'][self::TIME_MINUTE_30];
	}

	/**
	 * @return int
	 */
	public function getCpo() {
		return $this->data['simple'][self::CPO];
	}

	/**
	 * @return array
	 */
	public function getItem_age() {
		return $this->data['clusters'][self::ITEM_AGE];
	}

	/**
	 * @return int
	 */
	public function getFilter_allowosr() {
		return $this->data['simple'][self::FILTER_ALLOWOSR];
	}

	/**
	 * @return int
	 */
	public function getUrl() {
		return $this->data['simple'][self::URL];
	}

	/**
	 * @return int
	 */
	public function getSsp_qualifier() {
		return $this->data['simple'][self::SSP_QUALIFIER];
	}

	/**
	 * @return int
	 */
	public function getSsp_network() {
		return $this->data['simple'][self::SSP_NETWORK];
	}

	/**
	 * @return int
	 */
	public function getBrowser_3rd_party_support() {
		return $this->data['simple'][self::BROWSER_3RD_PARTY_SUPPORT];
	}

	/**
	 * @return int
	 */
	public function getUser_cookie() {
		return $this->data['simple'][self::USER_COOKIE];
	}

	public function getChannel_sem() {
		return $this->data['lists'][self::CHANNEL_SEM];
	}

	/**
	 * @return int
	 */
	public function gettransport_protocol() {
		return $this->data['simple'][self::TRANSPORT_PROTOCOL];
	}
}
