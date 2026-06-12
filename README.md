## All Work - Company Wise
   :- https://docs.google.com/spreadsheets/d/1foYBIGWsJAEXTA8ST7vwJWGqQgJZH2k4odPjKt-WMuY/edit?usp=sharing

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



           - Explain this below

                  - echo true + true + false ; ----- is is correct 
            
    </details>


  - Best Practices
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

     
  - Magento Cloud
    <details><summary><b>info: </b></summary> 
         - https://experienceleague.adobe.com/en/docs/commerce
            
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

## Payment GateWay Integration

#### Custom Payment Method Basic 
<details><summary><b>info</b></summary> 

</details>

#### Customize Payment Method For - ACH:  
<details><summary><b>info</b></summary> 

</details>

#### Customize Payment Method For - Comming Soon..:  
<details><summary><b>info</b></summary> 

</details>


## ERP Integration

#### 30 Lakh Product Disable From Magento to Akeno:  
<details><summary><b>info</b></summary> 

</details>
   


# Magento Quick - Hacks
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

 ### ForeverNew (What I Learn at ForeverNew)
            
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
    
   For Vs Code 
   --------------
      - sudo apt install php8.4-xdebug -y
      - php -v

      nano /etc/php/8.4/cli/conf.d/20-xdebug.ini 

      zend_extension=xdebug.so
      xdebug.mode=debug
      xdebug.start_with_request=yes
      xdebug.client_host=127.0.0.1
      xdebug.client_port=9003
      xdebug.log=/tmp/xdebug.log
      xdebug.idekey=VSCODE
      xdebug.max_nesting_level=1000

      Open Code into VS Code 
          cd /var/www/html/local$ code . 

      Create dir
      
      .vscode
        |
        |----- /var/www/html/local/.vscode/launch.json

         {
          "version": "0.2.0",
          "configurations": [
              {
                  "name": "Listen for Xdebug (Magento2)",
                  "type": "php",
                  "request": "launch",
                  "port": 9003,
                  "pathMappings": {
                      "/var/www/html/local": "${workspaceFolder}"
                  },
                  "log": true
              }
          ]
      }

   Start Debug 
   
      Now do the Full Test:
      In VS Code press Ctrl+Shift+D
      Select "Listen for Xdebug (Magento2)"
      Click ▶️ — bottom bar turns orange
      Open pub/index.php in VS Code
      Click line 1 to set a 🔴 breakpoint
      Visit http://magento.local in browser
      VS Code should pause ✅   


  For PhpStrom
   -----------
      - sudo apt install php8.4-xdebug -y
      - php -v
      
      sudo bash -c 'cat > /etc/php/8.4/cli/conf.d/20-xdebug.ini << EOF
      zend_extension=xdebug.so
      xdebug.mode=debug
      xdebug.start_with_request=yes
      xdebug.client_host=172.30.224.1
      xdebug.client_port=9003
      xdebug.log=/tmp/xdebug.log
      xdebug.idekey=PHPSTORM
      xdebug.max_nesting_level=1000
      EOF'
      
      sudo touch /tmp/xdebug.log
      sudo chmod 777 /tmp/xdebug.log
      
      sudo service php8.4-fpm restart
      sudo service nginx restart
      
      Run → Edit Configurations → + → PHP Remote Debug
      SettingValueNameMagento XdebugFilter by IDE key✅ checkedServerlocalIDE keyPHPSTORM
      Click Apply → OK



   Xdebug Setup For Staging Development

         Xdebug For GUI 





         Xdebug For CLI
         
         
</details>


 ### Hitachi (What I Learn at Hitchi)
            
#### ACH PaymentGateway Integration
<details><summary><b>info</b></summary>                   
        
</details>           

#### ERP  Integration
<details><summary><b>info</b></summary>                   
        
</details>  
            

