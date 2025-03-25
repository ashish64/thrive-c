# PHP API Application with FrankenPHP for Thrive Cart

This is a simple PHP API application built with FrankenPHP. It provides functionality for retrieving product information and processing orders.

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