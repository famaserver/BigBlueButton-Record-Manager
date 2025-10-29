# famabutton

سیستم مدیریت پنل سرورهای BigBlueButton

## مالکیت
- **مالک**: famaserver
- **نویسنده**: famaserver  
- **کپی‌رایت**: © 2025 famaserver - کلیه حقوق محفوظ است

## ویژگی‌ها
- داشبورد مدیریت سرورها
- افزودن و حذف سرورهای BigBlueButton
- مدیریت درخواست‌های API
- سیستم ورود و خروج
- رابط کاربری RTL

## نصب

### پیش‌نیازها
- PHP 7.4 یا بالاتر
- MySQL
- XAMPP یا هر سرور وب که PHP را پشتیبانی کند

### راه‌اندازی

1. کلون کردن مخزن:
```bash
git clone https://github.com/famaserver/famabutton.git
cd famabutton
```

2. ایجاد پایگاه داده:
```sql
CREATE DATABASE bigbluebutton_db;
```

3. ایجاد جدول‌ها:
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE servers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  url VARCHAR(255) NOT NULL,
  secret VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

4. ویرایش تنظیمات:
فایل `db.php` را ویرایش کرده و اطلاعات پایگاه داده خود را وارد کنید.

5. ایجاد کاربر:
```sql
INSERT INTO users (username, email, password) VALUES 
('famaserver', 'admin@example.com', 'famaserver');
```

6. اجرا:
در مرورگر به `http://localhost/famabutton/` بروید

## ساختار پروژه
```
famabutton/
├── assets/          # منابع استاتیک
├── index.php        # داشبورد اصلی
├── servers.php      # لیست سرورها
├── add_server.php   # افزودن سرور
├── view_record.php  # نمایش وضعیت
├── login.php        # صفحه ورود
├── auth.php         # احراز هویت
├── logout.php       # خروج از سیستم
└── db.php           # تنظیمات پایگاه داده
```

## مجوز
کلیه حقوق برای famaserver محفوظ است. برای جزئیات بیشتر به فایل LICENSE مراجعه کنید.

## ارتباط
- وب‌سایت: [famaserver.com](https://famaserver.com)
- آدرس مقاله اصلی: [ گام برای مدیریت ویدیوهای ضبط شده در BigBlueButton](https://famaserver.com/5-steps-to-manage-recorded-videos-in-bigbluebutton/)

=======
# BigBlueButton-Record-Handler
BigBlueButton-Record-Handler is a tool for managing and organizing BigBlueButton server recordings. It allows administrators to easily track, retrieve, and manage meeting archives. With automated processes and data handling capabilities, this project simplifies server management and enhances the accessibility of recorded sessions.
# اسکریپت مدیریت سرور BigBlueButton

این اسکریپت PHP به زبان PHP 7.4 و MySQL نوشته شده است و برای مدیریت سرور BigBlueButton طراحی شده است. هدف این اسکریپت لیست کردن ویدیوهای منتشر شده و منتشر نشده، همچنین مدیریت ویدیوها از جمله بازسازی و حذف ویدیوها است. علاوه بر این، اطلاعات میتینگ (Meeting ID) و آیدی داخلی (Internal ID) نمایش داده می‌شود. این اسکریپت محدودیت ندارد و می‌تواند به راحتی از طریق URL و سکرت به سرور BigBlueButton متصل شود.

### امکانات:
- لیست ویدیوهای منتشر شده و منتشر نشده
- امکان بازسازی و حذف ویدیوها
- نمایش Meeting ID و Internal ID
- اتصال به سرور BigBlueButton از طریق URL و سکرت
- پیش‌فرض نام کاربری: `famaserver`
- پیش‌فرض کلمه عبور: `famaserver`

### اسپانسر
این پروژه توسط [سرور مجازی فاماسرور](https://famaserver.com/vps/) اسپانسر شده است.
منبع اصلی

---

### English

# BigBlueButton Server Management Script

This PHP script is written in PHP 7.4 and MySQL and is designed for managing BigBlueButton servers. The purpose of this script is to list both published and unpublished videos, as well as manage videos including rebuilding and deleting them. Additionally, it displays the Meeting ID and Internal ID. This script is unlimited and can easily connect to a BigBlueButton server via URL and secret.

### Features:
- List published and unpublished videos
- Rebuild and delete videos
- Display Meeting ID and Internal ID
- Connect to BigBlueButton server via URL and secret
- Default username: `famaserver`
- Default password: `famaserver`

### Sponsor
This project is sponsored by [FamaServer](https://famaserver.com/vps/).
>>>>>>> 7873a28b13e6832a445cbe325032b69f3a7f63c8
=======
# BigBlueButton-Record-Manager
BigBlueButton-Record-Handler

BigBlueButton-Record-Handler is a tool for managing and organizing BigBlueButton server recordings. It allows administrators to easily track, retrieve, and manage meeting archives. With automated processes and data handling capabilities, this project simplifies server management and enhances the accessibility of recorded sessions.
اسکریپت مدیریت سرور BigBlueButton

این اسکریپت PHP به زبان PHP 7.4 و MySQL نوشته شده است و برای مدیریت سرور BigBlueButton طراحی شده است. هدف این اسکریپت لیست کردن ویدیوهای منتشر شده و منتشر نشده، همچنین مدیریت ویدیوها از جمله بازسازی و حذف ویدیوها است. علاوه بر این، اطلاعات میتینگ (Meeting ID) و آیدی داخلی (Internal ID) نمایش داده می‌شود. این اسکریپت محدودیت ندارد و می‌تواند به راحتی از طریق URL و سکرت به سرور BigBlueButton متصل شود.
امکانات:

    لیست ویدیوهای منتشر شده و منتشر نشده
    امکان بازسازی و حذف ویدیوها
    نمایش Meeting ID و Internal ID
    اتصال به سرور BigBlueButton از طریق URL و سکرت
    پیش‌فرض نام کاربری: famaserver
    پیش‌فرض کلمه عبور: famaserver

اسپانسر

این پروژه توسط فاماسرور اسپانسر شده است.
English
BigBlueButton Server Management Script

This PHP script is written in PHP 7.4 and MySQL and is designed for managing BigBlueButton servers. The purpose of this script is to list both published and unpublished videos, as well as manage videos including rebuilding and deleting them. Additionally, it displays the Meeting ID and Internal ID. This script is unlimited and can easily connect to a BigBlueButton server via URL and secret.
Features:

    List published and unpublished videos
    Rebuild and delete videos
    Display Meeting ID and Internal ID
    Connect to BigBlueButton server via URL and secret
    Default username: famaserver
    Default password: famaserver

Sponsor

This project is sponsored by FamaServer.
>>>>>>> ee9452b64c3be894a14996ee3cfd9b6ce5a2aa4e
