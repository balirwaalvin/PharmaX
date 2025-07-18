# DigitalOcean Database Setup Guide

## Quick Import via DigitalOcean Console (Recommended)

### Step 1: Access Your Database
1. Go to DigitalOcean Control Panel → Databases
2. Click on your MySQL database cluster
3. Go to "Users & Databases" tab
4. Note your database name (usually `defaultdb`)

### Step 2: Import via Console
1. Click "Console" tab in your database dashboard
2. You'll get a web-based MySQL console
3. Run this command to create the database:
```sql
CREATE DATABASE IF NOT EXISTS PharmacyX_DB;
USE PharmacyX_DB;
```

### Step 3: Import the Schema
Copy and paste the entire contents of `Database/PharmacyX_DB.sql` into the console, but **modify these lines first**:

**Remove or comment out these lines at the top:**
```sql
-- Comment out or remove these lines:
-- CREATE DATABASE PharmacyX_DB;
-- USE PharmacyX_DB;
```

**The import should start with:**
```sql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
-- ... rest of the file
```

## Alternative: MySQL Command Line Import

### Step 1: Get Connection Details
From your DigitalOcean database dashboard, copy:
- Host
- Port (usually 25060)
- Username
- Password
- Database name

### Step 2: Connect and Import
```bash
# Download your database SSL certificate first
wget https://raw.githubusercontent.com/digitalocean/certificate-authority/master/certs/ca-certificate.crt

# Connect and import
mysql -h your-host -P 25060 -u your-username -p --ssl-ca=ca-certificate.crt your-database-name < Database/PharmacyX_DB.sql
```

## Troubleshooting Common Issues

### Issue 1: "Database doesn't exist"
**Solution:** Create the database first:
```sql
CREATE DATABASE IF NOT EXISTS PharmacyX_DB;
USE PharmacyX_DB;
```

### Issue 2: "Table already exists"
**Solution:** Drop existing tables first:
```sql
DROP DATABASE IF EXISTS PharmacyX_DB;
CREATE DATABASE PharmacyX_DB;
USE PharmacyX_DB;
```

### Issue 3: Foreign Key Constraints
**Solution:** Disable foreign key checks during import:
```sql
SET FOREIGN_KEY_CHECKS = 0;
-- Import your tables here
SET FOREIGN_KEY_CHECKS = 1;
```

### Issue 4: Character Set Problems
**Solution:** Set proper character set:
```sql
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
```

## Modified Import Script

Here's a complete import script you can copy-paste:

```sql
-- Disable foreign key checks and set character encoding
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Drop existing database and recreate (optional, use if you want clean import)
-- DROP DATABASE IF EXISTS PharmacyX_DB;
-- CREATE DATABASE PharmacyX_DB;
-- USE PharmacyX_DB;

START TRANSACTION;
SET time_zone = "+00:00";

-- Now paste the rest of your PharmacyX_DB.sql content here
-- (everything after the initial database creation commands)

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
```

## Verification Steps

After importing, verify your database:

### 1. Check Tables
```sql
SHOW TABLES;
```

Should show:
- Messages
- Orders  
- Payment
- Products
- User_info

### 2. Check Sample Data
```sql
SELECT COUNT(*) FROM User_info;
SELECT COUNT(*) FROM Products;
SELECT COUNT(*) FROM Orders;
```

### 3. Test App Connection
Visit your deployed app at: `https://your-app-name.ondigitalocean.app/db_test.php`

This will test the database connection and show table information.

## Environment Variables

Make sure your DigitalOcean app has these environment variables set:

```
DATABASE_URL=mysql://username:password@host:port/database_name?ssl-mode=REQUIRED
DB_HOST=your-host
DB_USER=your-username  
DB_PASS=your-password
DB_NAME=your-database-name
DB_PORT=25060
```

## Post-Import Cleanup

1. **Remove test file**: Delete `db_test.php` after verification for security
2. **Update admin credentials**: Change default admin passwords in User_info table
3. **Clean sample data**: Remove test orders/messages if needed

## Next Steps

1. Import the database using one of the methods above
2. Verify the import worked by checking `/db_test.php`
3. Test user registration and login
4. Test product ordering functionality
5. Remove `db_test.php` file for security

Need help? Check the app logs in DigitalOcean → Apps → Your App → Runtime Logs
