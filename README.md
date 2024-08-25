# Hybrid-Rendering Web Project

This web project utilizes a **hybrid-rendering** approach, combining both **Client-Side Rendering (CSR)** and **Server-Side Rendering (SSR)** techniques.

## Technologies Used

- **Client-Side Rendering (CSR):**

  - **HTML**
  - **CSS**
  - **JavaScript**
    - Implements **Client-Side Routing** techniques to enhance user experience by enabling seamless navigation without full page reloads.

- **Server-Side Rendering (SSR):**
  - **PHP**

## Features

- **Password Reset with OTP Verification:**
  - Users can reset their passwords by receiving a One-Time Password (OTP) via email.
  - Utilizes the **PHPMailer** library to handle email sending functionality.

## Database Configuration

- **Database Management System:** MySQL
- **Database Name:** `php_test`
- **Table Structure:**
  - **Table Name:** `php`
  - **Columns:**
    - `username` (VARCHAR): Stores the user's username.
    - `password` (VARCHAR): Stores the user's password.
    - `otp` (VARCHAR): Stores the One-Time Password for verification.
    - `expireOTP` (DATETIME): Stores the expiration time of the OTP.
