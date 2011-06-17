; <?php exit; ?> DO NOT REMOVE THIS LINE
; If you want to change some of these default values, the best practise is to override 
; them in your configuration file in config/config.ini.php. If you directly edit this file,
; you risk losing your changes when you upgrade Piwik. 
; For example if you want to override action_title_category_delimiter, 
; edit config/config.ini.php and add the following:
; [General]
; action_title_category_delimiter = "-"

;--------
; WARNING - YOU SHOULD NOT EDIT THIS FILE DIRECTLY - Edit config.ini.php instead.
;--------

[superuser]
login			= root
password		= 

[database]
host			= 
username		= 
password		= 
dbname			= 
tables_prefix	= 
port			= 3306
adapter			= PDO_MYSQL
; if charset is set to utf8, Piwik will ensure that it is storing its data using UTF8 charset.
; it will add a sql query SET at each page view.
; Piwik should work correctly without this setting.  
;charset		= utf8

[database_tests]
host 			= localhost
username 		= root
password 		= 
dbname			= core_tests
tables_prefix	= coretests_
port			= 3306
adapter 		= PDO_MYSQL
 
[Debug]
; if set to 1, the archiving process will always be triggered, even if the archive has already been computed
; this is useful when making changes to the archiving code so we can force the archiving process
always_archive_data_period = 0;
always_archive_data_day = 0;

; if set to 1, all the SQL queries will be recorded by the profiler 
; and a profiling summary will be printed at the end of the request
enable_sql_profiler = 0

; if set to 1, javascript and css files will be included individually
; this option must be set to 1 when adding, removing or modifying javascript and css files
disable_merged_assets = 0

[General]
; character used to automatically create categories in the Actions > Pages, Outlinks and Downloads reports
; for example a URL like "example.com/blog/development/first-post" will create 
; the page first-post in the subcategory development which belongs to the blog category
action_url_category_delimiter = /

; similar to above, but this delimiter is only used for page titles in the Actions > Page titles report
action_title_category_delimiter = /

; minimum number of websites to run autocompleter
autocomplete_min_sites = 5

; maximum number of websites showed in search results in autocompleter
site_selector_max_sites = 10

; this action name is used when the URL ends with a slash / 
; it is useful to have an actual string to write in the UI
action_default_name = index

; this action name is used when the URL has no page title or page URL defined
action_default_name_when_not_defined = "page title not defined"
action_default_url_when_not_defined = "page url not defined"

