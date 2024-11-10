# استخدام صورة Apache وPHP الأساسية
FROM php:apache

# تثبيت مكتبة mysqli
RUN docker-php-ext-install mysqli

# نسخ ملفات التطبيق إلى مجلد html داخل الحاوية
COPY . /var/www/html/

# ضبط Apache لاستخدام login.php كصفحة رئيسية مع الحفاظ على إمكانية الوصول إلى بقية الصفحات
RUN echo "DirectoryIndex login.php" >> /etc/apache2/apache2.conf

# تفعيل mod_rewrite لدعم الروابط النظيفة
RUN a2enmod rewrite

# ضبط أذونات المجلد
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# ضبط ServerName لتجنب رسالة التحذير
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
