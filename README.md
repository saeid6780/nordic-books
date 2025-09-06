# Nordic Books

A WordPress plugin to manage and display a list of books.  
The plugin provides a frontend shortcode with AJAX-based book creation, customizable table colors, and an admin interface for managing books.

---

## ✨ Features

- **Shortcode** `[book_list]` to display books in a table with customizable primary/secondary colors.  
- **AJAX-powered add book form** (modal-based) with validation and secure database insertion.  
- **Internationalization (i18n)** ready and translation-friendly.  
- **Admin page** with a book list using `WP_List_Table`.  
- **Database table `wp_books`** automatically created on activation.  

---

## 📦 Installation

1. Download or clone this repository into your WordPress plugins directory:

   ```bash
   cd wp-content/plugins
   git clone https://github.com/saeid6780/nordic-books.git
   
Or download the .zip from GitHub and extract it into the plugins folder.

2. Activate the plugin from the WordPress Admin → Plugins page.

3. On activation, a new table wp_books will be created automatically in your WordPress database.

## 🚀 Usage
Shortcode: [book_list]

Insert the following shortcode into any page or post:

[book_list]
This will render:

A table of all current books (title, author, published year).

An Add Book button above the table, opening a modal form to add a new book via AJAX.

## Shortcode Attributes
You can customize the table colors with optional attributes:

primary_color — Sets the primary background color.

secondary_color — Sets the alternating row background color.

Example:

text
Copy code
[book_list primary_color="#2c3e50" secondary_color="#ecf0f1"]
If no attributes are provided, default colors will be used.

## 🔧 Admin Interface
The plugin creates a new Books menu item in the WordPress admin.
This page displays all books using the WP_List_Table class, allowing administrators to manage the book list in a familiar WordPress interface.

## 🌍 Internationalization
All strings are wrapped with translation functions.

The plugin is translation-ready and supports multiple languages.

## 🗑️ Uninstallation
When the plugin is deleted from the WordPress admin, the wp_books table is removed automatically.

## 📄 License
This project is licensed under the MIT License.
Feel free to use, modify, and distribute it under the terms of the license.

## 🤝 Contributing
Pull requests are welcome!
For major changes, please open an issue first to discuss what you would like to change.