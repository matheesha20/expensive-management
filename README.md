# Expensive Management

## Summary

This PHP script is designed to optimize the management of market prices, household item lists, and inventory on a monthly basis. It includes the following features:

- **Manage Market Price List**: Enter detailed information about items including name, unit price, quantity, and unit type. Assign items to specific months to keep track of market prices over time.
- **Manage House Item List**: Choose items from the market price list and specify the quantity needed for the chosen month. Plan your monthly purchases effectively and ensure all necessary items are accounted for.
- **Manage Inventory**: Monitor and manage the borrowing of items from reserved quantities for the chosen month. Maintain accurate records of inventory levels to ensure sufficient stock.
- **View Lists**: Access and manage detailed market price lists, including options to edit, delete, or duplicate items for different months. View house item lists that show both reserved and purchased data. Display comprehensive data for each item, including required quantity, total price, bought quantity, bought amount, and remaining quantity and amount. Utilize the edit and delete options for efficient management and updates.
- **Monthly Management**: All features are organized on a monthly basis, providing a clear and structured approach to managing prices, inventory, and household needs.

## Installation Guide

To set up this PHP script, follow these steps:

1. **Upload Files**: Upload all the script files to your server.
2. **Update Configuration**: Open `config.php` and update the database credentials to match your server's configuration.
3. **Import Database**: Import the provided SQL file into your database to create the necessary tables and insert initial data.

### Detailed Steps

1. **Upload Files to Server**
   - Use an FTP client or your hosting control panel to upload all script files to the desired directory on your server.

2. **Update Database Configuration**
   - Open the `config.php` file in a text editor.
   - Update the following lines with your database credentials:
     ```php
     define('DB_HOST', 'your_database_host');
     define('DB_NAME', 'your_database_name');
     define('DB_USER', 'your_database_user');
     define('DB_PASS', 'your_database_password');
     ```

3. **Import SQL File**
   - Access your database management tool (such as phpMyAdmin).
   - Create a new database if it does not already exist.
   - Select the database and import the SQL file (`database.sql`) provided with the script.
   - This will create the necessary tables and insert initial data.

## License

This project is licensed under the MIT License - see the LICENSE.md file for details.

## Author

Matheesha Prathapa

## Contact

For further information or queries, you can reach out at: info.webnex@gmail.com
