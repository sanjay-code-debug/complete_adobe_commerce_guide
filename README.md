
 # Adobe Commere or Magento 2 

complete adobe commerce(magento guide) - https://developer.adobe.com/commerce/php/development/roadmap/  

latest adobe commerce version : - https://experienceleague.adobe.com/en/docs/commerce-operations/release/versions


## adobe commerce guide 
-------------------------
             
                     magento | magento 2 | adobe commerce 
                     ------------------------------------
                                     |
                                     |
                 -------------------------------------------------           
                 |                     |                         |
                 |                     |                         |
               Basic                Intermediate              Advance
      
### Basic
----------
  - Core Tech Stack 
  
  - PHP
    <details><summary><b>info: </b></summary>
     
            |
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


  - Codding Standard
    <details><summary><b>coding-standards</b></summary>
    
              Step - 1
              --------
                         - Install the coding-standard folder   --- https://developer.adobe.com/commerce/php/coding-standards/
                                           |
                                           |
                                           |------------------ direct folder
                                           |
                                           |------------------ install via composer way
    
              Step - 2
              --------
                        - Install(keep) folder any directory location
    
                        - Here i kept under directory location i.e -  /var/www/html/coding-standards
    
                        - cd /var/www/html/coding-standards   ---- composer install
    
    
              Step -3
              -------
                       - use below command to check the coding standard
    
                       - vendor/bin/phpmd /var/www/html/marina/app/code/Codilar/CustomApi/ text /var/www/html/marina/dev/tests/static/testsuite/Magento/Test/Php/_files/phpmd/ruleset.xml
                       
                       - vendor/bin/phpcs --standard=Magento2 --extensions=php /var/www/html/marina/app/code/Codilar/CustomApi/
                       
                       - php src/php-cpd/phpcpd.phar --fuzzy /var/www/html/marina/app/code/Codilar/CustomApi/
                    
     
    </details> 

    <details><summary><b>SOLID</b></summary>
        - https://blog.bytebytego.com/p/mastering-design-principles-solid
    </details> 
   


     
  - Magento Cloud
    <details><summary><b>info: </b></summary>        
        
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
     
 </details>  

### Magento Practical
---------------------

 - how to setup multiple store
    <details><summary><b>info</b></summary>
            
            Link - https://www.youtube.com/watch?v=1HrOfr8e96A
            
                pub/index.php
                
                switch($_SERVER['HTTP_HOST']) {
                case 'casio-gshock-ecom.loc':
                $runCode = 'jp';
                $websiteType = 'website';
                break;
                case 'casio-gshock-ecom.loc.sg':
                $runCode = 'sg';
                $websiteType = 'website';
                break;
                }
                $params[\Magento\Store\Model\StoreManager::PARAM_RUN_CODE] = $runCode;
                $params[\Magento\Store\Model\StoreManager::PARAM_RUN_TYPE] = $websiteType;
                
     </details>   


 - Order Flow 
    <details><summary><b>order concept</b></summary> 
     
        Order Flow: 
            New: when customers just created an order and have not made any payment
            Pending: when the invoice and shipment have not been created
            Processing: the order has been either invoiced or delivered
            Complete: when the order has been both invoiced and shipped
            On hold: admins can assign the On hold status manually
            Canceled: when the order has not been paid for, the store admin or the payment gateway will decide to put this status.
            Closed: a credit memo is included and the refund has been made.
   
    </details>

### Community
 - Contribution
        <details><summary><b>info: </b></summary>
                <i> https://www.slideshare.net/secret/MwPFaOYcTj496V </i>
            
               - Performance
               - New Tools
               - Architecture
               - As Compare to Other E-commerce 
               - Real Life Simplified
               - Docs Support
               - Security Thread        
       </details>
 -        


