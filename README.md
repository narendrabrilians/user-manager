# User Manager

A simple PHP & MySQL web project for learning purposes only.

⚠️ **Not secure for production use.**

## ✨ Features

- User Registration, Login, and Logout
- Users can:
  - Upload posts with **title**, **description**, and **image**
  - Edit and delete their own posts
- Admins can:
  - Modify user roles
  - Add new users
  - Edit existing user data
  - Delete users

## ⚙️ How to Run

1. **Place Project in XAMPP htdocs**  
   Move this folder into `htdocs`, for example: `C:\xampp\htdocs\user-manager`

2. **Start Apache and MySQL**  
   Open XAMPP Control Panel and start **Apache** and **MySQL**.

3. **Run SQL Commands**  
   Run the commands in the `db.sql` file.

4. **Create `uploads/` Folder**  
   In the root of the project, create a folder named `uploads/`.
   
   Make sure it has proper write permissions.

5. **Open the Website**  
   Go to: `http://localhost/user-manager`

## 📝 Notes

- If your MySQL root user has a password, update it in `koneksi.php`.