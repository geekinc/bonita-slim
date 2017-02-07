*** Settings ***
Documentation     A resource file with reusable keywords and variables.
...
...               The system specific keywords created here form our own
...               domain specific language. They utilize keywords provided
...               by the imported Selenium2Library.

Library	Selenium2Library
Library	HttpLibrary.HTTP

*** Variables ***
${REMOTE}	"false"
${SERVER}	192.168.33.200
${BROWSER}	firefox
${DELAY}	0
${VALID USER}	admin
${VALID PASSWORD}	password
${FF PROFILE DIR}	${CURDIR}${/}..${/}ff_profile
${LOGIN URL}	http://${VALID USER}:${VALID PASSWORD}@${SERVER}/admin
${WELCOME URL}	http://${SERVER}/admin
${ERROR URL}	http://${SERVER}/error.html
${REMOTE_URL}	http://geekinc:b85798f1-58f3-4ea6-9d5e-b4a36d374723@ondemand.saucelabs.com:80/wd/hub
${DESIRED_CAPS}	Selenium::WebDriver::Remote::Capabilities.firefox,selenium-version:2.18.0,version:5,platform:XP,name:RobotFramework Regression Tests
${BROWSER_OPTIONS}	ff_profile_dir=${FF PROFILE DIR}

*** Keywords ***
Open Browser To Login Page
	Run Keyword If	${REMOTE} == "true"	Open Browser	${LOGIN URL}	${BROWSER}	${BROWSER_OPTIONS}	remote_url=${REMOTE_URL}	desired_capabilities=${DESIRED_CAPS}
	Run Keyword If	${REMOTE} != "true"	Open Browser	${LOGIN URL}	${BROWSER}	${BROWSER_OPTIONS}
    Maximize Browser Window
    Set Selenium Speed	${DELAY}
    Login Page Should Be Open

Login Page Should Be Open
    Title Should Be	COLONIAL

Welcome Page Should Be Open
    Location Should Be	${WELCOME URL}