# Magento Works
#### Multi-Store Setup for Nginx (Ubuntu)
<details><summary><b>info</b></summary>   

     Link  = https://experienceleague.adobe.com/en/docs/commerce-operations/configuration-guide/multi-sites/ms-nginx

     Step-1
     ------
          go the the directory = cd /etc/nginx/sites-available

          create a file i.e   = sudo nano local   (paste the below data - here i am doing two site setup)
     #-------------------------------------------------------------------> 
           upstream fastcgi_backend {
                 server unix:/run/php/php8.1-fpm.sock;
            }
            
            map $http_host $MAGE_RUN_CODE {
                default '';
                dev.forevernew.co.nz fn_nz;
                dev.forevernew.co.au fn_au;
            }
            
            server {
                listen 80;
                server_name dev.forevernew.co.nz dev.forevernew.co.nz dev.forevernew.co.au;
                set $MAGE_ROOT /var/www/html/local;
                set $MAGE_MODE developer;
                set $MAGE_RUN_TYPE website; #or set $MAGE_RUN_TYPE store;
                include /var/www/html/local/nginx.conf.sample;
            }
      #-------------------------------------------------------------------> 
           
            get the website code from this magento table i.e = store_website    (get the Code column value )[here fn_nz and fn_au are  website code ]


    Step-2 
    ------ 
           create two more file inside this  {" cd /etc/nginx/sites-available " directory}  [two store so - two file create if more create more file]

          i)  sudo nano dev.forevernew.co.nz  (paste the below data)

               server {
                    listen 80;
                    server_name dev.forevernew.co.nz;
                    set $MAGE_ROOT /var/www/html/local;
                    set $MAGE_MODE developer;
                    set $MAGE_RUN_TYPE website; #or set $MAGE_RUN_TYPE store;
                    set $MAGE_RUN_CODE fn_nz;
                    include /var/www/html/local/nginx.conf.sample;
                 }

 
         ii) sudo nano dev.forevernew.co.au  (paste the below data)

                server {
                    listen 80;
                    server_name dev.forevernew.co.au;
                    set $MAGE_ROOT /var/www/html/local;
                    set $MAGE_MODE developer;
                    set $MAGE_RUN_TYPE website; #or set $MAGE_RUN_TYPE store;
                    set $MAGE_RUN_CODE fn_au;
                    include /var/www/html/local/nginx.conf.sample;
                }
                
    Step-3
    ------            
             - create the all 3- files symlink to cd /etc/nginx/sites-enabled

             - sudo ln -s /etc/nginx/sites-available/local /etc/nginx/sites-enabled
             - sudo ln -s /etc/nginx/sites-available/dev.forevernew.co.nz /etc/nginx/sites-enabled
             - sudo ln -s /etc/nginx/sites-available/dev.forevernew.co.au /etc/nginx/sites-enabled

             - sudo nginx -t
            
             - sudo service nginx restart

    Step-4
    ------- 

             - Go the your magento folder location (e.g = /var/www/html/local )

             - Find nginx.conf.sample  file 

             - Edit --->  nginx.conf.sample  

             - Search   below location 
                    
                 Previous (existing)
                 =====================
                    # PHP entry point for main application
                        location ~ (index|get|static|report|404|503|health_check)\.php$ {
                            try_files $uri =404;
                            fastcgi_pass   fastcgi_backend;
                            fastcgi_buffers 1024 4k;
                        
                            fastcgi_param  PHP_FLAG  "session.auto_start=off \n suhosin.session.cryptua=off";
                            fastcgi_param  PHP_VALUE "memory_limit=1G \n max_execution_time=18000";
                            fastcgi_read_timeout 600s;
                            fastcgi_connect_timeout 600s;
                        
                            fastcgi_index  index.php;
                            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
                            include        fastcgi_params;
                        }
                        
      Step-5
      -------      
             - Modify the above with Below Two Line  (Before " include    fastcgi_params;  Line)
            #------------------------------------------------------------------->   
                    - fastcgi_param MAGE_RUN_TYPE $MAGE_RUN_TYPE;
                    - fastcgi_param MAGE_RUN_CODE $MAGE_RUN_CODE;
            #------------------------------------------------------------------->          

      Step-6
      ------           
                After Modify (currently)
                ========================
                # PHP entry point for main application

                    location ~ (index|get|static|report|404|503|health_check)\.php$ {
                        try_files $uri =404;
                        fastcgi_pass   fastcgi_backend;
                        fastcgi_buffers 1024 4k;
                    
                        fastcgi_param  PHP_FLAG  "session.auto_start=off \n suhosin.session.cryptua=off";
                        fastcgi_param  PHP_VALUE "memory_limit=1G \n max_execution_time=18000";
                        fastcgi_read_timeout 600s;
                        fastcgi_connect_timeout 600s;
                    
                        fastcgi_index  index.php;
                        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
                #------------------------------------------------------------------->        
                        # START - Multisite customization
                        fastcgi_param MAGE_RUN_TYPE $MAGE_RUN_TYPE;
                        fastcgi_param MAGE_RUN_CODE $MAGE_RUN_CODE;
                        # END - Multisite customization
                #-------------------------------------------------------------------->
                        include        fastcgi_params;
                    }
  

</details>



#### File & Folder Permission On Magento
<details><summary><b>info</b></summary>   
    
    Magento Folder Permission
    ---------------------------
       - # Change ownership of project directory
             sudo chown -R sanjay:sanjay /var/www/html/local

         # Fix permissions for the project directory
            find /var/www/html/local -type d -exec chmod 755 {} \;
            find /var/www/html/local -type f -exec chmod 644 {} \;

       
    Composer Install Not Working Due to Permission Issue
    ----------------------------------------------------
                     - Magento folder have different permission  & .ssh folder have different permission.
                     
                    - Give root permission to .ssh folder (i.e = sudo chmod -R 777 .ssh/) [or] Change the /var/www/html/local magento permission to .ssh level permission.
                     
                    - If /var/www/html/local  is root permission then  move the .ssh to root 
                                
                                        - sudo cp ~/.ssh /root/.ssh/
                                        - sudo chown root:root /root/.ssh/

     
     Basic of Permission For Owner(user),Group and Others
     ----------------------------------------------------
               -  700 (read, write, and execute for the owner only).

                  7 --- read + write + execute [4 + 2 + 1]
                  0 --- nothing
                  0 --- nothing 

                  


      3-types of access restrictions
      ------------------------------
            permission    |  Action | chmod option
                          |         |
             read         |  view   |  r or 4 
                          |         |
             write        |  edit   |  w or 2
                          |         |
             execute      | execute |  x or 1 



      3-types of user restrictions
      ----------------------------
            user  | Is Output 
                  |
            Owner |  -rwx------
                  |
            Group |  ----rwx---
                  |
            Other |  -------rwx
             
                          
           
        chmod  :-  permission related

        chown  :- owner and group related changes
                                   
 

</details>

   
#### Run Php Script On Magento for Quick Testing 
<details><summary><b>info</b></summary>   
    
                <?php
                use Magento\Framework\App\Bootstrap;
                use Magento\Framework\App\ObjectManager;
            
                // Include Magento Bootstrap file
                require __DIR__ . '/../app/bootstrap.php';
                
                // Initialize the Magento application
                $bootstrap = Bootstrap::create(BP, $_SERVER);
                $objectManager = $bootstrap->getObjectManager();
                $state = $objectManager->get('Magento\Framework\App\State');
                $state->setAreaCode('frontend');
                
                // Retrieve the product repository
                $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
                
                // Replace 'your_sku' with the SKU of the product you want to retrieve
                $sku = '257760';
                
                try {
                    // Load the product by SKU
                    $product = $productRepository->get($sku);
                
                    // Display product details
                    echo "Product ID: " . $product->getId() . "<br>";
                    echo "Name: " . $product->getName() . "<br>";
                    echo "SKU: " . $product->getSku() . "<br>";
                    echo "Price: " . $product->getPrice() . "<br>";
                } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                    echo "No product found with SKU: $sku";
                }
                
                /*
                 *
                 * File location = inside the pub directory i.e = /opt/homebrew/var/www/forevernew/pub
                 *
                 * To Run on Terminal = cd /opt/homebrew/var/www/forevernew/pub
                 *                    = php Test.php
                 *
                 *  To Run on Frontend = http://dev.forevernew.co.nz/Test.php
                 *
                 */
