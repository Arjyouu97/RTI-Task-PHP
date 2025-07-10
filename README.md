# RTI Task Manager - PHP CRUD Application

## Overview
A simple yet powerful task management system built with core PHP that demonstrates:
- CRUD operations (Create, Read, Update, Delete)
- AJAX-based interactions
- DataTables with server-side processing
- Bootstrap modals for all actions
- Form validation (both client and server-side)

## Features
✅ Full CRUD functionality  
✅ AJAX-powered interface (no page reloads)  
✅ DataTables with search, sort, and pagination  
✅ Modal forms for add/edit/delete actions  
✅ Server-side validation  
✅ Responsive Bootstrap design  
✅ Clean, interview-ready code structure  

## Requirements
- PHP 7.4+ 
- MySQL 5.7+
- Web server (Apache/Nginx)


## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/Arjyouu97/RTI-Task-PHP.git
   cd RTI-Task-PHP
   ```

2. Set up database:
   - Create a MySQL database
   - Import `database.sql` or let the app create tables automatically

3. Configure database connection:
   Edit `includes/db.php` with your credentials:
   ```php
   $host = 'localhost';
   $dbname = 'task_manager';
   $user = 'username';
   $pass = 'password';
   ```

4. Launch application:
   - Point your web server to the project root
   - Access via `http://localhost/RTI-Task-PHP/`

## Key Files
```
/includes/
  config.php - Main configuration
  db.php - Database connection
  functions.php - Helper functions
/modals/
  create.php - Add task modal
  edit.php - Edit task modal  
  delete.php - Delete confirmation
/partials/
  actions.php - Action buttons
index.php - Main interface
ajax.php - AJAX endpoint
process.php - Form processor
```

## Technical Highlights
- **Modern PHP** without frameworks
- **Prepared statements** for security
- **Server-side processing** for DataTables
- **Comprehensive validation** (title required, date validation etc.)
- **Clean separation** of concerns
- **Interview-ready** code quality

## Customization
- Change records per page in `ajax.php`:
  ```php
  $length = isset($_GET['length']) ? (int)$_GET['length'] : 5;
  ```
- Modify status options in `modals/create.php` and `modals/edit.php`

## Troubleshooting
- **Database errors**: Verify credentials in `db.php`
- **AJAX issues**: Check browser console for errors
- **Validation problems**: Review `process.php` validation rules

