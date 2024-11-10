FROM php:apache

# تثبيت git لتنزيل المشروع
RUN apt-get update && apt-get install -y git && rm -rf /var/lib/apt/lists/*

# تثبيت مكتبة mysqli
RUN docker-php-ext-install mysqli

# تنزيل المشروع من GitHub
RUN git clone https://github.com/Natheer777/nadim.git /var/www/html

# ضبط الأذونات
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# تعيين الصفحة الرئيسية في Apache
RUN echo "DirectoryIndex login.php" >> /etc/apache2/apache2.conf
