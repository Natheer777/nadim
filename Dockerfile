

# اختر صورة PHP الرسمية
FROM php:8.0-apache

# نسخ ملفات المشروع إلى داخل الحاوية
COPY . /var/www/html/

# تمكين mod_rewrite الخاص بـ Apache (إذا كنت بحاجة إلى إعادة كتابة الروابط)
RUN a2enmod rewrite

# إعدادات قاعدة بيانات، إذا كنت بحاجة إلى تهيئة بيئة
ENV DB_SERVERNAME=bcc63l03xd1znxj153qc-mysql.services.clever-cloud.com
ENV DB_USERNAME=ur2crl9wmg5jemi9
ENV DB_PASSWORD=h7kGTQvuNYzVBDxaxOxH
ENV DB_NAME=bcc63l03xd1znxj153qc

# تعيين المنفذ الذي يستمع عليه الخادم
EXPOSE 80