## Interview Question 
----------------------
- Core Concept : Alway's Refer Vendor Module
     <details><summary><b>info: </b></summary>

              - What are Design Patterns in Magento

                   - explain service design pattern
                          |
                          |-- data 
                          |
                          |-- service 
                                 |------ repo
                                 |
                                 |------ management 
                                 |
                                 |------ data 
  
                   - explain registry pattern 

                   - mvc

                   - mvvm

                   - factory

                   - proxy 

                   - dependency injection

                   - singleton

                   - 


            - Product Types 
               
                - Simple Product — Individual product without variations.
                - Configurable Product — Product with selectable options like size or color.
                - Grouped Product — Collection of simple products shown together.
                - Bundle Product — Customizable product where customers choose components.
                - Virtual Product — Non-physical product like services or memberships.
                - Downloadable Product — Digital product such as PDFs, software, or music.
                - Gift Card Product — Product used to purchase/store gift card balance.


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

              - Service Contract (Data , Service - Repository, Managemnt, Data)

              - Repository (CRUD Operation -- no direct connect to Database)

              - Model , Resource Model, Collection (ORM)

              - EAV (eav_entity_type, eav_attribute) --- eav_ prefix 
                  
                  - 9 but as per b2b more
                  - 5 table (text, varchar, int, decimal, datetime)

              - Declarative Schema, Data Patch , Schema Patch

              - Plugin (before, around, after ) - interceptor (modify the behaviour of class ---public method) - sort order
                    - 3 Pluin written on same core method (which will execute -- tell the exact flow)

              - Event & Observer
                    - 2 event and obserser written on a core event -- tell which will execute first 

              - Preference (last case)

              - Type vs VirtualType 

              - uiComponent (listing , form component)

              - Area in magento (7)

                    - frontend
                    - adminhtml
                    - crontab
                    - graphql
                    - webapi_soap
                    - webapi_rest
                             |---------- why we need to write the di.xml inside this area (why not global area)

              - GA4 , GTM 

              - LogRocket 

              - DataDome 

              - 


              - Explain - Website , Store and Store View :- https://experienceleague.adobe.com/en/docs/commerce-admin/start/setup/websites-stores-views
                      |
                      |
                      |--- Website manages business level, Store manages product categories, and Store View manages language or presentation layer.

   
                      Global (Magento Installation)
   
                          ├── AU Website (forevernew.com.au)
                          │     └── Main Store
                          │           └── English Store View
                          
                          ├── CA Website (evernew.ca)
                          │     └── Main Store
                          │           ├── English Store View
                          │           └── French Store View
                          
                          ├── IN Website (forevernew.co.in)
                          │     └── Main Store
                          │           └── English Store View
                          
                          ├── NZ Website (forevernew.co.nz)
                                └── Main Store
                                      └── English Store View

                      Website = Country / Domain (Business separation)
                      Store = Product catalog (logical grouping)
                      Store View = Language / localization (UI level)                

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


                 - GraphQl

                      - How to add a custom field to Product
                      - How to Maintain the Security 
 
                  - Rest Api
  
                      - Add a Custom Field and That Value only applicable to RestAPI not into -- Frontend
                      - I want to add value to custom field based on Price -- How we can achieve it
                
      
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
   - Caching (private content | public content)
   
                   |
                   |---- public content --- https://developer.adobe.com/commerce/php/development/cache/page/public-content
                   |---- private content --- https://developer.adobe.com/commerce/php/development/cache/page/private-content 


    - Cache Storage - https://developer.adobe.com/commerce/php/development/cache/partial/cache-type
                   
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

                        
                   - FPC (Opcache) - Default Built in Magento Cache (Full - Html Content will cache)


                   - There is 20 to 30 Product In this Case Which Caching We Can Use
                                    |
                                    |-------- Redis ? Varnish ? FPC and Why   

                   

      - Cache Management - - https://experienceleague.adobe.com/en/docs/commerce-admin/systems/tools/cache-management

              - cache : clean 
              - cache : flush
               
 
      - Indexing (Prtial | Full) - https://developer.adobe.com/commerce/php/development/components/indexing/
               
                  - Update on Save
                         - 

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

       - What is RabbitMq : - https://experienceleague.adobe.com/en/docs/commerce-operations/installation-guide/prerequisites/message-brokers/rabbitmq
     
                    - https://developer.adobe.com/commerce/php/development/components/message-queues/

                    - Explain how to configure and --- In Case Of Cloud -- Is RabbitMQ we need to install or any other servies we need instead of RabbitMQ
                    - It processes asynchronous tasks using queues.
                    - Instead of executing immediately, the task is pushed to a queue and processed by consumers.

                    - Used for:
                        Async APIs
                        Bulk operations
                        Inventory reservations
                        Order processing
                        Email processing


               - If RabbitMq is not available --- Which Services Magento uses and How

      - CRON JOB :- https://experienceleague.adobe.com/en/docs/commerce-operations/configuration-guide/crons/custom-cron
      
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

     - API
  
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


    - Performance
       

        - after add to cart -- cart items is not loading why and how to debug in adobe commerce 

        - check is slow customer is unable to make the payment 
        
                
                
    - Security
            
            1. XSS — Cross Site Scripting
                - Attacker injects malicious JS into your page, runs in victim's browser.
                
                - php// Prevent
                    echo $block->escapeHtml($userInput);        // always escape output
                    echo $block->escapeUrl($url);               // escape URLs  

            2. DDoS — Distributed Denial of Service
                - Thousands of bots flood your server with requests, making it unavailable.   
                
                - Prevent
                    limit_req_zone $binary_remote_addr zone=one:10m rate=10r/s;
                    Use Cloudflare / Fastly WAF rate limiting at edge

            3. SQL Injection
            - Attacker injects SQL via input fields to read/delete your database.   

            - // Prevent — always use prepared statements

                    $collection->addFieldToFilter('sku', ['eq' => $sku]);
                    $connection->query("SELECT * FROM t WHERE id = ?", [$id]);    

            4. CSRF — Cross Site Request Forgery        
                
                - Tricks logged-in user's browser into making unwanted requests to your site.

                - // Prevent
                    Magento handles automatically via form_key
                    <input type="hidden" name="form_key" 
                        value="<?= $block->getFormKey() ?>"/>

            5. Brute Force Attack
            - Attacker tries thousands of password combinations on admin/customer login.   

            - Prevent
                // Admin → Stores > Config > Advanced > Admin
                // Max Login Failures = 5
                // Lockout time = 15 mins
                // Change admin URL: bin/magento setup:config:set --backend-frontname=secret123  

            6. Remote Code Execution (RCE)
            - Attacker uploads malicious PHP file and executes it on your server.

            - Prevent — block PHP execution in media/uploads folder
                    location ~* /pub/media/.*\.php$ { deny all; }
                    location ~* /pub/static/.*\.php$ { deny all; }

            7. Path Traversal / Directory Traversal
            - Attacker uses ../../etc/passwd in file paths to access server files.

            - Prevent — validate and sanitize file paths
                    $safePath = basename($userInput);   // strips directory traversal
                    $realPath = realpath($basePath . '/' . $safePath);
                    if (strpos($realPath, $basePath) !== 0) { throw new Exception('Invalid path'); }

            8. Clickjacking
            - Attacker embeds your site in invisible iframe and tricks users into clicking.  

            - Prevent — X-Frame-Options header
                // Magento sets this by default
                // Verify in: Stores > Config > General > Web > Default Pages
                header('X-Frame-Options: SAMEORIGIN');
                header('Content-Security-Policy: frame-ancestors \'self\'');  

            9. Sensitive Data Exposure
            - API keys, DB passwords, .env files exposed publicly via misconfiguration.

            - Prevent
                - Block sensitive files in nginx
                    location ~* \.(env|log|git|htaccess|pem|key)$ { deny all; }
                    Never commit credentials — use Vault / AWS Secrets Manager    

            10. Insecure Direct Object Reference (IDOR) 
            - Attacker changes order ID in URL (/sales/order/view/order_id/1001) to see other customer orders.

            11. Mass Assignment / Parameter Tampering 
            - Attacker sends extra POST params (price=0.01) to manipulate order totals.

            12. XML External Entity (XXE)
            - Attacker sends malicious XML to read server files via XML parsers.

            13. Magento-Specific — Admin Panel Exposure 
            - Admin accessible at /admin — bots constantly probe this.

            14. Third Party Module Vulnerabilities
            
            - Malicious or poorly coded modules injecting backdoors or exposing data.

            15. Session Hijacking
            - Attacker steals session cookie to impersonate logged-in customer/admin.
  
 

     - Data Migration

           - 10 Lakh Product --- How to Migrate

               - Standard import approach on 1M products:
               
                    Magento Admin Import    → times out at 10k products ❌
                    Single CSV import       → memory exhaustion ❌
                    All at once             → DB locks, site goes down ❌
                    No rollback plan        → data corruption risk ❌
                    Images all at once      → storage I/O bottleneck ❌
                    Reindex after import    → hours of downtime ❌

               - Migration Architecture Overview
               
                 SOURCE SYSTEM                                          MAGENTO
                 (ERP/PIM/Old Platform)

                 Products Data ──► Extract ──► Transform ──► Validate ──► Load ──► Verify
                                    ↓              ↓             ↓          ↓        ↓
                                 CSV/API        Mapping        Rules      Batches   Reports
                                   JSON            Logic         Check       Queue     Alerts

               - 1.1 — Audit Source Data First

               - 1.2 — Split 1M Products into Categories
            
               - 2.1 — Dedicated Migration Server

               - 2.2 — Disable Everything Non-Essential


           - 10 Lakh Customer --- How to Migrate

                - Phase 1 — Database Migration Strategy (Biggest Risk)

                    - These tables will be HUGE
                        customer_entity          -- 1M+ rows
                        customer_entity_varchar  -- 10M+ rows
                        customer_entity_int      -- 5M+ rows
                        customer_grid_flat       -- 1M+ rows
                        quote                    -- millions of rows
                        sales_order              -- millions of rows


                # NEVER run ALTER TABLE directly on production with 1M rows

                    - It locks table for hours

                # Custom Batch Reindexer for 1M customers:

                    - // Console/Command/BatchReindexCustomers.php



                - Migration tool :- https://experienceleague.adobe.com/en/docs/commerce-operations/tools/data-migration/basics/upgrade

                  - composer require magento/data-migration-tool

                  - php bin/magento migrate:settings




     - Upgrade Magento 2 
 
             - What are the Step you will follow to --- Upgrade the Current Verion of Magento To Any Specific Verison
                  
                - UCT Tools - https://experienceleague.adobe.com/en/docs/commerce-operations/upgrade-guide/upgrade-compatibility-tool/use-upgrade-compatibility-tool/run\


        - Steps 
                
            1.1 — Check Compatibility First

                - # Check current version

                bin/magento --version

                    # Check target version release notes
                    # https://experienceleague.adobe.com/docs/commerce-operations/release/notes

                    # Run Adobe Upgrade Compatibility Tool (UCT)
                    composer require magento/upgrade-compatibility-tool --dev

                    bin/uct upgrade:check . \
                        --coming-version=2.4.7 \
                        --ignore-current-version-compatibility-issues

            1.2 — Check All 3rd Party Module Compatibility

                # List all non-Magento modules
                    composer show | grep -v magento

                        # For each module check vendor's changelog
                        # Check on Magento Marketplace compatibility tab

                        # Example: check if module supports target version
                        composer outdated

            1.3 — Backup Everything

                # Full database backup

                    mysqldump -u root -p magento_db > backup_$(date +%Y%m%d).sql

                    # Full codebase backup
                    tar -czf magento_backup_$(date +%Y%m%d).tar.gz \
                        --exclude='./var' \
                        --exclude='./pub/media' \
                        .

                    # Note current version in backup filename
                    # backup_2.4.5_to_2.4.7_20240516.sql
                      
            1.4 — Check PHP & System Requirements

                # Target version requirements

                    # Magento 2.4.7 needs PHP 8.2+
                    php -v

                    # Check required extensions
                    php -m | grep -E 'bcmath|ctype|curl|dom|gd|hash|iconv|intl|mbstring|openssl|pdo_mysql|simplexml|soap|xsl|zip'

                    # MySQL version
                    mysql --version   # needs 8.0+

                    # Elasticsearch/OpenSearch version
                    curl localhost:9200  # check version

            2.1 — Enable Maintenance Mode

                bin/magento maintenance:enable

                # Verify
                bin/magento maintenance:status

               
              - What is the Latest Vesion and It's - Respective Requirements

                - https://experienceleague.adobe.com/en/docs/commerce-operations/installation-guide/system-requirements

            2.2 — Update composer.json
               
               # Option A — Command (recommended)

                    composer require \
                        magento/product-community-edition=2.4.7 \
                        --no-update

                    # For Adobe Commerce (Enterprise)
                    composer require \
                        magento/product-enterprise-edition=2.4.7 \
                        --no-update

                    # Update 3rd party modules to compatible versions
                    composer require \
                        vendor/module-name=^2.0 \
                        --no-update

            2.3 — Run Composer Update

                # Dry run first
                    composer update --dry-run 2>&1 | tee upgrade_dryrun.log

                    # Review the log — check for conflicts

                    # Actual update
                    composer update \
                        --with-all-dependencies \
                        2>&1 | tee upgrade_composer.log   

            2.4 — Handle Composer Conflicts

                # Common conflict — fix one by one
                    # Example: module requires old magento/framework version
                    composer why-not magento/framework 2.4.7

                    # Check what's blocking
                    composer prohibits magento/framework 2.4.7

                    # Update conflicting module
                    composer require conflicting/module=^3.0 --no-update

                    # Retry update
                    composer update --with-all-dependencies   

            3.1 — Run Magento Upgrade Commands
                # 1. Run setup upgrade
                    bin/magento setup:upgrade
                    # This runs all db_schema, data patches, upgrades

                    # 2. Compile DI
                    bin/magento setup:di:compile

                    # 3. Deploy static content
                    # For production
                    bin/magento setup:static-content:deploy \
                        en_US en_AU en_GB \
                        -f \
                        --jobs=4

                    # 4. Reindex all
                    bin/magento indexer:reindex

                    # 5. Flush cache
                    bin/magento cache:flush

            3.2 — Disable Maintenance Mode

                bin/magento maintenance:disable

            4.1 — Automated Testing

            4.2 — Manual Smoke Test Checklist   

                FRONTEND
                    □ Homepage loads
                    □ Category page loads with products
                    □ Product page loads — images, price, add to cart
                    □ Search works
                    □ Mini cart updates
                    □ Checkout — guest & logged in
                    □ Payment methods work (test each one)
                    □ Order confirmation email received
                    □ My Account — orders, address, wishlist

                ADMIN
                    □ Admin login works
                    □ Order grid loads
                    □ Create order from admin
                    □ Invoice, Ship, Credit Memo creation
                    □ Product save works
                    □ Category save works
                    □ CMS page save works
                    □ Config save works
                    □ Import/Export works

                INTEGRATIONS
                    □ ERP sync working
                    □ Payment gateway responding
                    □ Shipping carriers returning rates
                    □ Email sending working
                    □ Cron jobs running    


    - Best Practice
                 
                 - SOLID
                       S – Single Responsibility Principle
                            One class should have only one responsibility.
                            Example: In Magento, a Product class should handle only product data, not email sending.

                       O – Open/Closed Principle
                            Open for extension, closed for modification.
                            Example: In Magento, use Plugins or Preferences to extend functionality instead of changing core files.

                       L – Liskov Substitution Principle
                            A subclass should be replaceable with its parent class without breaking functionality.
                            Example: A custom payment method should work anywhere the base payment method is used.

                       I – Interface Segregation Principle
                            Clients should not depend on interfaces they do not use.
                            Example: Create small Magento interfaces for specific features instead of one large interface.

                       D – Dependency Inversion Principle
                            High-level modules should not depend on low-level modules; both should depend on abstractions.
                            Example: In Magento, use Dependency Injection (DI) with interfaces instead of directly creating objects using new.

                 
                - PHPMD (PHP Mess Detector)
                    Tool used to find bad code practices and unnecessary code in PHP.
                    Example: Detects unused variables, long methods, complex code in Magento.

                - PHPCS (PHP CodeSniffer)
                    Tool used to check coding standards and formatting rules in PHP.
                    Example: Checks Magento coding standards like indentation, naming, and file structure.


                 - Codding standard modules

                 - explain SOLID with Current Magento Code Base

                 - Proper Example of -- Oops 

    </details>

  </details>  
    
