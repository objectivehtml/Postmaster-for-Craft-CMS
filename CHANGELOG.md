## Postmaster

### Changelog

#### 0.4.0
##### 10/14/2014

- (Feature) Added Campaign Monitor service
- (Feature) Added new Notifications API
- (Feature) Added new Schedule API to Notifications and Parcels
- (API) Added new API to save template contents and view it via url. Campaign Monitor for example needs templates passed a url, so this Api is there to serve CM and similar requests
- (API) Added the new Postmaster_NotificationsService
- (API) added getSendDate() and setSendDate() methods to the TransportInterface
- (API) Added new onBeforeSend and onAfterSend methods to all the plugin API's for more advanced manipulation
- (API) Refactored plugin parsing as an inherited method to make life easy and simple
- (API) Updated PostmasterService with new methods to get registered notification types, notification schedules, and parcel schedules
- (API) Added new craft()->postmaster_notifications service with paralleled functionality to craft()->postmaster_parcels
- (API) Added craft.postmaster.notifications variables
- (API) Added craft.postmaster.notificationSchedules variables
- (API) Added craft.postmaster.notificationSchedules variables
- (API) Added craft.postmaster.parcelSchedules variables
- (API) Ensured getSuccess and setSuccess actually return boolean values for the Postmaster_TransportResponseModel
- (Bug Fix) Fixed an issue where a Postmaster_TransportModel was sent to the queue but still creating a sent response record in the db even though it hadn't actually sent
- (Bug Fix) Fixed an issue with the UserEmailParcelType not parcel the parcel settings using the new API changes in the last release
- (Bug Fix) Fixed typos and issues with tabs and breadcrumbs
- (Bug Fix) Removed unused legacy core files from early stages of dev. The logic in these files were just basically refactored into other files.

#### 0.3.1
##### 10/12/2014

- (Feature) Added ability to send parcels in the future by adding items to the queue
- (API) Added the new Queue Service to handle sending items to the queue
- (API) Added ability to register site url's with Postmaster plugins
- (API) Added new TransportInterface to the Postmaster_TransportModel with the getSendDate() and shouldSend() methods
- (API) Refactored settings models so that even parcel settings are parsed dynamically
- (API) Refactored Postmaster_ServiceSettingsModel to be a bare bones extendible model without any logic. (Logic moved to the Postmaster_BaseSettingsModel)
- (API) Added sendToQueue() method to PostmasterService class

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