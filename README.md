# Local Events Platform with Weather Integration and Role-Based Functionalities

## Introduction

This README provides detailed setup instructions, API documentation, and explanations of the role-based functionalities for the Local Events Platform project.

## Setup Instructions

### Prerequisites

Before starting, ensure you have the following installed:

- PHP (>= 8.2)
- Composer
- MySQL (or any other supported database)
- Redis (for caching)

### Installation Steps

 Clone the repository to your local machine:

   ```bash
   git clone <repository-url>

   ```bash
   cd weather-app

   ```bash
   composer install

   cp .env.example .env

   php artisan key:generate

   php artisan serve
    
    install jwt and the key into env file
    ```bash

### Installation openweathermap Steps
    Please create an account on https://openweathermap.org/ and generate the token. Then, place the token into the .env file under the variables WEATHER_BASE_URL and WEATHER_API_KEY.
    For further information, please refer to the documentation available at https://openweathermap.org/api/statistics-api.

   # API Documentation

## Authentication

### Register a new user:

- Method: POST
- Endpoint: /api/auth/register
- Request Body: JSON object with name, email, and password fields
- Response: JSON object with user details and authentication token

### Login:

- Method: POST
- Endpoint: /api/auth/login
- Request Body: JSON object with email and password fields
- Response: JSON object with authentication token

### Home:

- Method: get
- Endpoint: /api/login
- Request Body: -
- Authorization: Bearer token with Admin role
- Response: JSON object with user detail

### Logout:

- Method: POST
- Endpoint: /api/logout
- Authorization: Bearer token
- Response: JSON object with logout message

## Admin Routes

### Events Management

- **Create an event:**
  - Method: POST
  - Endpoint: /api/admin/event
  - Authorization: Bearer token with Admin role
  - Request Body: JSON object with event details (name, description, date, location)
  - Response: JSON object with created event details

- **Update an event:**
  - Method: PUT
  - Endpoint: /api/admin/event/{id}
  - Authorization: Bearer token with Admin role
  - Request Body: JSON object with updated event details
  - Response: JSON object with updated event details

- **Delete an event:**
  - Method: DELETE
  - Endpoint: /api/admin/event/{id}
  - Authorization: Bearer token with Admin role
  - Response: JSON object with delete confirmation message

- **Get all events:**
  - Method: GET
  - Endpoint: /api/admin/events
  - Authorization: Bearer token with Admin role
  - Response: JSON array of event objects

## Buyer Routes

### Event Search

- **Search events:**
  - Method: GET
  - Endpoint: /api/buyer/events
  - Authorization: Bearer token with Buyer role
  - Query Parameters: name, date, location (optional)
  - Response: JSON array of event objects matching the search criteria with average tempreture

## Role-Based Functionalities

- **Admin Role:**
  - Can create, edit, delete, and view events.
  - Access to admin-specific routes requires authentication and Admin role.

- **Buyer Role:**
  - Can search for events based on criteria like name, date, and location.
  - Access to buyer-specific routes requires authentication and Buyer role.

## Evaluation Criteria

- **Functionality:** Role-specific features should be correctly implemented and functional.
- **Code Quality:** Adherence to Laravel best practices, code readability, and maintainability.
- **Scalability and Performance:** Effective database design and query optimization.
- **Security Practices:** Secure handling of user data and protection against common vulnerabilities.
- **Third-Party API Integration:** Efficient use of the weather API with proper error handling.
- **Testing:** Thoroughness of tests covering various scenarios.
- **Documentation:** Clarity and completeness of the documentation.

## Conclusion

This concludes the setup instructions and API documentation for the Local Events Platform. If you have any further questions or issues, please don't hesitate to reach out to the project maintainers. Thank you for using our platform!