- Scenario Based Concept : 
     <details><summary><b>info: </b></summary>

        - Home ---> PLP (sort, search, filter)---> PDP----> Add to Cart | Add to Wishlist ---> Checkout ---> Coupon Code ---> Shipping + Billing - Payment --> Place Order

        - when declare di.xml file for webapi_rest (why we need to declare globally why need to declare inside the webapi_rest)

        - Writen 2 pluin on a - existing vendor code function --- which plugin will execute and why explain same for event and observer

        - In a particular case 
                   -- some junior written the plugin but we can achieve the same with event as well === can you explain -- when exactly we need the pluin or event 

        - Performance
  
              - How to increase the performace of magento site (Explain - in details)

              - In Customer Cart - you added a custom - customer attribute but it is fails under - Pulic content so - how you make this will be different value for respective customer

              - Customer Cart Is --- Loading Slow  --- What are Step to flow so - find the root cause why it is causing issue 
               

        - Security
              - What are best way to prevent various attack on Magento site.
              - Sql Injection - How to prevent the Sql injection attack
              - What is CSRF → form key valitdation.
              - What is DDOs Attack and How To Prevent.


        - Caching (Explain In Detail - Scenario Based)
  
             - Customer - Related Data (specially - specific customer attribute where it will cache and how it will render for - Respective Customer)

             - how actually caching is working for Specific Customer can you explain end to end flow -- is any issue with performance how we can improve

             - If Cache is Faster than Database - why not store everything in Cache

                     - Cache is Volatile but the Database is Durable
                     - RAM is expensive. The Disk is Cheap
                     - Cache is not designed for Complex Queries
                     - Consistency becomes nightmare
                     - Not all data needs to be fast

      

        - Frontend Layout(Magento / Adobe Commerce Frontend)

              - Produt Is not Loading It is Slow - Explain the Step to Debug and Found Why it is Slow

              - For Specif Block --- 



        - Exaplain MVC and MVVM 

        
        - Stock and Source 

            - When an order is placed Magento creates a reservation to reduce salable quantity, and actual source inventory is deducted only when shipment is created. 
            If the order fails or is cancelled, the reservation is released and stock is restored.


            - Customer
  
                - How Customer Data Loads
                - 

            - Price 
                
                - explain tier price 

                - cart price , cartalog price 

                - how to create a coupon code 

                - 

            - Checkout

                - how to add a new attribute to checkout 

                - is checkout data for the customer is cache ? 


            - Payment

                - how to implement a new payment method without breaking existing payment method 


            - Product
                
                - when searching a product how it is working (end to end flow)

                - types of product 


            - Order

                - explain complete order flow 

                - explain end to end flow --- how order will flow to erp and other system 

                - if i want to add any extra attribute to order how we can do that - can you explain 


            - Return
                 
                 - 




             
    </details>
    
