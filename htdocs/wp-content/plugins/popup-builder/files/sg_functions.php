<?php
class SGFunctions
{
	public static function showInfo()
	{
		$sgInfo = '';
		$divisor = "<span class=\"info-vertical-divisor\">|</span>";
		$sgInfo .= "<span>If you like the plugin, please <a href=\"https://wordpress.org/support/view/plugin-reviews/popup-builder?filter=5\" target=\"_blank\">rate it 5 stars</a></span>".$divisor;
		$sgInfo .= "<a href=\"https://wordpress.org/support/plugin/popup-builder\" target=\"_blank\">Support</a>".$divisor;
		$sgInfo .= "<a href=\"https://www.youtube.com/watch?v=3ZwRKPhHMzY\" target=\"_blank\">How to create a popup</a>";
		echo $sgInfo;
	}

	public static function balckFriday()
	{
		$output = '<a href="'.SG_POPUP_PRO_URL.'" target="_blank"><div class="sg-black-friday-wrapper">
			<div class="sg-balck-info-wrapper">
				<div class="sg-black-info sg-balck-info-left">
					<span class="sg-black-friday-day">
						November 25
					</span>
					<span class="sg-black-friday-name">
						BLACK FRIDAY
					</span>
				</div>
				<div class="balck-friday-line-image balck-friday-line-left"></div>
				<div class="sg-black-info sg-balck-info-center">
					<span class="sg-black-percent">
						-25%
					</span>
					<span class="sg-black-all-plans">
						* All Plans
					</span>
				</div>
				<div class="balck-friday-line-image balck-friday-line-right"></div>
				<div class="sg-black-info sg-balck-info-right">
					<span class="sg-black-friday-day">
						Use this promo code to get 25% off:
					</span>
					<span class="sg-black-friday-name">
						POPUP 25 OFF
					</span>
				</div>
			</div>
		</div></a>';
		echo $output;
	}

	public static function sgPopupDataSanitize($sgPopupData)
	{

		$allowedHtmltags = wp_kses_allowed_html('post');
		$allowedHtmltags['input'] = array('name'=>true, 'class'=>true, 'id'=>true, 'placeholder'=>true, 'title'=>true, 'value'=>true, 'type'=>true, 'src'=>true);
		$allowedHtmltags['select'] = array('name'=>true, 'class'=>true, 'id'=>true, 'placeholder'=>true, 'title'=>true, 'size'=>true, 'multiple'=>true, 'disabled'=>true,'autofocus'=>true);
		$allowedHtmltags['option'] = array('value'=>true, 'class'=>true, 'id'=>true, 'placeholder'=>true, 'selected'=>true, 'label'=>true, 'disabled'=>true);
		$allowedHtmltags['link'] = array('href'=>true, 'charset'=>true, 'hreflang'=>true, 'media'=>true, 'rel'=>true, 'rev'=>true, 'sizes'=>true,'type'=>true);
		$allowedHtmltags['script'] = array('src'=>true, 'type'=>true, 'async'=>true, 'charset'=>true);
		$allowedHtmltags['style'] = array('type'=>true, 'media'=>true, 'scoped'=>true);
		return wp_kses($sgPopupData, $allowedHtmltags);
	}

	public static function popupTablesDeleteSatus() {

		global $wpdb;

		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_popup_settings WHERE id = %d",1);
		$arr = $wpdb->get_row($st,ARRAY_A);
		$options = json_decode($arr['options'], true);
		$deleteStatus = ($options['tables-delete-status'] == 'on' ? true: false);
		
		return $deleteStatus;
	}