</details>






 # Learning At Work

 ## ForeverNew (What I Learn at ForeverNew)
            
#### Push and Get Data to Staging Environment Using Ssh
<details><summary><b>info</b></summary>    
    
        Connect to sftp (From the Directory where  you want to Upload the Files)
        ------------------------------------------------------------------------
                run - : sftp 1.ent-wh2okhswlvpuo-staging-ded-yg3eyli@ssh.us-3.magentosite.cloud

                        sftp> put File.php

                        sftp> put -r directoryName

                        sftp> get File.php

                        sftp> get -r applogy_email                        
        
</details>           
            
  
#### How to Take Table Dump From Staging Environment
<details><summary><b>info</b></summary>
    
     Staging Database Export
     =======================
        Connect to Cloud  
         ------------------
            - magento-cloud ssh
            - choose the respective environment 

      Run this command to Get the DB Dump
     ------------------------------------
           - Go to respective directory where want to keep the file (e.g - e.x - cd var/backups )
           -  mysqldump -uwh2okhswlvpuo_stg2 -pVTdE8ZvbZC7FfLM -h 127.0.0.1 wh2okhswlvpuo_stg2 | gzip > forevernew_sql_12.sql.gz

     After this connect to the SFTP to get .sql File into Local
     -------------------------------------------------------   
          - Run this command inside some directory where you want to keep on local(e.x - cd Downloads)
          - sftp 1.ent-wh2okhswlvpuo-staging-ded-yg3eyli@ssh.us-3.magentosite.cloud
          - Get fileName(e.x - Get forevernew_sql_12.sql.gz)

     Production Database Export
     ==========================
             - same step as staging only - change userName and Password

     Unzip the File 
     
            - gunzip < forevernew_sql_mar_10.gz | mysql -uroot -pAbhi@9902 forevernew
