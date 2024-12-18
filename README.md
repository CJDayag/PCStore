# **MyTechPC**

MyTechPC is an e-commerce website that allows users to buy PC parts or fully built desktops online. The platform provides a seamless shopping experience with essential features for both users and administrators.

---

# Features

## **User Features**

* 🛒 **Cart:** Add and manage products in your cart for easy checkout.

* 🖥️ **Products Menu:** Browse a wide selection of PC parts and desktops.

* 👤 **Profile:** View and edit your personal details.

* 🔐 **Login and Signup:** Secure account creation and access.

* 💳 **Checkout:** Complete your purchase (currently supports Cash on Delivery only).

* ✏️ **Edit Profile:** Update your user information anytime.

## **Admin Features**

⚙️ Admin Panel: Manage products, view orders, and oversee website operations.

---

## Technologies Used:

* **HTML:** For the website structure and content.

* **Tailwind CSS:** For modern and responsive styling.

* **PHP:** For server-side scripting and database interactions.

* **JavaScript:** For interactive front-end functionality.


---

## Installation Instructions

**Clone the Repository**

```bash
    git clone https://github.com/DevWithCJ/MyTechPC.git
```

**Set Up a Local Server**

- Use tools like **XAMPP** or **Laragon** to set up a local PHP server.

- Place the project files in the server's htdocs or equivalent directory.

**Configure the Database**

- Create a MySQL database for the project.

- Import the _**pcstore.sql**_ file into your database.

- Update Configuration

- Edit the database connection details in the PHP _Connection_ file:

```php
$host = "localhost";

$user = "root";

$password = "";

$dbname = "your_database_name";
```
---

## Start the Server

Launch your local server and access the website in your browser:
http://localhost/MyTechPC

**How to Use**

1. Sign Up: Create an account to start shopping.

2. Browse Products: Explore the products menu to find PC parts or desktops.

3. Add to Cart: Select your desired items and add them to your cart.

4. Checkout: Place your order using the Cash on Delivery payment option.

5. Manage Profile: Update your account details as needed.

6. Admin Access: Log in as an admin to manage the website.


---

## Future Improvements

* Online payment gateway integration.

* Advanced product filtering and search options.

* Multi-language support.