	public static function addReview()
	{
		return '<div class="sg-info-panel-wrapper">
			<div class="sg-info-panel-row">
				<div class="sg-info-panel-col-3">
					<p class="sg-info-text sg-info-logo">
						<span class="sg-info-text-white">Popup</span><span class="sg-info-text-highlight">Builder</span>
					</p>
					<p class="sg-info-text">
						If you have any difficulties in using the options, please follow the link to <a href="https://sygnoos.ladesk.com/235213-Popup-Builder" class="sg-info-link">Knowledge Base</a>
					</p>
				</div>
				<div class="sg-info-panel-col-3 sg-info-text-center">
					<a class="sg-info-upgrade-pro sg-info-blink" href="http://popup-builder.com" target="_blank">
						Upgrade NOW
					</a>
					<p class="sg-info-text">
						Want to upgrade to PRO version?<br> Just click on "Upgrade NOW".
					</p>
				</div>
				<div class="sg-info-panel-col-3">
					<ul class="sg-info-menu sg-info-text">
						<li>
							<a class="sg-info-links" target="_blank" href="https://wordpress.org/support/plugin/popup-builder/reviews/?filter=5"><span class="dashicons dashicons-heart sg-info-text-white"></span><span class="sg-info-text"> Rate Us</span></a>
						</li>
						<li>
							<a class="sg-info-links" target="_blank" href="https://sygnoos.ladesk.com/submit_ticket"><span class="dashicons dashicons-megaphone sg-info-text-white"></span></span> Submit Ticket</a>
						</li>
						<li>
							<a class="sg-info-links" target="_blank" href="https://wordpress.org/plugins/popup-builder/faq/"><span class="dashicons dashicons-editor-help sg-info-text-white"></span> FAQ</a>
						</li>
						<li>
							<a class="sg-info-links" target="_blank" href="mailto:support@popup-builder.com?subject=Hello"><span class="dashicons dashicons-email-alt sg-info-text-white"></span></span> Contact</a>
						</li>
					</ul>
				</div>
			</div>
			<div>
				<span class="sg-info-close">+</span>
				<span class="sg-dont-show-agin">Don’t show again.</span>
			</div>
		</div>';
	}

	public static function noticeForShortcode() {
		$notice = '<span class="shortcode-use-info">NOTE: Shortcodes doesn\'t work inside the HTML Popup. Please use <a href="'.SG_APP_POPUP_ADMIN_URL.'admin.php?page=edit-popup&type=shortcode">Shortcode Popup</a> instead.</span>';
		return $notice;
	}

