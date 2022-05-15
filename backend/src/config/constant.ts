// export  export const API_URL = "http://192.168.10.70/node_l/sivr-gsl/php_data_provider/vivr_data_response.php";
export  const API_URL = "http://localhost:8080/vivr/php_data_provider/vivr_data_response.php";

export const VIVR_TOKEN_ISSUER = "vivr.web" ;
 export const GET_USER_TYPE_API =  "SXML:CBS-TYPE:CLI=<CLI>";
 export const GET_USER_BY_PHONE_API =  "HTTP:MWCUDEBP:CLI=<CLI>,CALLID=<CALLID>";
 export const CALL_EXTERNAL_API =  "external_api_call"; 
 export const LOG_LOCATION = '/var/www/html/mtbsivr/sivr7.1';
 export const PIN_GENERATE_FUNCTION =  "generatePIN";
 export const SEND_SMS_FUNCTION =  "SendSMS";
 export const GET_USER_FUNCTION =  "checkUser";
 export const GET_DEFAULT_ACTION_FUNCTION =  "getDefaultAction";
 export const GET_USER_FROM_AUTH_CODE_FUNCTION =  "getVIVRData";
 export const GET_LOGIN_GENERATE_CODE = 'getIVRGeneratedLink';
 export const VIVR_LOG_FUNCTION =  "vivrLog";
 export const STORE_CUSTOMER_JOURNEY_FUNCTION =  "logCustomerJourney";
 export const GET_FUNCTION_NAME_FROM_ACTION =  "getFunctionOfAction";
 export const GET_DEFAULT_PAGE_ID =  "getDefaultPageID";
 export const GET_PAGE_ID_FROM_BUTTON =  "getPageIdFromButton";
 export const GET_PAGE_DATA_FROM_PAGE_ID =  "getPageFromPageId";
 export const GET_PAGE_ELEMENTS_FROM_PAGE_ID =  "getPageElementsFromPageId";
 export const EXTERNAL_API_CALL =  "external_api_call";
 export const GET_COMPARING_DATA =  "getComparingData";
 export const GET_API_KEY_DATA =  "getElementsApiKeyData";
 export const GET_API_CALCULATION_DATA =  "getElementCalculationData";
 export const SAVE_CUSTOMER_FEEDBACK =  "iceFeedback";
 export const UPDATE_VIVR_LOG =  "updateVivrLog";
 export const LOG_VIVR_JOURNEY =  "logVivrJourney";
 export const GET_BULLETIN_MSG =  "getBulletinMessage";
 export const GET_DYNAMIC_PAGE_DATA =  "getDynamicPageData";
 export const SET_LOGOUT_TYPE =  "setLogoutType";

 export const PIN_SMS_TEXT =  "Your Smart IVR Login PIN is : ";
 export const USER_TYPE_KEY =  "bcsPaymentMode";

 export const VIVR_MODULE_TYPE =  "VI";
 export const VIVR_ONLY_MODULE_SUBTYPE =  "IO";
 export const WEB_SOURCE =  "W";
 export const IVR_SOURCE =  "I";

 export const PREPAID =  "prepaid";
 export const POSTPAID =  "postpaid";
 export const PREPAID_IVR =  "AB";
 export const POSTPAID_IVR =  "AC";

 export const IVR_ID = "AU";

 export const SESSION_PREFIX_LENGTH =  6;
 export const LOGIN_DURATION =  3600;


 export const ENGLISH =  "EN";
 export const BENGALI =  "BN";
 export const SOUND_ON =  "on";
 export const SOUND_OFF =  "off";
 export const AUDIO_FILE_PATH =  "audio/";
 export const GREETINGS_AUDIO_EN =  "1_Welcome_to_City_Bank_EN.wav";
 export const GREETINGS_AUDIO_BN =  "1_Welcome_to_City_Bank_BN.wav";

 export const DEFAULT_ACTION =  "nav";
 export const HOME_BUTTON_VALUE =  "h";
 export const PREVIOUS_BUTTON_VALUE =  "p";
 export const HOME_PAGE =  "main_page";
 export const PREVIOUS_PAGE =  "previous_page";


 export const ELEMENT_TYPE_PARAGRAPH =  "paragraph";
 export const ELEMENT_TYPE_BUTTON =  "button";
 export const ELEMENT_TYPE_TABLE =  "table";
 export const ELEMENT_TYPE_HYPERLINK =  "a";
 export const ELEMENT_TYPE_COMPARE_API =  "compareApi";
 export const ELEMENT_TYPE_INPUT =  "input";
 export const DROPDOWN_TYPE_INPUT =  "select";
 export const VISIBLE =  "Y";
 export const NOT_VISIBLE =  "N";

 export const DYNAMIC_TEXT =  "##";

 export const ENGLISH_WEB_KEY =  "web_en";
 export const BENGALI_WEB_KEY =  "web_bn";

 export const STATIC_TABLE_KEY =  "static";
 export const DYNAMIC_TABLE_KEY =  "key";
 export const DYNAMIC_TABLE_VERTICAL = 'daynamicvertical';
 export const STATIC_HORIZONTAL_TYPE = 'SHT';
 export const DYNAMIC_HORIZONTAL_TYPE = 'DHT';
 export const DYNAMIC_VERTICAL_TYPE = 'DVT';

 export const TABLE_HEADING =  "table_heading";
 export const BENGALI_TABLE_HEADING =  "heading_bn";
 export const ENGLISH_TABLE_HEADING =  "heading_en";
 export const BENGALI_TABLE_ROW =  "table_row_data_bn";
 export const ENGLISH_TABLE_ROW =  "table_row_data_en";
 export const TABLE_DATA_KEY =  "responseData";

 export const CUSTOMER_FEEDBACK_YES =  "Y";
 export const CUSTOMER_FEEDBACK_NO =  "N";
 export const GET_NAVIGATION_PAGE =  "getNavigationPage";

 export const YES =  "Y";
 export const NO =  "N";

 export const TASK_NAVIGATION =  "nav";
 export const TASK_COMPARE =  "comp";

 export const COMPARISON_EQUAL =  "=";
 export const COMPARISON_NOT_EQUAL =  "!=";
 export const COMPARISON_GREATER_THAN =  ">";
 export const COMPARISON_GREATER_THAN_EQUAL =  ">=";
 export const COMPARISON_LESS_THAN =  "<";
 export const COMPARISON_LESS_THAN_EQUAL =  "<=";

 export const SET_VALUE =  "SV";
 export const SEND_OTP =  "SO";
 export const CHECK_OTP =  "CO";
 export const SET_VALUE_COMPARISON_EQUAL =  "SVE";

 export const ADDITION =  "+";
 export const SUBTRACTION =  "-";
 export const MULTIPLICATION =  "*";
 export const DIVISION =  "/";

 export const DEFAULT_PAGE =  "1600152195";
 export const DEFAULT_PAGE_CONVENTIONAL =  "1631696583";
 export const DEFAULT_PAGE_ISLAMIC =  "1631422692";
 export const IVR_TYPE_ISLAMIC = 'AC';
 export const IVR_TYPE_CONVENTIONAL = 'AB';

 export const TYPE_HOME_MENU = 'HM';
 export const TYPE_SUB_MENU =  'SM';

 export const LAST_REQUESTED_PAGE =  "lastRequestedPage";
 export const REQUEST_COUNT =  "requestCount";
 export const MAX_REQUEST_COUNT =  "3";
 export const INPUT_ERROR_PAGE =  "ierr";

 export const CLI_OTP_LIMIT_24_HOUR =  "10";
 export const CLI_OTP_LIMIT_90_SECOND = "1";
 export const IP_OTP_LIMIT_24_HOUR =  "250";
 export const IP_OTP_LIMIT_90_SECOND =  "5";
 export const SYSTEM_REQUEST_LIMIT = '50000';
 export const SYSTEM_REQUEST_LIMIT_FUNCTION = 'systemRequetLimit';
 export const THROTTLE_FUNCTION = 'throttleFunction';