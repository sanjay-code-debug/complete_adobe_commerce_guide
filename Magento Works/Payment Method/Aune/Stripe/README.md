# Custom Stripe Payment Method Integration 



### Stripe Details 

      url - https://dashboard.stripe.com/

      get stripe (sandbox account)
              - Publishable key
              - Secret key
      

### composer require 

     cd /var/www/html/local   # your magento root
     composer require stripe/stripe-php:^10.0


### update the configuration with stripe public key and secret key 

         - Publishable key 
         - Secret Key


### Order Flow 

       - As I am using - USA details for place the order (default stripe available for USA)

       - USA address 
               
               - bangloe
                 benglore, Federated States Of Micronesia, 
                 Zip - 62701
                 United States
                 T: 6465180948
 
       - Card Details (stripe test cards - https://docs.stripe.com/testing )
             
             Card -  4242 4242 4242 4242
             CVC  - 123
             Expiry - 12/34 

       - Open stripe dashboard you can see the order details


### Enhancement 

      - Need to Make this Code to be compatible with - php8.4 / any latest 
