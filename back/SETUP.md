# Mang Inasal Backend Setup Guide

## Database Structure

Your application now has the following tables synchronized with Supabase:

### 1. **Categories Table**
- `id` (Primary Key)
- `name` (Unique)
- `slug` (Unique) 
- `description` (Optional)
- `order` (Display order)
- `timestamps`

### 2. **Menu Items Table**
- `id` (Primary Key)
- `category_id` (Foreign Key to categories)
- `name`
- `description` (Optional)
- `base_price` (Decimal)
- `order` (Display order)
- `is_active` (Boolean)
- `timestamps`

### 3. **Menu Variants Table**
- `id` (Primary Key)
- `menu_item_id` (Foreign Key to menu_items)
- `name` (e.g., "Small", "With Drink", "Family Size")
- `description` (Optional)
- `price` (Decimal)
- `order` (Display order)
- `is_active` (Boolean)
- `timestamps`

## Data Included

The application includes **ALL** Mang Inasal menu data from the PDF:

- ✅ Breakfast Menu (3 items)
- ✅ Chicken Menu (27 items)
- ✅ Family Fiesta Menu (11 items)
- ✅ Buddy Fiesta Menu (11 items)
- ✅ Solo Fiesta Menu (9 items)
- ✅ Halo-Halo Menu (4 items with variants)
- ✅ Palabok Menu (6 items)
- ✅ Grilled Liempo Menu (4 items)
- ✅ Pork BBQ Menu (5 items)
- ✅ Lumpiang Togue Menu (2 items)
- ✅ Sisig Menu (7 items)
- ✅ Drinks Menu (7 items with size variants)
- ✅ Extras Menu (8 items)

## API Endpoints

### Categories
- `GET /api/categories/` - Get all categories with items
- `POST /api/categories/` - Create new category
- `GET /api/categories/{id}` - Get specific category
- `PUT /api/categories/{id}` - Update category
- `DELETE /api/categories/{id}` - Delete category

### Menu Items
- `GET /api/menu-items/` - Get all active menu items
- `POST /api/menu-items/` - Create new menu item
- `GET /api/menu-items/{id}` - Get specific menu item
- `PUT /api/menu-items/{id}` - Update menu item
- `DELETE /api/menu-items/{id}` - Delete menu item
- `GET /api/menu-items/search?q=keyword` - Search menu items
- `GET /api/menu-items/category/{categoryId}` - Get items by category

### Menu Variants
- `GET /api/menu-items/{menuItemId}/variants/` - Get all variants for item
- `POST /api/menu-items/{menuItemId}/variants/` - Create variant
- `GET /api/menu-items/{menuItemId}/variants/{id}` - Get specific variant
- `PUT /api/menu-items/{menuItemId}/variants/{id}` - Update variant
- `DELETE /api/menu-items/{menuItemId}/variants/{id}` - Delete variant

## Running the Setup

### Step 1: Fix Network Connection

Before running migrations, ensure you have internet access:

```bash
# Test internet connectivity
ping google.com

# Test Supabase connection
ping db.iaprdpxrzlpdttuhecug.supabase.co
```

**If DNS resolution fails**, you may need to:
- Check your network connection
- Verify Supabase project is active
- Contact your ISP if DNS is blocked
- Use a VPN if your network restricts external access

### Step 2: Install Dependencies (if not already done)

```bash
composer install
```

### Step 3: Run Database Migrations

```bash
php artisan migrate
```

This will create:
- `categories` table
- `menu_items` table
- `menu_variants` table

### Step 4: Seed the Database with Menu Data

```bash
php artisan db:seed --class=MenuSeeder
```

Or seed everything at once:

```bash
php artisan migrate:fresh --seed
```

### Step 5: Verify Data in Supabase

Go to your Supabase dashboard:
1. Navigate to your project
2. Go to SQL Editor
3. Run: `SELECT COUNT(*) FROM categories;` (should return 13)
4. Run: `SELECT COUNT(*) FROM menu_items;` (should return 140+)
5. Run: `SELECT COUNT(*) FROM menu_variants;` (should return 200+)

## Testing the API

### Get All Categories with Items

```bash
curl http://localhost:8000/api/categories/
```

### Get Menu Items by Category

```bash
curl "http://localhost:8000/api/menu-items/category/1"
```

### Search Menu Items

```bash
curl "http://localhost:8000/api/menu-items/search?q=Chicken"
```

### Get Specific Item with Variants

```bash
curl http://localhost:8000/api/menu-items/1
```

## Real-Time Synchronization

All changes to menu items, variants, and categories are automatically persisted to Supabase:

- Create, Update, Delete operations immediately write to PostgreSQL
- Supabase provides real-time capabilities via WebSockets
- All prices and data are synced in real-time

## Models Overview

### Category Model
```php
$category->menuItems(); // Get all menu items in category
```

### MenuItem Model
```php
$item->category; // Get parent category
$item->variants; // Get all variants (sizes, with/without drink, etc.)
```

### MenuVariant Model
```php
$variant->menuItem; // Get parent menu item
```

## Notes

- All prices are stored as DECIMAL(10,2) for accurate currency handling
- Menu items have an `is_active` flag to easily disable items
- Order fields allow custom sorting in the frontend
- SSL mode is enabled for secure Supabase connection
- Timestamps are automatically managed by Laravel

## Troubleshooting

### Connection Refused Error
- Check internet connectivity: `ping google.com`
- Verify Supabase credentials in `.env`
- Ensure VPN is not blocking the connection

### Migration Failed
- Run: `php artisan migrate:rollback` 
- Check `.env` file for correct credentials
- Ensure database user has CREATE TABLE permissions

### Data Not Showing
- Verify seeder ran: `php artisan db:seed --class=MenuSeeder`
- Check Supabase dashboard for tables existence
- Verify `is_active` field is true for items

## Environment Variables

Key variables in `.env`:

```
DB_CONNECTION=pgsql
DB_HOST=db.iaprdpxrzlpdttuhecug.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=HONDA_NSXR100
DB_SSLMODE=require
SUPABASE_URL=https://iaprdpxrzlpdttuhecug.supabase.co
```

All data is secure and encrypted in transit over SSL.
