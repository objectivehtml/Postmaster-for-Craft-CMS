## Postmaster

### Changelog

#### 0.3.0
##### 10/11/2014

- (Feature) Added settings to Test Email Service that allow users to send successful and failed responses
- (Feature) Added new craft()->postmaster->transportResponses()
- (Feature) Added craft.postmaster.transportResponse() to the template variables
- (API) Added new Postmaster_TransportResponseService
- (API) Added new Postmaster_TransportResponseCriteria
- (API) Updated all service classes to use the new Postmaster_TransportResponseModel instead of the Craft\Plugins\Postmaster\Responses\TransportResponse class
- (API) Added the getSettingsModelClassName() classes to the SettingsInterface
- (API) Added success() and failed() method to the BaseService class
- (API) Added new helper methods to the BaseService class to make returning responses easier
- (Bug Fix) Fixed issue if a plugin was instantiated with settings and the settings property wasn't an instance of the settings model
- (Bug Fix) Cleanup code comments
- (Bug Fix) Added new migration to fix issues with classes without the __class__ property

#### 0.2.0
##### 10/11/2014

- (Feature) Added the new HttpRequest Service which allows users to send GET, POST, PUT, and DELETE requests to a specific URL, along with the headers and payload data
- (Bug Fix) Removed Ping Service from repo and replaced with new HttpRequest Service
- (Bug Fix) Removed Ping Service from existing parcels to prevent PHP errors

#### 0.1.2
##### 10/11/2014

- (Feature) Added Twilio SMS service

#### 0.1.1 
##### 10/10/2014

- (Bug Fix) Fixed more stability issues
- (Bug Fix) Fixed a bunch of errors that were being triggered when devMode was set to false
- (Bug Fix) Fixed issue with bugs appearing in the template

#### 0.1.0 
##### 10/10/2014

- Initial Beta Release