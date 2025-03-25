# PHP API Application with FrankenPHP for Thrive Cart

This is a simple PHP API application built with FrankenPHP. It provides functionality for retrieving product information and processing orders.

## Installation

 1. Clone the repo below
		`https://github.com/ashish64/thrive-c.git`
		
 2. CD into the folder and run the command below 
	 `docker-compose up -d --build`
	 
 3. Once the container is available, enter the container with
    `docker exec -it thrive-cart bash`
    
 4. Lastly, Run:
	`composer install`

The application should now be available on 
`http://127.0.0.1:8080`

	

## Endpoints

The application exposes the following endpoints:

### 1.  `GET /`

    * **Description:** Retrieves a list of all products.
    * **Method:** `GET`
    * **Response:**
        * `200 OK` - A JSON object containing an array of products with their names and prices.
        * `404 Not Found` - If the endpoint is incorrect
    * **Example Response:**

        ```json
        {
            "data": {
                "R01": {
                    "name": "R01",
                    "price": "32.95"
                },
                "G01": {
                    "name": "Green Widget",
                    "price": "24.95"
                },
                "B01": {
                    "name": "Blue Widget",
                    "price": "7.95"
                }
            },
            "status": 200
        }
        ```
		* **CURL for Post man**
		```
		curl --location 'http://127.0.0.1:8080' \ --header 'accept: application/json'
		 ```
       

### 2.  `POST /order`

    * **Description:** Processes a product order and calculates the total cost, including discounts and delivery charges.
    * **Method:** `POST`
    * **Request Body:** An array of product codes (strings).
    * **Response:**
        * `200 OK` - A JSON object containing the order details, including a list of products with quantity and total grand total.
        * `404 Not Found` - If the endpoint is incorrect
    * **Example Request:**

        ```json
        ["B01", "R01", "B01"]
        ```

    * **Example Response:**

        ```json
        {
            "data": {
                "products": [
                    {
                        "product_code": "B01",
                        "name": "Blue Widget",
                        "price": "7.95",
                        "quantity": 2
                    },
                    {
                        "product_code": "R01",
                        "name": "R01",
                        "price": "32.95",
                        "quantity": 1
                    }
                ],
                "grandTotal": 18.85
            },
            "status": 200
        }
        ```
        * ** Curl for postman **
        ```
	        curl --location '127.0.0.1:8080/order'
	         \ --header 'Content-Type: application/json' \ --data '["B01", "B01", "R01", "R01", "R01"]'
        ```

Folder structure:
```
backend/ 
├── composer.json 
├── index.php 
├── phpunit.xml 
├── src/ 
│ ├── Controllers/ 
│ │ └── WidgetController.php 
│ ├── Interfaces/ 
│ │ └── ProductRepositoryInterface.php 
│ ├── Repositories/ 
│ │ └── ProductRepository.php 
│ ├── Services/ 
│ │ └── OrderService.php 
│ └── ToolsClass.php 
├── tests/ 
│ └── WidgetControllerTest.php
├── Vendor/ 

```