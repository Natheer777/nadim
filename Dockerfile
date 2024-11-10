# استخدام صورة Apache وPHP الأساسية
FROM php:apache

# تثبيت مكتبة mysqli
RUN docker-php-ext-install mysqli

# نسخ ملفات التطبيق إلى مجلد html داخل الحاوية
COPY . /var/www/html/

# ضبط Apache لاستخدام login.php كملف رئيسي
RUN echo "DirectoryIndex login.php" >> /etc/apache2/apache2.conf

# ضبط أذونات المجلد
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
