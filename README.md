# WooCommerce Google Sheets Integration

A simple plugin to automatically send your WooCommerce store orders to a Google Sheet. This plugin is not intended to be a user-friendly solution but rather a starter kit for developers working on a similar solution.

## Requirements

- PHP 7.4 or above
-  ⚠️ Secure credentials file (instructions for NGINX provided below)

## Setup Instructions

### 1. Create a Google Sheet

- Create a Google Sheet with the following headers:

order date | order id | order total | total fees | total shipping tax | shipping total | order subtotal | total discount

- In the Google Sheet URL, locate and save the sheet ID, which looks something like this:
![image1](https://github.com/Babylon1999/woocommerce-google-sheets-integration/assets/67080558/315193ff-a3c4-4af9-a03e-7ead1b708a3b)

### 2. Clone the Repository

- Clone the repository locally and navigate to the project directory.
- Run `composer install` to install the Google API library. By default version 2.0.3 will be installed because it's compatible with PHP 7.4, if you're using PHP 8.1 or above, feel free to install another version.

### 3. Configure the Plugin

- Open the main plugin file.
- Replace `'your_sheet_id'` with the sheet ID you saved from the Sheet URL.
- Update the `$sheet_name` if needed, which you can find at the bottom of your Google Sheet.

- ![image](https://github.com/Babylon1999/woocommerce-google-sheets-integration/assets/67080558/72b655f1-4d90-4ccb-9641-b7a426da6fb9)


### 4. Authenticate Using a Service Account

Authenticating with Google services can be a bit of a maze, luckily there are plenty of online resources to guide you through the process. Here's a quick summary:

- Enable the Google Sheets API from your Google Cloud project.
- Navigate to **Credentials** -> **Create Service Account**.

![Service Account Creation](https://github.com/Babylon1999/woocommerce-google-sheets-integration/assets/67080558/63cdd272-69fe-4342-ae66-94b44ab021f0)

- Download a JSON key file and save it as `creds.json` in the plugin directory.

- ![image](https://github.com/Babylon1999/woocommerce-google-sheets-integration/assets/67080558/40d52cb2-2940-4a75-8df2-29496a6373f9)


### 5. Secure the Credentials File (Crucial Step)

 The JSON file containing your service account credentials holds the keys to your account. If someone gains access to it, they essentially have control over your account. Therefore, it's paramount to ensure this file can't be accessed directly.

#### Apache Server

If you're using Apache, you don't have to worry about this too much, I already added a `.htaccess` file in place to prevent all access to the plugin files.

#### NGINX Server

> [!IMPORTANT]
> With NGINX, securing this file requires additional steps, otherwise, anyone can access the file.

![NGINX Vulnerability](https://github.com/Babylon1999/woocommerce-google-sheets-integration/assets/67080558/30e8e92c-f0f9-4e77-bb47-3ae4732458eb)


To address this, add this to your config file and restart the server. Make sure to try and access the file after doing so to make sure it's working as expected.

```nginx
	location /wp-content/plugins/woocommerce-google-sheets-integration/ {
    deny all;
    return 404;
}

```
