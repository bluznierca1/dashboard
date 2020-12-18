# dashboard project

## Worh to know
Project is not finished and most probably won't be.
There are many things that could be improved (f.ex. Logger for handling errors).
The simplest possible design.
Chart gets loaded on initial load and gets reloaded by AJAX when datepickers are submitted.
README.md is veeeery general :)
- - -

## Controllers

#### Controller
Main controller holding functions that need to be available in child classes.
It also handles 'refillDb' -> that is to remove all existing records and replace them with new random data (more in Mocker part).

#### DashboardController
Taking case of preparing data for chart and rendering View displaying it.

#### AjaxController
Handling AJAX calls (POST only for now). Delegating proper methods depended on action provided in POST request. If action does not match method, just return empty JSON.

- - -

## Traits

#### ControllerTrait
Contains all common functions that might be used by some of the controllers (future ones atm) but are not needed in every single one.
For now it is mainly focused on building data for chart which is similar for both DashboardController and AjaxController.

#### EntityTrait
Contains mostly functions helping to build SQL queries based on Entity attributes.

- - -

## Mocker
To have any kind of sample data, Mocker was needed.
It takes care of erasing all records from DB and filling it with custome data.
Plenty of randomness to have different data on every refill.
###### Triggered by {URL}/fillDb/refill
Users are pulled from external srv but countries/devices etc. are just random arrays.
- - -

## Request
Request handles the request... :D
Check if given route is registered (in routes.php), extract not needed prefixes and trigger controller which is supposed to render view.
Also, set all REQUEST_URI data to class attributes as private properties and send instance of Request to controller so it can use it by getters.
Setters are not set as there was not need for that (setting values to class attrs was done straight away and controller does not need to update original data for now).

- - -

## Views
Simple class building path based on data from controller, fetching file, extracting $data array and returning string (output buffering) to controller which will echo it.
- - -

## Router
Created to register routes we want to have available. If user provides one that is not registered, home page will be returned.
Registering routes takes place in routes.php
- - -

## Config files
##### config.php
autoloader function + some of constants

### config.ini 
Contains DB credentials.

### .htaccess
Simple redirect all requests to index.php (except JS and CSS files)
that one because of Apache (live would be most probably Nginx)

##### All of these files are included in GIT because it is fully transparent project and it does not go live.

### queries_for_db.txt
All queries used to build DB. DOES NOT include queries used to pull data from DB.

# Example photos from project (since it was running on localhost)

## Initial load (before refilling DB):
![Alt text](includes/imgs/initial_before_db_refill.png?raw=true "Title")

## After AJAX request (changing dates)
![Alt text](includes/imgs/ajax_update.png?raw=true "Title")

## After 2nd AJAX request (changing dates)
![Alt text](includes/imgs/ajax_update_2.png?raw=true "Title")

## Refilling DB
![Alt text](includes/imgs/db_refill.png?raw=true "Title")

### Initial load after DB refill:
![Alt text](includes/imgs/initial_after_db_refill.png?raw=true "Title")