</details>

#### How to Resolve Git Conflict
<details><summary><b>info</b></summary>  

    Creating a new branch for any task 
 
         git checkout production
         git pull origin production
         git checkout -b feature/MS-0000 (Your branch name)
         git add (File path)
         git commit -m "MS-0000 new functionality for website"
         git push origin feature/MS-0000     

   Go to git UI and  create PR of "feature/MS-0000" against staging or release
      If conflict comes in feature/MS-0000 against staging or release      

        Go to your console or terminal

             git checkout feature/MS-0000
             git checkout -b resolve-conflicts/MS-0000 (New branch to resolve conflict)
             git pull origin staging or release branch (Generally staging branch conflict will come)

        Resolve conflict
                  - You can see the conflicts on respective file (just resolved it - PhpStrom UI way or Manually)             

              git add (File path)
              git commit -m "MS-0000_resolve_conflict Fixed conflict issues"
              git push origin resolve-conflicts/MS-0000 

       Go to git UI create PR of "feature/MS-0000_resolve_conflict" against staging or release
           Merge "feature/MS-0000_resolve_conflict" (In background feature/MS-0000 will also get resolved)

    Smart Way : - 

             git pull origin production
             git checkout -b yourTaskBranchName
             git pull origin staging

      Do the development
                git add files
                git commit -m "your commit"
                git push origin yourTaskBranchName  :- Go to git UI and  create PR of "yourTaskBranchName" against staging or release
           

</details>

#### How to Setup X-Debug for Local and Staging Environment On Mac OS
<details><summary><b>info</b></summary>

     
   Xdebug Setup For Local Development 
    
         - Install the Xdebug


   Xdebug Setup For Staging Development

         Xdebug For GUI 





         Xdebug For CLI
         
         
</details>