	public static function createSelectBox($data, $selectedValue, $attrs) {

 		$attrString = '';
		$selected = '';
		
		if(!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$selectBox = '<select '.$attrString.'>';

		foreach ($data as $value => $label) {

			/*When is multiselect*/
			if(is_array($selectedValue)) {
				$isSelected = in_array($value, $selectedValue);
				if($isSelected) {
					$selected = 'selected';
				}
			}
			else if($selectedValue == $value) {
				$selected = 'selected';
			}
			else if(is_array($value) && is_array($selectedValue, $value)) {
				$selected = 'selected';
			}

			$selectBox .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
			$selected = '';
		}

		$selectBox .= '</select>';

		return $selectBox;
 	}

	public static function sgCreateRadioElements($radioElements, $checkedValue)
	{
		$content = '';
		for ($i = 0; $i < count($radioElements); $i++) {
			$checked = '';
			$br = '';
			$radioElement = @$radioElements[$i];
			$name = @$radioElement['name'];
			$label = @$radioElement['label'];
			$value = @$radioElement['value'];
			$brValue = @$radioElement['newline'];
			$additionalHtml = @$radioElement['additionalHtml'];
			if($checkedValue == $value) {
				$checked = 'checked';
			}
			if($brValue) {
				$br = "<br>";
			}
			$content .= '<input class="radio-btn-fix" type="radio" name="'.$name.'" value="'.$value.'" '.$checked.'>';
			$content .= $additionalHtml.$br;
		}
		return $content;
	}

	public static function getUserIpAdress() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public static function getCountryName($ip)
	{
		if(empty($_COOKIE['SG_POPUP_USER_COUNTRY_NAME'])) {
			
			try {
				
				$details = wp_remote_get(SG_IP_TO_COUNTRY_SERVICE_URL.$ip."&".SG_IP_TO_COUNTRY_SERVICE_TOKEN, array( 'timeout' => SG_IP_TO_COUNTRY_SERVICE_TIMEOUT));$details = wp_remote_get(SG_IP_TO_COUNTRY_SERVICE_URL.$ip."&".SG_IP_TO_COUNTRY_SERVICE_TOKEN, array( 'timeout' => SG_IP_TO_COUNTRY_SERVICE_TIMEOUT));
				$dataIp = json_decode($details['body'], true);
				if(!is_array($dataIp)) {
					return true;
				}
				$counrty = @$dataIp['country'];
			}
			catch ( Exception $ex ) {
				return true;
			}
			
		}
		else {
			$counrty = $_COOKIE['SG_POPUP_USER_COUNTRY_NAME'];
		}
		
		return $counrty;
	}

	public static function getUserLocationData($popupId) {
		$obj = SGPopup::findById($popupId);
		$countryStatus = '';
		$countryName = '';

		if($obj) {
			$options = json_decode($obj->getOptions(), true);
			$countryStatus = $options['countryStatus'];
		}

		if(!empty($countryStatus)) {
			$ip = SGFunctions::getUserIpAdress();
			$countryName = SGFunctions::getCountryName($ip);
		}
		if(!empty($countryName)) {
			return $countryName;
		}
		return false;
	}

	public static function isShowMenuForCurrentUser() {
		$usersSelectedRoles = SgPopupGetData::getValue('plugin_users_role', 'settings');
		
		$currentUserRole = SgPopupGetData::getCurrentUserRole();

		/*When user is admintrator or this user is in selected rules*/
		if(@in_array($currentUserRole, $usersSelectedRoles) || $currentUserRole == 'sgpb_administrator') {
			return true;
		}
		return false;
	}

	public static function countrisSelect() {

		return '<select id="sameWidthinputs" name="countris" class="optionsCountry"  data-role="tagsinput">
					<option value="AF">Afghanistan</option>
					<option value="AX">Åland Islands</option>
					<option value="AL">Albania</option>
					<option value="DZ">Algeria</option>
					<option value="AS">American Samoa</option>
					<option value="AD">Andorra</option>
					<option value="AO">Angola</option>
					<option value="AI">Anguilla</option>
					<option value="AQ">Antarctica</option>
					<option value="AG">Antigua and Barbuda</option>
					<option value="AR">Argentina</option>
					<option value="AM">Armenia</option>
					<option value="AW">Aruba</option>
					<option value="AU">Australia</option>
					<option value="AT">Austria</option>
					<option value="AZ">Azerbaijan</option>
					<option value="BS">Bahamas</option>
					<option value="BH">Bahrain</option>
					<option value="BD">Bangladesh</option>
					<option value="BB">Barbados</option>
					<option value="BY">Belarus</option>
					<option value="BE">Belgium</option>
					<option value="BZ">Belize</option>
					<option value="BJ">Benin</option>
					<option value="BM">Bermuda</option>
					<option value="BT">Bhutan</option>
					<option value="BO">Bolivia, Plurinational State of</option>
					<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
					<option value="BA">Bosnia and Herzegovina</option>
					<option value="BW">Botswana</option>
					<option value="BV">Bouvet Island</option>
					<option value="BR">Brazil</option>
					<option value="IO">British Indian Ocean Territory</option>
					<option value="BN">Brunei Darussalam</option>
					<option value="BG">Bulgaria</option>
					<option value="BF">Burkina Faso</option>
					<option value="BI">Burundi</option>
					<option value="KH">Cambodia</option>
					<option value="CM">Cameroon</option>
					<option value="CA">Canada</option>
					<option value="CV">Cape Verde</option>
					<option value="KY">Cayman Islands</option>
					<option value="CF">Central African Republic</option>
					<option value="TD">Chad</option>
					<option value="CL">Chile</option>
					<option value="CN">China</option>
					<option value="CX">Christmas Island</option>
					<option value="CC">Cocos (Keeling) Islands</option>
					<option value="CO">Colombia</option>
					<option value="KM">Comoros</option>
					<option value="CG">Congo</option>
					<option value="CD">Congo, the Democratic Republic of the</option>
					<option value="CK">Cook Islands</option>
					<option value="CR">Costa Rica</option>
					<option value="CI">Côte d\'Ivoire</option>
					<option value="HR">Croatia</option>
					<option value="CU">Cuba</option>
					<option value="CW">Curaçao</option>
					<option value="CY">Cyprus</option>
					<option value="CZ">Czech Republic</option>
					<option value="DK">Denmark</option>
					<option value="DJ">Djibouti</option>
					<option value="DM">Dominica</option>
					<option value="DO">Dominican Republic</option>
					<option value="EC">Ecuador</option>
					<option value="EG">Egypt</option>
					<option value="SV">El Salvador</option>
					<option value="GQ">Equatorial Guinea</option>
					<option value="ER">Eritrea</option>
					<option value="EE">Estonia</option>
					<option value="ET">Ethiopia</option>
					<option value="FK">Falkland Islands (Malvinas)</option>
					<option value="FO">Faroe Islands</option>
					<option value="FJ">Fiji</option>
					<option value="FI">Finland</option>
					<option value="FR">France</option>
					<option value="GF">French Guiana</option>
					<option value="PF">French Polynesia</option>
					<option value="TF">French Southern Territories</option>
					<option value="GA">Gabon</option>
					<option value="GM">Gambia</option>
					<option value="GE">Georgia</option>
					<option value="DE">Germany</option>
					<option value="GH">Ghana</option>
					<option value="GI">Gibraltar</option>
					<option value="GR">Greece</option>
					<option value="GL">Greenland</option>
					<option value="GD">Grenada</option>
					<option value="GP">Guadeloupe</option>
					<option value="GU">Guam</option>
					<option value="GT">Guatemala</option>
					<option value="GG">Guernsey</option>
					<option value="GN">Guinea</option>
					<option value="GW">Guinea-Bissau</option>
					<option value="GY">Guyana</option>
					<option value="HT">Haiti</option>
					<option value="HM">Heard Island and McDonald Islands</option>
					<option value="VA">Holy See (Vatican City State)</option>
					<option value="HN">Honduras</option>
					<option value="HK">Hong Kong</option>
					<option value="HU">Hungary</option>
					<option value="IS">Iceland</option>
					<option value="IN">India</option>
					<option value="ID">Indonesia</option>
					<option value="IR">Iran, Islamic Republic of</option>
					<option value="IQ">Iraq</option>
					<option value="IE">Ireland</option>
					<option value="IM">Isle of Man</option>
					<option value="IL">Israel</option>
					<option value="IT">Italy</option>
					<option value="JM">Jamaica</option>
					<option value="JP">Japan</option>
					<option value="JE">Jersey</option>
					<option value="JO">Jordan</option>
					<option value="KZ">Kazakhstan</option>
					<option value="KE">Kenya</option>
					<option value="KI">Kiribati</option>
					<option value="KP">Korea, Democratic People\'s Republic of</option>
					<option value="KR">Korea, Republic of</option>
					<option value="KW">Kuwait</option>
					<option value="KG">Kyrgyzstan</option>
					<option value="LA">Lao People\'s Democratic Republic</option>
					<option value="LV">Latvia</option>
					<option value="LB">Lebanon</option>
					<option value="LS">Lesotho</option>
					<option value="LR">Liberia</option>
					<option value="LY">Libya</option>
					<option value="LI">Liechtenstein</option>
					<option value="LT">Lithuania</option>
					<option value="LU">Luxembourg</option>
					<option value="MO">Macao</option>
					<option value="MK">Macedonia, the former Yugoslav Republic of</option>
					<option value="MG">Madagascar</option>
					<option value="MW">Malawi</option>
					<option value="MY">Malaysia</option>
					<option value="MV">Maldives</option>
					<option value="ML">Mali</option>
					<option value="MT">Malta</option>
					<option value="MH">Marshall Islands</option>
					<option value="MQ">Martinique</option>
					<option value="MR">Mauritania</option>
					<option value="MU">Mauritius</option>
					<option value="YT">Mayotte</option>
					<option value="MX">Mexico</option>
					<option value="FM">Micronesia, Federated States of</option>
					<option value="MD">Moldova, Republic of</option>
					<option value="MC">Monaco</option>
					<option value="MN">Mongolia</option>
					<option value="ME">Montenegro</option>
					<option value="MS">Montserrat</option>
					<option value="MA">Morocco</option>
					<option value="MZ">Mozambique</option>
					<option value="MM">Myanmar</option>
					<option value="NA">Namibia</option>
					<option value="NR">Nauru</option>
					<option value="NP">Nepal</option>
					<option value="NL">Netherlands</option>
					<option value="NC">New Caledonia</option>
					<option value="NZ">New Zealand</option>
					<option value="NI">Nicaragua</option>
					<option value="NE">Niger</option>
					<option value="NG">Nigeria</option>
					<option value="NU">Niue</option>
					<option value="NF">Norfolk Island</option>
					<option value="MP">Northern Mariana Islands</option>
					<option value="NO">Norway</option>
					<option value="OM">Oman</option>
					<option value="PK">Pakistan</option>
					<option value="PW">Palau</option>
					<option value="PS">Palestinian Territory, Occupied</option>
					<option value="PA">Panama</option>
					<option value="PG">Papua New Guinea</option>
					<option value="PY">Paraguay</option>
					<option value="PE">Peru</option>
					<option value="PH">Philippines</option>
					<option value="PN">Pitcairn</option>
					<option value="PL">Poland</option>
					<option value="PT">Portugal</option>
					<option value="PR">Puerto Rico</option>
					<option value="QA">Qatar</option>
					<option value="RE">Réunion</option>
					<option value="RO">Romania</option>
					<option value="RU">Russian Federation</option>
					<option value="RW">Rwanda</option>
					<option value="BL">Saint Barthélemy</option>
					<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
					<option value="KN">Saint Kitts and Nevis</option>
					<option value="LC">Saint Lucia</option>
					<option value="MF">Saint Martin (French part)</option>
					<option value="PM">Saint Pierre and Miquelon</option>
					<option value="VC">Saint Vincent and the Grenadines</option>
					<option value="WS">Samoa</option>
					<option value="SM">San Marino</option>
					<option value="ST">Sao Tome and Principe</option>
					<option value="SA">Saudi Arabia</option>
					<option value="SN">Senegal</option>
					<option value="RS">Serbia</option>
					<option value="SC">Seychelles</option>
					<option value="SL">Sierra Leone</option>
					<option value="SG">Singapore</option>
					<option value="SX">Sint Maarten (Dutch part)</option>
					<option value="SK">Slovakia</option>
					<option value="SI">Slovenia</option>
					<option value="SB">Solomon Islands</option>
					<option value="SO">Somalia</option>
					<option value="ZA">South Africa</option>
					<option value="GS">South Georgia and the South Sandwich Islands</option>
					<option value="SS">South Sudan</option>
					<option value="ES">Spain</option>
					<option value="LK">Sri Lanka</option>
					<option value="SD">Sudan</option>
					<option value="SR">Suriname</option>
					<option value="SJ">Svalbard and Jan Mayen</option>
					<option value="SZ">Swaziland</option>
					<option value="SE">Sweden</option>
					<option value="CH">Switzerland</option>
					<option value="SY">Syrian Arab Republic</option>
					<option value="TW">Taiwan, Province of China</option>
					<option value="TJ">Tajikistan</option>
					<option value="TZ">Tanzania, United Republic of</option>
					<option value="TH">Thailand</option>
					<option value="TL">Timor-Leste</option>
					<option value="TG">Togo</option>
					<option value="TK">Tokelau</option>
					<option value="TO">Tonga</option>
					<option value="TT">Trinidad and Tobago</option>
					<option value="TN">Tunisia</option>
					<option value="TR">Turkey</option>
					<option value="TM">Turkmenistan</option>
					<option value="TC">Turks and Caicos Islands</option>
					<option value="TV">Tuvalu</option>
					<option value="UG">Uganda</option>
					<option value="UA">Ukraine</option>
					<option value="AE">United Arab Emirates</option>
					<option value="GB">United Kingdom</option>
					<option value="US">United States</option>
					<option value="UM">United States Minor Outlying Islands</option>
					<option value="UY">Uruguay</option>
					<option value="UZ">Uzbekistan</option>
					<option value="VU">Vanuatu</option>
					<option value="VE">Venezuela, Bolivarian Republic of</option>
					<option value="VN">Viet Nam</option>
					<option value="VG">Virgin Islands, British</option>
					<option value="VI">Virgin Islands, U.S.</option>
					<option value="WF">Wallis and Futuna</option>
					<option value="EH">Western Sahara</option>
					<option value="YE">Yemen</option>
					<option value="ZM">Zambia</option>
					<option value="ZW">Zimbabwe</option>
				</select>';
	}
}