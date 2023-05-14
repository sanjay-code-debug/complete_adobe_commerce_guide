# Complete_Adobe_Commerce_Guide
complete adobe commerce(magento guide)



## Before Go into Magento 

    - Php 
    - Html
    - Css
    - Javascript
    - Knockout JS
    - Xml
    - Jquery
    - 

     
### Best Practices
<details><summary><b>Always Remind Before Code</b></summary>
               
                 - Before Code - Understood what is the Requirement
                 - Some Time Directly Try - If you are not gettting 
                 - Always Try with Dummy Value then Implement Real
                 - Implement Oops Concept
                            |
                            |--- Divide code into small methods 
                            |--- Write Clean Code (Proper Naming )
                            |--- 
                 -  
    
    
 </details>      
    
<details><summary><b>Try Catch</b></summary>
    
             try { echo 'Perferom your Operation'; } catch(Exception $e) { echo 'Message: ' .$e->getMessage(); }
               try {
                   //check if
                     var $test = "TEST";
                     if(($test) === "TEST") {
                     //throw exception if condition is not valid
                     throw new customException($test);
                 }
                  //check for "TEST" in dummy value
                   if(strpos($test, "TEST") !== FALSE) 
                   {
                    throw new Exception("$test is an example Dummy Value");
                  }
                 }
                 catch (customException $e) {
                    echo $e->errorMessage();
                    }
                 catch(Exception $e) {
                  echo $e->getMessage();
                }  
  </details>
  
  <details><summary><b>Print Logs</b></summary>
  
                      $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/mylogfile.log');
                      $logger = new \Zend\Log\Logger();
                      $logger->addWriter($writer);
                      $logger->info('This Is Simple Text Log'); //To print simple text log
                      $logger->info(print_r($myarray, true)); //To print array log  
 
 </details>  
 

## History o    f Magento(Adobe Commerce)

## Tools Require 
       - Nginx
       - Apache
       - Php
       - Elasticsearch
       - OpenSearch
       -  Redish
       - Varnish
       - RabbitMQ
       - Xdebug
       

## Setup and Installation 
       


## Basic Commands and use

      - Production Vs Developer Vs Default Mode
      - Two_FactorAuthentication
      - bin/magento deploy:mode:set production     - set the mode
      - bin/magento se:up 
      - bin/magento se:di:co
      - bin/magento ca:fl
      - bin/magento module:status
      - bin/magento module:enable Modeule
      - bin/magento module:disable Modeule
      - bin/magento dev:di:info "Magento\Catalog\Ui\DataProvider\Product\Listing\DataProvider"
      


## Common Issue or Error  for Magento Developer  as Beginner



## What all File and Why 



## How Magento Taking Memory and How it is Working on Local


## How Magento Code Giving Output 



## What is the need of Console Interface to Operate Magento codes(Terminal)




## MVC in Magento 



## Storefont in Magento 


## Admin Panel in Magento 



## Architecture Level Concept in Magento






## Basic Working of Magento(Adobe Commerce Application)




## How Files are working





