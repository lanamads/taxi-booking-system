# CabsOnline Booking and Admin System

This project is a web-based taxi booking and admin management system developed as part of a university assignment. The system consists of two main components: a booking interface for users and an admin interface for managing bookings.

## **Project Overview**

- **Booking Interface**: Allows users to create taxi booking requests.
- **Admin Interface**: Enables admins to search and assign taxi bookings.

### **List of Files**

- **booking.html**: Frontend for creating taxi booking requests.
- **booking.js**: JavaScript for handling booking functionalities.
- **booking.php**: Backend processing for booking requests.
- **admin.html**: Frontend for admin tasks like searching and assigning bookings.
- **admin.js**: JavaScript for handling admin functionalities.
- **admin.php**: Backend processing for admin tasks.
- **style.css**: Stylesheet for both booking and admin pages.
- **mysqlcommand.txt**: Contains MySQL commands used in the system.

## **Important Notes**

### **Database Connection**

The PHP files in this project connect to a university-hosted MySQL database using the following method:

```php
require_once("../../files/settings.php");
```

This settings.php file contains private login credentials and is not included in this repository for security reasons.

### Accessing the System

The live system is hosted on the AUT network and can be accessed at the following URLs:

- [Booking Interface](https://webdev.aut.ac.nz/~scc1338/assign2/booking.html)
- [Admin Interface](https://webdev.aut.ac.nz/~scc1338/assign2/admin.html)

### Network Restrictions

Access to these URLs is restricted to users who are connected to the AUT network and have access rights to my specific student page.

### User Access

Only I, the project creator, and authorized personnel (such as my teaching assistant) can access these pages. Other users, including my peers, will not be able to view or interact with the live site.

### Limitations

Due to the connection setup and access restrictions, it is not possible for others to run this project locally or on their own servers without the appropriate credentials and network access.

## Usage Instructions

### Booking Interface

- Navigate to the Booking Interface to create a taxi booking.
- Fill in all required fields with valid inputs.

### Admin Interface

- Navigate to the Admin Interface to search for and manage bookings.
- You can search by booking reference or view upcoming bookings.

## Security Considerations

To protect sensitive information, the `settings.php` file containing database credentials has been excluded from this repository. Additionally, access to the live system is secured by the AUT network, ensuring that only authorized users can access the functionality.

## License

This project is subject to specific restrictions. Please see the [LICENSE](LICENSE) file for more information.