; if you want all your users to use Piwik in only one language, disable the LanguagesManager
; plugin, and set this default_language (users won't see the language drop down) 
default_language = en

; default number of elements in the datatable
datatable_default_limit = 10

; default number of rows returned in API responses
API_datatable_default_limit = 50

; This setting is overriden in the UI, under "General Settings". This is the default value used if the setting hasn't been overriden via the UI.
; Time in seconds after which an archive will be computed again. This setting is used only for today's statistics.
; Defaults to 10 seconds so that by default, Piwik provides real time reporting.
time_before_today_archive_considered_outdated = 10

; This setting is overriden in the UI, under "General Settings". The default value is to allow browsers
; to trigger the Piwik archiving process.
enable_browser_archiving_triggering = 1

; Display items that need to be connected to the internet for resources
internet_is_connected = 1;

; Display map data. google openstreetmap
map_data = google;

; PHP minimum required version (minimum requirement known to date = ->newInstanceArgs)
minimum_php_version = 5.1.3

; MySQL minimum required version
; note: timezone support added in 4.1.3
minimum_mysql_version = 4.1

; PostgreSQL minimum required version
minimum_pgsql_version = 8.3

; Minimum adviced memory limit in php.ini file (see memory_limit value)
minimum_memory_limit = 256

; by default, Piwik uses relative URLs, so you can login using http:// or https://
; (the latter assumes you have a valid SSL certificate).
; If set to 1, Piwik redirects the login form to use a secure connection (i.e., https).
force_ssl_login = 1

; login cookie name
login_cookie_name = core_auth

; login cookie expiration (14 days)
login_cookie_expire = 1209600

; The path on the server in which the cookie will be available on. 
; Defaults to empty. See spec in http://curl.haxx.se/rfc/cookie_spec.html
login_cookie_path = 

; email address that appears as a Sender in the password recovery email
; if specified, {DOMAIN} will be replaced by the current Piwik domain
login_password_recovery_email_address = "password-recovery@{DOMAIN}"
; name that appears as a Sender in the password recovery email
login_password_recovery_email_name = iBike

; Set to 1 to disable the framebuster (a click-jacking countermeasure).
; Default is 0 (i.e., bust frames on the Login forms).
enable_framed_logins = 0

; language cookie name for session
language_cookie_name = core_lang

; standard email address displayed when sending emails
noreply_email_address = "noreply@{DOMAIN}"

; feedback email address;
; when testing, use your own email address or "nobody"
feedback_email_address = "hello@core.org"

; during archiving, Piwik will limit the number of results recorded, for performance reasons
; maximum number of rows for any of the Referers tables (keywords, search engines, campaigns, etc.)
datatable_archiving_maximum_rows_referers = 1000
; maximum number of rows for any of the Referers subtable (search engines by keyword, keyword by campaign, etc.)
datatable_archiving_maximum_rows_subtable_referers = 50

; maximum number of rows for any of the Actions tables (pages, downloads, outlinks)
datatable_archiving_maximum_rows_actions = 500
; maximum number of rows for pages in categories (sub pages, when clicking on the + for a page category)
; note: should not exceed the display limit in Piwik_Actions_Controller::ACTIONS_REPORT_ROWS_DISPLAY
;       because each subdirectory doesn't have paging at the bottom, so all data should be displayed if possible.
datatable_archiving_maximum_rows_subtable_actions = 100

; maximum number of rows for other tables (Providers, User settings configurations)
datatable_archiving_maximum_rows_standard = 500

; by default, Piwik uses self-hosted AJAX libraries.
; If set to 1, Piwik uses a Content Distribution Network
use_ajax_cdn = 0

; required AJAX library versions
jquery_version = 1.4.2
jqueryui_version = 1.8.4
swfobject_version = 2.2

; If set to 1, Piwik adds a response header to workaround the IE+Flash+HTTPS bug.
reverse_proxy = 0

; List of proxy headers for client IP addresses
;
; CloudFlare (CF-Connecting-IP)
;proxy_client_headers[] = HTTP_CF_CONNECTING_IP
;
; ISP proxy (Client-IP)
;proxy_client_headers[] = HTTP_CLIENT_IP
;
; de facto standard (X-Forwarded-For)
;proxy_client_headers[] = HTTP_X_FORWARDED_FOR

; List of proxy headers for host IP addresses
;
; de facto standard (X-Forwarded-Host)
;proxy_host_headers[] = HTTP_X_FORWARDED_HOST

; The release server is an essential part of the Piwik infrastructure/ecosystem
; to provide the latest software version.
latest_version_url = http://core.org/latest.zip

; The API server is an essential part of the Piwik infrastructure/ecosystem to
; provide services to Piwik installations, e.g., getLatestVersion and
; subscribeNewsletter.
api_service_url = http://api.core.org

[mail]
transport =							; smtp (using the configuration below) or empty (using built-in mail() function)
port =								; optional; defaults to 25 when security is none or tls; 465 for ssl
host =								; SMTP server address 
type =								; SMTP Auth type. By default: NONE. For example: LOGIN 
username =							; SMTP username
password =							; SMTP password 
encryption =						; SMTP transport-layer encryption, either 'ssl', 'tls', or empty (i.e., none).

[log]
;possible values for log: screen, database, file
; by default, standard logging/debug messages are hidden from screen
;logger_message[]		= screen
logger_error[]			= screen
logger_exception[]		= screen

; if configured to log in files, log files will be created in this path
; eg. if the value is tmp/logs files will be created in /path/to/core/tmp/logs/
logger_file_path		= tmp/logs

; all calls to the API (method name, parameters, execution time, caller IP, etc.)
; disabled by default as it can cause serious overhead and should only be used wisely
;logger_api_call[]		= file

[smarty]
; the list of directories in which to look for templates
template_dir[]	= plugins
template_dir[]	= themes/default
template_dir[]	= themes

plugins_dir[]	= core/SmartyPlugins
plugins_dir[] 	= libs/Smarty/plugins

compile_dir		= tmp/templates_c
cache_dir		= tmp/cache

; error reporting inside Smarty
error_reporting = E_ALL|E_NOTICE

[Plugins]
Plugins[] 		= CorePluginsAdmin
Plugins[] 		= CoreAdminHome
Plugins[] 		= CoreHome
Plugins[] 		= Proxy
Plugins[] 		= API
Plugins[] 		= Widgetize
Plugins[] 		= LanguagesManager
Plugins[] 		= Actions
Plugins[] 		= Dashboard
Plugins[] 		= MultiSites
Plugins[] 		= Referers
Plugins[] 		= UserSettings
Plugins[]		= Goals
Plugins[]		= SEO

Plugins[] 		= UserCountry
Plugins[] 		= VisitsSummary
Plugins[] 		= VisitFrequency
Plugins[] 		= VisitTime
Plugins[] 		= VisitorInterest
Plugins[] 		= ExampleAPI
Plugins[] 		= ExamplePlugin
Plugins[]		= ExampleRssWidget
Plugins[] 		= ExampleFeedburner
Plugins[] 		= Provider
Plugins[]		= Feedback

Plugins[] 		= Login
Plugins[] 		= UsersManager
Plugins[] 		= SitesManager
Plugins[] 		= Installation
Plugins[] 		= CoreUpdater
Plugins[]		= PDFReports
Plugins[] 		= UserCountryMap
Plugins[] 		= Live

[PluginsInstalled]
PluginsInstalled[] = Login
PluginsInstalled[] = CoreAdminHome
PluginsInstalled[] = UsersManager
PluginsInstalled[] = SitesManager
PluginsInstalled[] = Installation

[Plugins_Tracker]
Plugins_Tracker[] = Provider
Plugins_Tracker[] = Goals