### Interview Question 
----------------------
- Core Concept : Alway's Refer Vendor Module
     <details><summary><b>info: </b></summary>

            <i> Backend (view/adminhtml) | app/code </i>

              - module 
                  - explain module creation 
                  - why route.xml - explain - id, frontName

              - DI(loose coupling- constructor injection) vs Object Manager(get, create)

              - DI
                 - Dependency Injection in Magento 2 is a design pattern where dependencies are provided to a class instead of being created inside it.
                   Magento uses a DI container (Object Manager) to automatically inject dependencies via constructors

              - Object Manager
                  - The Object Manager is the core class responsible for creating and managing objects in Magento 2.
                      - It is Magento’s Dependency Injection (DI) container
                      - It creates objects, resolves dependencies, and manages shared instances

              - Singletone Class
                    - A Singleton class ensures that only one instance of a class exists and provides a global access point.
                      In Magento 2, Singleton behavior is achieved using dependency injection with shared instances instead of manually implementing the pattern.

              - Factory (Auto generated Class - non-injectable class ---> Entity Class)
                  - In Magento 2, a Factory is used to create new instances of a class dynamically. Magento automatically generates Factory classes,
                    and each call to the create() method returns a new object, unlike Singleton which returns a shared instance."


              - Proxy
                 - In Magento 2, a Proxy class is used for lazy loading.
                   It delays the instantiation of heavy objects until they are actually needed, improving performance.
                   Magento automatically generates proxy classes when we use \Proxy in dependency injection.


              - REST and GraphQl

              - Service Contract (Data , Service - Repository, Managemnt, )

              - Repository (CRUD Operation -- no direct connect to Database)

              - Model , Resource Model, Collection (ORM)

              - EAV (eav_entity_type, eav_attribute) --- eav_ prefix 
                  
                  - 9 but as per b2b more
                  - 5 table (text, varchar, int, decimal, datetime)

              - Declarative Schema, Data Patch , Schema Patch

              - Plugin (before, around, after ) - interceptor (modify the behaviour of class ---public method) - sort order 

              - Event & Observer 

              - Preference (last case)

              - Type vs VirtualType 

              - uiComponent (listing , form component)

              - Area in magento (6)

              - 


              - GA4 , GTM 


              - Explain - Website , Store and Store View :- https://experienceleague.adobe.com/en/docs/commerce-admin/start/setup/websites-stores-views
                      |
                      |
                      |--- Website manages business level, Store manages product categories, and Store View manages language or presentation layer.


                    E.g - 
                       Website: ForeverNew AU  -------------> One website because business is only in Australia
                                      Currency: AUD
                                      Tax: Australia GST
                                      Shipping: Australia rules

                                Store: Women ---------------------> Women product catalog
                                                                    Categories: Dresses, Tops, Skirts

                                    Store View: English -----------> Default language
                                    Store View: Chinese -----------> Chinese translation for customers


                                Store: Men -----------------------> Men product catalog
                                                                    Categories: Shirts, Jeans, Jackets

                                    Store View: English -----------> Default language
                                    Store View: Chinese -----------> Chinese translation


              - Stocks and sources(MSI – Multi-Source Inventory):- (https://experienceleague.adobe.com/en/docs/commerce-admin/inventory/basics/sources-stocks)

                    - Stock -
                        - A Source represents the physical location where inventory is stored.

                    - Source
                       - A virtual inventory that links Sources to Websites.
                       - It decides from which source the product will be sold.

                    - Website → Stock → Sources → Product Qty     


                    - Tables invovped 

                        - inventory_source_item
                        - inventory_reservation
                        - inventory_stock
                        - inventory_source


                - EAV Attribute vs Extension Attribute (Explain uses cases and where the value is going to store)\

                    - EAV attributes are used to store dynamic entity data in EAV tables, 
                      while extension attributes are used to extend service contract APIs and usually store data in custom tables.

                    - EAV
                       - Used to add dynamic attributes to entities like products or customers and stored in EAV tables.

                    - Extension Attribute
                       - Used to extend API data objects without modifying the core database schema. 


                - Explain ViewModel 

                      - declare for specific layout <referenceBlock> <argument> so it will pass to respective .phtml file 

                - Singleton Class 

                      - A Singleton class means only one instance of the class is created and reused everywhere during the application lifecycle.
                      - Instead of creating a new object every time, Magento shares the same object.            

                      
      
             <i> Frontend (view/frontend) | app/design </i>

              - theme
                  - how to create theme
                  - explain what are themes (e.g - hyva)
                  -
  
              - layout  
                 - handles
                 - container, referenceContainer
                 - block, referenceBlock 

                 - how data is passed from .phtml file to ----? js File (ForeverNew/Adyen- storecard.js - $this.attr('data-agreement')
                 - data-mage-init vs x-magento-init

  
  
              - uiComponent : https://developer.adobe.com/commerce/frontend-core/ui-components/
                 - listing component
                 - form component
                 - how data is passed to form or listing (dataSource)
  
              - knockout js 
                 - knockout binding , how js file linked with - html, phtml
                 - what is mixins
                 - explain - ways to override the js file (all the - define , dependency and functionality e.g - map, *, true/false)
                 
    </details>

- Advance Concept : 
     <details><summary><b>info: </b></summary>

               - Caching (private content | public content) - https://experienceleague.adobe.com/en/docs/commerce-admin/systems/tools/cache-management
                   
                   - Redis (session storage + cache) ---> env.php [Redis stores data in RAM, so it is extremely fast.]

                        - session:
                            customer_id
                            cart data
                            login state

                        - cache :
                            config
                            layout
                            block_html
                            collections
      
                   - Varnish (Varnish is a reverse proxy cache server placed in front of Magento.) ----> Settings ---> Configurtion ----> Advance ---> System ---> Caching Application

                        - Serve cached pages

                        
                   - FPC (Opcache)



               - Indexing (Prtial | Full) - https://developer.adobe.com/commerce/php/development/components/indexing/

                  - Update on Save

                  - Update By Schedule 
                         
                         - Cron Job
                         - mview & indexer (mview.xml | indexer.xml) 
                              |
                              |------- Sql Triggers 
                                             |
                                             |----- CL Table  (how change log table will create based on - what ?)
                                                      |
                                                      |---------

                        - what are the status and which table to see the status(indexer_state)

                            -                               

               - What is RabbitMq - Explain how to configure and --- In Case Of Cloud -- Is RabbitMQ we need to install or any other servies we need instead of RabbitMQ

                    - It processes asynchronous tasks using queues.
                    - Instead of executing immediately, the task is pushed to a queue and processed by consumers.

                    - Used for:
                        Async APIs
                        Bulk operations
                        Inventory reservations
                        Order processing
                        Email processing


               - If RabbitMq is not available --- Which Services Magento uses and How

               - What is CRON Job - Explain How It Works 
                     |
                     |---- Is Cron Job Is Asynchronous
                     |
                     |---- Is a cron job asynchronous? Let's say I schedule a cron job for every 5 minutes. If that cron fails, what happens to the next cron job?
                           
                           - Cron itself is not asynchronous. It runs tasks at a specific time interval.

                           - If a cron job fails, Magento records the status in the table:
                                 |
                                 |
                                 |----- cron_schedule
                                            |
                                            |----- pending, running, success, missed and error

                           - The next cron job will still run normally. Cron jobs are independent executions.

                           - Used for:
                                Reindexing
                                Sending emails
                                Catalog price rules
                                Generating feeds
                                Cleaning logs               
                                            


               - Is RabbitMQ is Same as CRON Job -- Exaplain in Detail


            - REST vs GraphQL — Are both cached, and how does authorization work?

                - Authorization 
                   - Admin token
                   - Customer token
                   - Integration token 

                - You created a custom GraphQL query, but it's very slow. What will you check?

                - Your ERP system cannot access product APIs. It gets 401 Unauthorized. What will you check?  

                - Your REST API is not cached even though Varnish is enabled.

                - GraphQL responses are not cached. What could be the reason?

                - GraphQL is slower than REST in your project. Why?

                - How would you secure Magento APIs from abuse?

                - Your mobile app uses GraphQL, and after deployment product queries fail.

                - GraphQL query is loading 100 products, but DB query count is 1000+. (N+1 Query Problem - Use bulk loading / collections.)

                - How would you cache GraphQL product queries with Varnish?

                - 


                
                
            <i> Security </i>


            <i>Best Practice</i>
                 
                 - SOLID 
                 
                 - PHPmd , PHPCs

                 - Codding standard modules 



    </details>
    
- Scenario Based Concept : 
     <details><summary><b>info: </b></summary>

        - Home ---> PLP (sort, search, filter)---> PDP----> Add to Cart | Add to Wishlist ---> Checkout ---> Coupon Code ---> Shipping + Billing - Payment --> Place Order



        - Frontend - Produt Is not Loading It is Slow - Explain the Step to Debug and Found Why it is Slow 


        - Exaplain MVC and MVVM 

        
        - Stock and Source 

            - When an order is placed Magento creates a reservation to reduce salable quantity, and actual source inventory is deducted only when shipment is created. 
            If the order fails or is cancelled, the reservation is released and stock is restored.

            - Customer

                - 

            - Price 
                
                - explain tier price 

                - cart price , cartalog price 

                - how to create a coupon code 

                - 

            - Checkout

                - how to add a new attribute to checkout 

                - 


            - Payment

                - how to implement a new payment method without breaking existing payment method 


            - Product
                
                - when searching a product how it is working (end to end flow)

                - types of product 

            - Order
                - explain complete order flow 


            - Return
                 
                 - 




             
    </details>
    
- Current Company Based Concept/Challenges : 
     <details><summary><b>info: </b></summary>

            - what is the recent issue you faced and how you overcome it.

            - What is the Recent Project or Feature You Developed 
              
    </details>

- Cloud Based Concept : 
     <details><summary><b>info: </b></summary>

            - list B2B modules
                    |
                    |
                    |------- Shared Catalog
                    |
                    |------- Quick Order
                    |
                    |------- Purchase Order
                    |
                    |------- Negotiable Quote
                    |
                    |------- Requisition List 
                    
                        
                

            - what is ece tools 

            - what is the purpose newrelic. explain all various cases 

            - why we are using - Fastly. explain in details 


            - what .mgento and what is the purpose explain 
                     |
                     |
                     |------ .magento/routes.yaml
                     |
                     |------ .magento/services.yaml
                     |
                     |------ .magento/app.yaml
                     |
                     |------ .magento/env.yaml

            - How to update Php version, Mysql or any Other Services         



                   
              
    </details>


- Third Party Extension Based Question : 
     <details><summary><b>info: </b></summary>
            
            - Explain ERP 

            - Akeno 

            - Tecsys

            - Iconic 

            - Freedhopper

            - Social Login (Adyen - Applepay / Googlepay)
              
    </details>



- End to End Question : 
     <details><summary><b>info: </b></summary>

            - explain attribute creation by Patch and it respectice table (patch_list, eav_attribute)

            - when placing order what all table involved 

            - when do add to cart --- where the billing information will going to store

            - 
              
    </details>
    



- Practical Question : 
     <details><summary><b>info: </b></summary>

            - how to create a plugin 

            - how to create a event and observer

            - how to create custom cli commands 

            - how to create custom logger 

            - how to create email_template 

            - how to create custom rest api

            - how to create graphQl

            - 


              
    </details>



- Overall Question : 
     <details><summary><b>info: </b></summary>

            <i>Magento Upgrade | Migration</i>


            <i>Magento Setup</i>


            <i>Git Conflict & Deploy</i>


            - composer install vs composer update

            - how to apply a patch (m2_hotfix)

            - 


              
    </details>



- MySql Question : 
  <details><summary><b>info: </b></summary>

         - there is 2 table - product , orders - can you fetch the - sku which is not sold in last 6 month

         - find the nth highest price of product 
  
         - 
              
  </details>

- Coding Question (Time Complexity / Space Complexity - Best Case): 
  <details><summary><b>info: </b></summary>

         - 0(1) ---- constant time
         - 0(n) ---- linear time
         - 0(log n) -- 
         - 0(n2)

     - reverse a array 3 way (inbuit , by lenth and loop(here 0(n)-time, 0(n)-space, two pointer way (here 0(n)-- time, 0(1)-- space)
     - 
              
  </details>  



#### Interview Tips 

      - Full Confidence
      
      - Never Ever Beg -- Ask the Cross Question If Feel - Low 

      - Stay On - My Point (Show I Am the Best --- Make Them - Wrong)

      - Never Try to Get Hire --- By Empathy and Emotion

      - No Matter What ----> Say the Correct and Be Polite

      - Never Show---- Weakness | Make Interviewer -- Superioir (make them -- confuse) 


    









