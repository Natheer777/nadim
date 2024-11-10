# استخدام صورة PHP الأساسية مع خادم Apache
FROM php:8.0-apache

# نسخ ملفات المشروع إلى مجلد العمل في الحاوية
COPY . /var/www/html/

# تثبيت Composer إذا كان التطبيق يستخدمه (اختياري)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# تغيير أذونات المجلد الرئيسي (اختياري)
RUN chown -R www-data:www-data /var/www/html
