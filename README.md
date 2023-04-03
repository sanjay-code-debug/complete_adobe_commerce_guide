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


## Codding Standards
     - https://developer.adobe.com/commerce/php/coding-standards/
     
     
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
===================

LoggedIn Customer Flow
======================


   
PLP(Product Listing Page)
=========================


PDP(Product Description Page)
============================


Checkout 
========



Cart
====




Service Contract Both Way
=========================

          Basic Way
          ---------
          
          
          
          Repository Way
          --------------
          
## Various Task Quick Help 
#### Deprecated Functionality
          
          
          
# GraphQl
--------          
          
          

   

Third Party Integration
=======================





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


Adobe Training Docs
====================


  Cloud
  
  
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
           
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
     