- Current Company Based Concept/Challenges : 
     <details><summary><b>info: </b></summary>

            - what is the recent issue you faced and how you overcome it.

            - what is the Recent Project or Feature You Developed 

            - what is your day to day work 

            - how many project did you work 

            - What are  OMS used 

            - What are PIM used 

            - What are used for security 

            - What are the used for ERP 

            - What are all integration you worked on - explain any good one 



            
              
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
                 - RMS
                 - OVC

          - PIM 

            - Akeno 

            - Tecsys

            - Iconic 

            - Freedhopper

            - Social Login (Adyen - Applepay / Googlepay)

     - Payment Gateway Integraton

       
     - ERP Integration 


              
    </details>



- End to End Question : 
     <details><summary><b>info: </b></summary>

            - Explain attribute creation by Patch and it respectice table (patch_list, eav_attribute)

            - When placing order what all table involved 

            - When do add to cart --- where the billing information will going to store

            - What is Index : - Explain in db_schema.xml (how actually helps to - in performance)

            - Once Order Is Place --- How to Send the Order Details To ERP Explain End to End Flow

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
  
         - Btree vs Full-index Magento 2 (real time example)
         - Having vs Where 
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

      - No Matter What ----> Say the Correct and Be Polite | Clam and Confident | Say Not Sure 

      - Never Show---- Weakness | Make Interviewer -- Superioir (make them -- confuse) 


    