##Core Concept In Magento
=========================
# API
![api](https://user-images.githubusercontent.com/78407424/229434266-befbda7b-674b-4156-9a62-75c8a9d37319.png)

Rest Api in Magento(Service contract & webapi.xml)
--------------------------------------------------
 - http://magento.local(base_url)/swagger#/
 
                |
                |------First check the active mode website is running
                |                                              |-----------sudo bin/magento deploy:mode:show
                |----- If It is in default mode then chane it to "developer"
                |                                                     |--------- sudo bin/magento deploy:mode:set developer (do se:up & ca:fl)
                |
                |----Some time it will not work So Check Below Configuration
                              |
                              |--------------Stores > Settings > Configuration > Advanced > Developer
                                                                                              |-------Swagger----->Enabled Yes/No (make it Yes)
      

  Customer
  --------
       Login & Create
       --------------
             - Register
             - Login
             - Forgot Password
             - Social Login
      Customer Dashboard
      ------------------
             - Customer Details
             - Customer Cart 
             - Customer Orders
             - Customer Whislist
             - Shipping Address
             - Billing Address
             - Customer Logout
             
             
             
  Guest Customer Flow 
  -------------------

  LoggedIn Customer Flow
  ----------------------
  
  PLP(Product Listing Page)
  -------------------------

  PDP(Product Description Page)
  -----------------------------
  
  Checkout
  --------
  
  Cart
  ----
  




## Service Contract Both Way


          Basic Way
          ---------
          
          
          
          Repository Way
          --------------
                    
          
          
# GraphQl
--------          
          
          

   

## Third Party Integration






## Basic Magento Syllabus

## Advance Magento Syllabus

## Cloud Envirnmonet in Magento


## Deprecated Code in Magento 



# Task 
    - Basic Understanding of Magento Assignment Part - 1

    - Basic Understanding of Magento Assignment Part - 2
    
    - Basic Understanding of Magento Assignment Part - 3 
    
    - Basic Understanding of Magento Assignment Part - 4

    - Basic Understanding of Magento Assignment Part - 5 


## Existing Free Extension For Magento 

## Bug in Magento 


## Publish your Custom Extension 


## Third Party Integration with Magento (Adobe Commerce)



## Latest Update on Code



## Latest Release 



            
# Core Concept With Own Way

## Remind 

<details>
      <b> <br/>
        
       - Always check with manual value  (set  dummy value for testing purpose no wait for actual value will come and you can test)
       - Check Cache Due to Cache - not getting proper output so try with --- InCognito Mode
       - Check with Network section
       - Check with Console section
       - When checking code -- If Required then check the related table for that code (try to think out of the box)
       - No need to wait anyone may help
       - If You wrote any single file in Magento How it Executed 
                                                 |
                                                 |-----what are all possible way to to get error(bootstrap.php(ini_set('display_errors', 1))
                                                 |
                                                 |-----Check the Modes ----
                                                                         |------Default     -- 
                                                                         |------Production  -- No Error will show to user 
                                                                         |------Developer   --- Error will show to user and log into var/log file
                                                                         
     - What we are trying to Get Output  in Any Location is it the correct Place to Print/Show(Like - Session ID -- Not able to print in Terminal)
     
     
     - What was the Basic Purpose of the Task
                                          |------Clear Understanding on the Task ---- What need to Do
                                          |------What Was the Perfect Solution
          
     - Must Have Basic Idea 
                        |----how any application is working
                        |----how it giving response to browser
                        |----session
                        |----cookie   -----------what actually it is working not the theory(Real Implement)
          
     </b>         
</details>                      


## Magento Codding Standard

<details>
  
                     - https://developer.adobe.com/commerce/php/coding-standards/
                     -  Write Code as Per Requirement
                                           |------Create Interface
                                           |------Create Classes 
                                           |------Create Method 
                                                           |-------One task for one Method
                                                           |-------Use Try Catch 
                                                           |-------Avoid use of For Loops
                                                           |-------Give Proper Comments to Code
                                                           |-------Make Clean and Optimize Code
                                                           |-------Check Error through Logs
                                                           |-------Write Test Cases for you Code
                                                           |-------Check all Latest Code Release
                     - PhpCS
                     - PhpMd
                     - Xdebug           
                     
          - ./vendor/bin/phpcs --standard=./dev/tests/static/framework/Magento/ruleset.xml app/code/Casio/....

 </details>
 
  <details>
  
    - Think Before Start the Task   (Best Way For Implementation)  - Think Various Way 
    - Xdebug
    - PhpCs
    - PhpMd
    - Unit Testing
    - MailHog / MailChip
    - Comporize the Module(Available by require composer)
    - Log the Error
    - Create Own Functionality For Implement the Concept
    - Try Catch  - Catching the Exception
    - Have Proper and MeaningFull Name (Class, Method and Variables)
                                      |----Access Specifier (When to declare - Public , Private )
                                      |----One Method will Perform one Task
                                      |- 
    - Have Pro Skill with Research Level - Causes - Why and How
          |- Learn Implementation of Message Queue 
          |- Best Use of GraphQl
          |- Best Use of Service Contract
          |- Best Use of Rest Api
          |- Complete working of Cron Job
          |- Complete Understanding of Vendor Module and Any Other File Present in Magento
          |- Learn KO 
          |- Learn JS
    - 
    - Learn Complete Hoisting of Magento Site
                                  |----Stage Deploy
                                  |----Production
                                  |----Live 
    - Complete Setup of Magento
                    |--Windows 
                    |     |-------WSL        youtube link - 
                    |     |-------Hyper-V    youtube link - 
                    |     |-------XAMPP      youtube link - 
                    |
                    |--Ubuntu
                    |    |----Docker
                    |    |----LAMPP(Setup Vanilla Magento / Setup the Project)
                    |
                         
   
 </details>

## Service Contract
![Service_contract](https://user-images.githubusercontent.com/78407424/170829619-146e2aa8-2507-4f36-bfaa-718794394412.png)


- Avoid use of Deprecated Method

## Proxies(Proxy Class)
![Proxy_2](https://user-images.githubusercontent.com/78407424/170829650-ccf014c8-d401-4af4-aed3-a8ea578d9482.png)


- di.xml is having higher priority rather than constructor 

## Factories(Factory Class)
![Factories_3](https://user-images.githubusercontent.com/78407424/170829686-0171959b-3bb8-4469-a952-92ad24aca85d.png)


## Indexing
![Screenshot from 2022-05-28 19-30-43](https://user-images.githubusercontent.com/78407424/170829798-5186503c-dead-4948-a22e-c9620b424515.png)



## Caching
![caching_5](https://user-images.githubusercontent.com/78407424/170829843-dc40a7ba-3ebc-40b3-9e6b-1eb3dfd0b73a.png)



## EAV


## Event and Observer


    - Events Related to Order
                                 <!--
                                    sales_order_place_after
                                    sales_order_save_before
                                 -->
     

## Dependency Injection
 
 <details>
  
              Dependency Injection 
             --------------------
                 |-----deffination
                 |-----diff ways or types of injection(constructor,method, by declaring di.xml way)
                 |-----require file to implement the injection
                 |-----types of dependency class
                 |                          |-----Injectable 
                 |                                      |---------what is singleton(cache memory)
                 |                          |-----Non-Injectable
                 |                                      |----------what is Factory class(entity table)
                 |                                      |                    |-------------when we need to use factory class
                 |                                      |                    |-------------advantage of factory class
                 |                                      |----------what is Proxy Class(Lazy loading, Object chaining)
                 |                                                      |--when we need to use proxy class
                 |                                                      |--where we need to declare the proxy class(di.xml)
                 |                                                          |----why we did not use proxy class directly inside constructor inject
                 |-----which two types of node di.xml file support 
                 |                                      |--------type
                 |                                      |--------virtual type
                 |
                 |-----what is the use type
                 |-----what is the use of virtual type
                 |-----Difference bewteen type and virtual type
                 |                                     |---------when to use type 
                 |                                     |---------when to use virtual type
                 |-----What are all the concept we can use to modify the magento core functionality without touching core files
                 |                             |
                 |                             |
                 |                             |----Type
                 |                             |
                 |                             |----Virtual Type
                 |                             |
                 |                             |----Plugin
                 |                             |         |-------what is plugin
                 |                             |         |-------how to declare plugin(folder way)
                 |                             |         |-------where exactly we can apply the plugin(rule's)
                 |                             |         |-------advantage and limitation of plugin
                 |                             |         |-------types of plugin
                 |                             |                         |------what is before plugin(changing method input parameter)
                 |                             |                         |------what is after plugin(changing method output parameter)
                 |                             |                         |------what is around pluign(changing actuall implementation of original code)
                 |                             |
                 |                             |----Preference
                 |                             |            |----what is preference
                 |                             |            |----how to declare plugin(folder way)
                 |                             |            |----where exactly we can apply the preference(rule's)
                 |                             |            |----advantage and limitation of plugin
                 |                             |
                 |                             |----Event and Observer(no modification to original class. need to communicate with other classes)
                 |                                          |---------what is event and observer
                 |                                          |---------how to declare plugin(folder way)
                 |                                          |---------where exactly we can apply the preference(rule's)
                 |                                          |---------advantage and limitation of plugin
                 |
                 |-----Why magento doe's not create object using new keyword
                 |-----Why magento did not allow direct use of Object Manager
                 |-----Why we did not specify Proxy in the Class Constructor Directly
                 |-----When we put Factory to Any Class -- how magento will knowing this and when    
                 |
                 |------------What is Object Manager in Magento(to mange the object by checking from di.xml(all the things declare here)
                                                          |----------what is the use of create() method (for non-injectable class)
                                                          |----------what is the use of get() method (for injectable class)
                 
 </details>
  
![type_virtualtype](https://user-images.githubusercontent.com/78407424/216369559-323f1132-c4f6-46e7-a039-21fc51bfe545.png)
 
 
 
 
 
 
  

## Indexing
      Indexing
      ========
        |
        |---What is Indexing in Magento
        |               |-------Why we need Indexing
        |               |-------How many types of Indexing mode
        |                                 |-----How Update on Save is working
        |                                 |-----How Update on Schedule is working
        |                                           |----Explain end to end how schedule work based on cron job
        |                                                          |------what is change_log table and how it tigger      
        |                                                          |------what is tigger function
        |                                                                  
        |---What all file require to implement indexing concept in magento
                                        |----------what is the use of indexer.xml
                                        |----------what is the use of mview.xml 
                                                          |-----how mview file is link with other files and help cron job to work                                  
## Caching

 <details>
  
    Caching 
    =======
      |
      |----------what is caching
      |                    |-------why we need caching in magento 
      |                    |-------how to know is cache is enable for particular section in magento
      |                    |-------if we declare cache is false then what happen 
      |                    |-------how to know is the site is full cache enabled
      |
      |----------What are all the way to know is the page is cache enable and how to achieve 
      |
      |---------how many types of cache mechanism
      |                           |---------------what is public content(server side)
      |                           |                            
      |                           |---------------what is private content(client side) 
      |                                                      |------------what type of data is called private content
      |--------how cahce is related with
                                      |------varnih
                                      |------redis
                                      |------Opchache
                                      |------JIT
                                      |------Zend Engine 
 </details>
 
                   
                   
## Declarative Schema 
<details>

    Declarative Schema
    ==================
        |
        |-----What is declarative schema
        |                          |-------what all files need to implemnt the declare schema in magento
        |                          |-------how we can perform crud operation using db_schema.xml only
        |
        |----what is the use of db_schema_whitelist.json
        |                          |-----------------------Is mantory to keep the db_schema_whitelist if no then why 
        |
        |----What is Patchs
        |             |-------how many types of patchs available
        |             |                            |----------------what is Data patch
        |             |                            |              
        |             |                            |----------------what is Schema patch
        |             |
        |             |------If we need to create attribute then which patch we need to use
        |             |------If we need to insert data into table then which patch we need to use
        |

</details>


## Service Contract
<details>

    Service Contract
    ================
       |
       |------What is Service contract
       |                 |-----------why should any one implement Service contract in magento
       |                 |-----------Benefit of Service Contract 
       |------types of interface service contract concept implement
       |                        |-------what is Data interfaces
       |                        |                        |---------what is data integrity
       |                        |-------what is service interfaces 
       |                                                 |----------types of service interfaces
       |                                                 |----------what is  Repository interfaces(CRUD)
       |                                                 |----------what is Management interfaces(send the email, manage related)
       |                                                 |----------what is Metadata interfaces(Eg-name has --first_name, last_name)
       |          
   
   </details>
      
## EAV 
      
<details>

       EAV 
      =======
        |--------what is EAV
        |                 |----Why Magento implement EAV concept why not other concept to manage the data
        |                 |----How many types of entity table in magento(9)
        |                 |----How many types of data types table for entity table in magneto(5)
        |                 |----From Which table we will get the complete details of eav_table(eav_entity_type)    
        |                 |----Explain the complete eav_table relation in magento
        |                 |---If we need to add simple customer attribute then explain 
        |                                                                  |----complete flow table including attribute creation from code
        |  
        |--------types of eav attribute 
        |          |--------Custome attribute
        |          |                     |---------
        |          |                     |---------
        |          |
        |          |--Extension Attribute
        |                 |               
        |                 |
        |                 |---What is extension attribute
        |                 |---what all folder structure need to implemnt extension attribute
        |                 |---what is the use of resource in extension_attribute in magento
        |                 |                |-------------------what is the use of join in extension_attribute
        |                 |                |-------------------what are all type of extension attribute (string, init, float or Object)
        |                 |---If we need to add extension attribute for customer then what we need to do.
        |                 |--For getting and set the extension attribute which interface we need extends in service contract design.
                           
</details>


## Implementation Of Third-Party Service in Magento

       |    - Google Tag Manager or Google Analytics                       ->  youtube video link  - 
       |    - Youtube Api (Showing youtube video on Product Image Place)
       |    - SMG91 Sms Gateway Integaration
       |    - Google Shopping Field 
       |    - 
       |
       |
       |
       |
       |
       |
       |- Latest Technology into Magento
          ------------------------------
                      |------ScandiPWA
                      |------Vue



Magento Contribution Path
=========================
       
  <details> 
            <i> https://www.slideshare.net/secret/MwPFaOYcTj496V </i>
        
           - Performance
           - New Tools
           - Architecture
           - As Compare to Other E-commerce 
           - Real Life Simplified
           - Docs Support
           - Security Thread
           
  </details>
  
  
Company Working on Magento
==========================
                   - Codilar Technologies pvt ltd 
                   - Adobe 
                   - Born Groups
                   - Corra
                   - Arnia Software 
                   - Nextus Solutions
                   - Webventure Development 
                   - Flanco Romania
                   -   


# Adobe Training Docs
            
## Ground Level

<details>

    - How magento is taking memory.
            |-------From Which File Magento Code get Executed
            |-------How i can find particular route in magento by folder(code) structure way internally
            |-------Why magento keep this much folder 
            |-------Explain all the folder present in magento and their uses
            |-------If we delete some of the folder then what happen if not then why 
                            |-------Based on Which mechanism magento is Working
                            |-------What all concept magento is implementing  and When




   

             - Use of Elasticsearch. why magento use elasticsearch
                                                 |--------what is elasticsearch
                                                 |--------advantage of elasticsearch
                                                 |--------how elasticsearch work internally
                                                 |--------It is necessary to use elasticsearch
                                                 |--------If We need to change anything in elasticsearch then how can we change 
                                                 |--------Which port it is taking and how it detect the port 
                                                 |-------Why anyone choose elasticsearch
                                       
  
              - Use of Varnish. Why magento use varnish
                                         |--------what is varnish
                                         |--------advantage of varnish
                                         |--------how varnish work internally
                                         |--------It is necessary to use varnish
                                         |--------If We need to change anything in varnish then how can we change 
                                         |--------Which port it is taking and how it detect the port 
                                         |-------Why anyone choose varnish
  
              - Use of Redis . Why magento use Redis
                                      |--------what is Redis
                                      |--------advantage of Redis
                                      |--------how Redis work internally
                                      |--------It is necessary to use Redis
                                      |--------If We need to change anything in redis then how can we change 
                                      |--------Which port it is taking and how it detect the port
                                      |-------Why anyone choose redis 



                   - How to Know In Which File or Which Mechanism Magento Configure Varnish, Redis and Elasticseaerch along with Php
                                                        |
                                                        |-------How Varnish and Redis is Related with Cache and Indexing
                                                        |-------Is it mandotory to use varnish and redis at the same time for magento if yes then Why 
                                                                                         
                                                                                        
                                    
       

                 - What all Application Mode in Magento
                                 |-------By default which application mode magento have 
                                 |-------How to know which application mode we will use and When
                                 |-------What advantage we will get by Application Mode
                                 |-------Is it Helpfull for  Magento 
                               

                - What is Indexing
                            |----------------What are all Indexing mode in magento
                            |----------------How Indexing is Usefull for Magento
</details>

   
###  PHP

<details> 

       PHP
       ===
        |------ How Php code get executed
        |------ Php is a which type of language compiled or interpreted
        |------ Difference between Compiled and Interpreted Language
        |------ Functional Programming vs Object oriented 
        |------ What all mechanishm Php follow for better Performance Result
        |------ What are all the Step Require to Compile the Php Code
        |------ What is Opchace Mechanism in Php
        |------ What JIT concept in Php and Where it Require
        |------ What is the use of Zend Engine in Php
        |------ What all file contain Zend Engine
        |------ What is the Difference between Zend Engine and Zend Framework
        |------ What is the use of PEAR and PECL
        |------ What is the use of Auto_load() method in Php
        |------ Why any one need to use namespaces in Php
        |------ What are all magic method Present in Php and what is magic method
        |
        |------------------OOPS
        |                    |------What is Class
        |                    |                |------variable
        |                    |                |          
        |                    |                |------constructor
        |                    |                |             |------default
        |                    |                |             |------parameterized
        |                    |                |------methods
        |                    |                
        |                    |------What is Object
        |                    |                |--------what is state 
        |                    |                |--------what is behaviour
        |                    |                |--------what is identity
        |                    |------What is Methods
        |                    |                |--------final and static method
        |                    |------What is Variables
        |                    |                 |-------What all variable Scope in Php
        |                    |-------What is Abstraction
        |                    |                    |-----------What is abstract class
        |                    |                    |-----------What is abstract method 
        |                    |                    |-----------Explain exact rule to implement abstraction concept in Php
        |                    |-------What is Encapsulation
        |                    |                      |--------Explain the encapsulation by giving proper code representation
        |                    |-------What is Inheritance
        |                    |                   |---------How many types of inheritance support by Php
        |                    |                   |---------What is the use of Traits in Php
        |                    |-------What is Polymorphisim
        |                    |                     |----------compiled time(static)or(overloading)
        |                    |                     |----------run time(dynamic)or(overriding)
        |                    |
        |                    |-------What is Interface in Php
        |                    |                    |---------------Explain complete implementation of Interface
        |                    |------What is the Difference betweeen Interface and Abstraction  
        |
        |-------------What are all Access Specifier in Php
        |                                      |-------------What is Public and its Scope
        |                                      |-------------What is Protected and its Scope
        |                                      |-------------What is Private and its Scope
        |
        |
        |-----comming soon......
        
</details>


### Magento Fundamentals Part-1
 <details>
  
          Unit-1 Fundamentals of Magento Development pt-1
          -----------------------------------------------

                                - Video Intro
                                - Using the Player
                                - Introduction
                                - Approach Audience
                                - Course Content
                                - Best Way to Take the Course
                                - Unit One Home Page

          Preparation
          -----------
                          - Preparation | Module Topics
                          - Preparation | LAMP $ Composer
                          - Preparation | Magento2 Installation
                          - Video :Install Magento part1
                          - Video :Install Magento part2
                          - Video :Install Magento part3
                          - Video :Install Magento part4
        Overview $ Architecture
        -------------------------
                        - Magento 2 Overview & Architecture | Module Topics
                        - Magento 2 Platform & Architecture Platform
                        - Magento 2 Platform & Architecture | Architecture
                        - Magento 2 Platform & Architecture | Areas
                        - Magento 2 Platform & Architecture | Magento 2 Essentials
                        - Magento 2 Platform & Architecture | Components
                        - Magento 2 Platform & Architecture | Paths
                        - Magento 2 Platform & Architecture | File Types
                        - Magento 2 Platform & Architecture | Config Files
                        - Magento 2 Platform & Architecture | PHP Classes
                        - Magento 2 Platform & Architecture | Development Process
                        - Magento 2 Platform & Architecture | Enable Custom Code
                        - Magento 2 Platform & Architecture | Modules
                        - Modules | Location
                        - Modules | Naming a Module 
                        - Modules | Registering a Module/Empty Module Structure
                        - Modules | module.xml
                        - Modules | module.xml Example
                        - Modules | registration.php
                        - Modules | Module Dependencies
                        - Modules | Types of Module Dependencies
                        - Modules | Module Dependencies Tasks
                        - Reinforcement Exercise 1.3.1:Modules


           File System
           -----------
                        - File System | Module Topics
                        - File System | Root Folders
                        - File System | App Folder Contents 
                        - File System | Framework & Core Modules
                        - File System | Core Source Code
                        - File System | Framework Source Code
                        - File System | Module Structure
                        - File System | Module View File Types
                        - File System | Templates
                        - File System | Templates(expanded)
                        - File System | Themes
                        - File System | Static Files
                        - MULTIPLE CHOICE QUESTION

           Development Operations
           ----------------------

                                          - Development Operations | Module Topics
                                          - Modes | Modules in Magento2
                                          - Modes | Developer Mode in Magento 2 
                                          - Modes | Production Mode in Magento 2 
                                          - Modes | Default Mode in Magento 2 
                                          - Modes | Summary of Mode Features
                                          - Modes | Maintenance Mode in Magento 2 
                                          - Modes | Specifying a Mode : Environment Variable
                                          - Modes | Specifying a Mode : Web Server Environment
                                          - Modes | Specifying a Mode : php-fpm Environment
                                          - Video : Magento Modes
                                          - Reinforcement Exercise 1.5.1:Mode
                                          - Command-Line Interface | Magento 2 CLI
                                          - Cache | Cache in Magento 2 
                                          - Cache | Cache Type
                                          - Cache | Cache Cleaning
                                          - Reinforcement Exercise 1.5.2:Cache
                                        
               DI & Object Manager
               ------------------ 
              
                                          - DI & Object Manager  | Modules Topics 
                                          - Dependency Injection | DI Pattern
                                          - Dependency Injection | Overview
                                          - Reinforcement Exercise 1.6.1:Dependency Injection
                                          - Dependency Injection | Class Instantiation in Magento 2
                                          - Dependency Injection | Different Classes Instantiation
                                          - Object Manager
                                          - Object Manager | Shared Instances Concept
                                          - Object Manager | Object Manager Usage
                                          - Object Manager | Magento 2 Best Practice
                                          - Object Manager | Auto-generated Classes
                                          - Object Manager | Configuration
                                          - Object Manager Configuration | Specification
                                          - Object Manager Configuration | Preferences Example
                                          - Object Manager Configuration | Argument Example
                                          - Object Manager | Configuration Shared Argument Example
                                          - Video : Dependency Injection
                                          - Reinforcement Exercise 1.6.2: Object Manager
                                          - Check Your Understanding(1.6.B)
                                          
                                          
                                    
                Plugins
                -------
                                        - Plugins | Module Topics
                                        - Plugins | Defination
                                        - Plugins | Customizations
                                        - Declare a Plugin
                                        - Plugin Example | Before-Listener Method
                                        - Plugin Example | After-Listener Method
                                        - Plugin Example | Around-Listener Method
                                        - Prioritizing Plugins
                                        - Configuration Inheritance & Plugins
                                        - Plugins | Interception
                                        - Reinforcement Exercise 1.7.1: Plugins 1
                                        - Reinforcement Exercise 1.7.2: Plugins 2
                                        - Check Your Understanding 
                                        - Check Your Understanding
                                    

              Events
              ------

                                        - Events | Module Example
                                        - Events | Defination
                                        - Events | Schema
                                        - Events | Core Example: Saving an Order Process
                                        - Demo | Registering an Event
                                        - Reinforcement Exercise 1.8.1:Events
                                        
                                        
                                        
                                      
                Module Configuration
                --------------------
                                       - Module Configuration | Module Topics 
                                       - Configuration Files Overview
                                       - Configuration Files: Application Configuration
                                       - Configuration Files: Modules's Configuration
                                       - Configuration Files: Merging Config Files
                                       - Configuration Files | Storage
                                       - Configuration Files | core_config_data
                                       - Configuration Files | Backend System Config Page
                                       - Configuration Files | Scope
                                       - Configuration Files | Merging
                                       - Configuration Files | Validation
                                       - Video : XSD Schema
                                       - Error Reporting Settings | Overview
                                       - Check Your Understanding(1.9.1:Module)
                                       - Reinforcement Exercise 1.9.1: Module Configuration
                                       - End of Unit One
  
   </details>
   
     
### Magento Fundamentals Part-2

  <details>
  
      1.1  Introduction to UI Components

                - UiComponent Overview
                - UiComponent Definition
                - UiComponent & Block Comparison
                        
      1.2 Architecture and Configuration
  
               - Architecture 
               - Configuration
                  
     1.3 Templates and Rendering

               - Templates
               - Rendering
 
     1.4 JavaScript Role in UiComponents

               - JavaScript in UiComponent Overview
               - Executing of UiComponents
  
     2.1 Introduction to Grids

              - Grids Overview
              - Listing UiComponent
              - DataSource
              - Columns
              - Filters
              - Mass Actions
              - Grid Indexer
              - Paging
              
     2.2  Intriduction to Forms
  
              - Forms Overview
              - Form Components
              - Form Fieldsets
              - Form Elements
              
 
  
   Question for Part - 2
   ---------------------
  
    - Write a module/class that logs the data that is supplied to the customer add/edit form before it is 
      render in the admin panel. This data can be printed as an array into a log file.
      Create an extension that writes "Hello World" at the top of a mini-cart popup

    - Create a standalone component that renders hardcoded text from default.xhtml . Make that phrase available 
      for all pages.
  
    - Create html templates for a previous exercise that renders a list of customers from DataProvider(list of customers could be hardcoded)
      into html template.
      A skeleton -Unit1/ StandaloneXhtmlTemplateSkeleton has been provided - build on top of it to complete the exercise . It has a pre defined DataProvider
      Create a simple UiComponent which will render the "Hello World" Phrase on the Page

    - Use Unit1/JsDataProviderSkeleton. has a pre-defined DataProvider.

   - Use the generic URL to Fetch data for your component - the URL should use your DataProvider fetch the data.
     Render the data to the html template.
  
 
  </details>
 
 
  

### Cloud for Adobe Commerce

  <details>
  
                     - Overview
                     - Onboarding tasks
  Architecture

                     - Starter architecture
                     - Starter develop and deploy workflow
                     - Pro architecture
                     - Pro develop and deploy workflow
                     - Scaled architecture
                     
  Technologies and requirements

                      - Composer
                      - magento-cloud CLI
                      - ece-tools package
                      - Git
                      - SSH and sFTP
                      - PrivateLink
                      - New Relic
                      - SendGrid 
                      
  Manage your project

                      - Configure your project
                      - Project structure
                      - Manage user access
                      - Enable MFA for SSH
                      - Manage branches with the Interface
                      - Manage branches with the CLI
                      - Manage disk space
                      - Monitor performance
                      - View logs
                      - Snapshots and backup management
                      - Restore an environment
                      - Profile database queries
                      
   Local development setup

                       - Prepare for manual setup
                       - Install prerequisites
                       - Enable SSH keys
                       - Set up the file system owner
                       - Clone and branch the project
                       - Install Commerce
                       - First time deployment
                       - Optional - Configure Xdebug
                       - Optional - Install sample data
                       
   Cloud Docker development

                  Install
                      - Additional tools for file synchronization
                      - Upgrade
                  Use Docker
                        - Production mode
                        - Developer mode
                  Configure and manage
                        - Configuration sources
                        - Manage the database
                        - Manage cron jobs
                        - Set up multiple websites or stores
                        - Xdebug for Docker
                        - Extend Docker
                        - Add Blackfire.io service
                 Docker container architecture
                         - Service containers
                         - CLI containers
                 Functional Testing
                         - Application testing
                         - Cloud code testing for Commerce
                         - Docker quick reference
                         - Get support for Cloud Docker
  Integrations

               - Bitbucket integration
               - GitHub integration
               - GitLab integration
               - Health notifications
               
  Import existing code into a project

               - Prepare your existing system
               - Import code
               
  Configure your store

               - Best practices for store configuration
               - Set up PayPal
               - Set up B2B
               - Set up cron jobs
               - Set up multiple Cloud websites or stores
               - Install, manage, and upgrade modules
               - Install a theme
               - Import URL Rewrites
               
  Configure Fastly services

               - Set up Fastly
                    - Customize cache configuration
                    - Customize error and maintenance pages
               - Web Application Firewall
               - Image Optimization
               - Custom VCL snippets
                    - Reroute requests to a CMS backend
                    - Block referral spam
                    - IP allow list
                    - IP block list
                    - Bypass Fastly cache
               - Fastly troubleshooting
               
  Configure environments

               - Application
                   - Properties
                   - Variables
                   - PHP application
                   - Workers
                   - Set cache for static files
               - Build and deploy
                   - Set up notifications
                   - Logging handlers
               - Routes
                    - Caching
                    - Redirects
                    - Server side includes
               - Services
                    - Set up MySQL service
                    - Set up Redis service
                    - Set up Elasticsearch service
                    - Set up OpenSearch service
                    - Set up RabbitMQ service
               - PHP (php.ini)
               - Environment variables
                    - ADMIN variables
                    - Global variables
                    - Build variables
                    - Cloud variables
                    - Deploy variables
                    - Post-deploy variables
                    - Working with variables
                    
  Configuration management for store settings

               - Example of managing system-specific settings
               
  Optimize deployment

               - Cloud deployment process
               - Scenario-based deployment
               - Zero downtime deployment
               - Static content deployment
               - Smart wizards
               
  Deploy your store

               - Deployment process
               - Continuous deployment
               - Protective block
               - Build and deploy on local
               - Prepare to deploy to Staging and Production
               - Deploy code and migrate static files and data
               - Test deployment
               - Error message reference for ece-tools
               
  Site launch

               - Launch checklist
               - Launch steps
               
  Troubleshooting

               - Component deployment failure
               - Add site map and search engine robots
               
  Upgrades and patches

               - Update ece-tools
               - Apply patches
               - Upgrade version
               - Upgrade project
               
  Release notes

               - ece-tools
               - Cloud Patches for Commerce
               - Cloud Docker for Commerce
               - Cloud Components for Commerce
               - Backward incompatible changes
    
    
    
           Cloud overview --> https://devdocs.magento.com/cloud/bk-cloud.html
           
           Onboarding --> https://devdocs.magento.com/cloud/onboarding/onboarding-tasks.html
           
           Architecture --> https://devdocs.magento.com/cloud/architecture/cloud-architecture.html
           
           Pro vs Started --> https://devdocs.magento.com/cloud/architecture/starter-architecture.html
           
           Pro vs Started --> https://devdocs.magento.com/cloud/architecture/pro-architecture.html
           
           Technical Requirements --> https://devdocs.magento.com/cloud/requirements/cloud-requirements.html
           
           Magneto CLI --> https://devdocs.magento.com/cloud/reference/cli-ref-topic.html
           
           ECE Tool --> https://devdocs.magento.com/cloud/reference/ece-tools-reference.html
           
           Private Link --> https://devdocs.magento.com/cloud/project/privatelink-service.html
           
           SSH --> https://devdocs.magento.com/cloud/env/environments-ssh.html
           
           NewRelic --> https://devdocs.magento.com/cloud/project/new-relic.html
           
           SendGrid --> https://devdocs.magento.com/cloud/project/sendgrid.html
           
           Cloud Project Structure --> https://devdocs.magento.com/cloud/project/sendgrid.html
           
           Fastly --> https://devdocs.magento.com/cloud/cdn/cloud-fastly.html
           
           Configure Application --> https://devdocs.magento.com/cloud/project/magento-app.html
           
           Build and Deploy --> https://devdocs.magento.com/cloud/project/magento-env-yaml.html
           
           Services Configuration --> https://devdocs.magento.com/cloud/project/services.html
           
           PHP Configuration --> https://devdocs.magento.com/cloud/project/magento-app-php-ini.html
           
           Environment Variable --> https://devdocs.magento.com/cloud/env/variables-intro.html
           
           All Type of Variable --> https://devdocs.magento.com/cloud/env/variables-intro.html
           
           Zero Down time deployment --> https://devdocs.magento.com/cloud/deploy/reduce-downtime.html
           
           Go Live Checklist --> https://devdocs.magento.com/cloud/live/site-launch-checklist.html
           
           Upgrade --> https://devdocs.magento.com/cloud/project/project-upgrade-parent.html 
               
      
 </details>
 
           
            
            
















            
            
            
            
            
            
            
            
            
            
            
            
     

