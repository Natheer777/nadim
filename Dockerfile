FROM php:apache

# تثبيت git لتنزيل المشروع
RUN apt-get update && apt-get install -y git && rm -rf /var/lib/apt/lists/*

# تثبيت مكتبة mysqli
RUN docker-php-ext-install mysqli

# تنزيل المشروع من GitHub
RUN git clone https://github.com/Natheer777/nadim.git 

# ضبط الأذونات
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# تعيين الصفحة الرئيسية في Apache
# RUN echo "DirectoryIndex DirectoryIndex.php" >> 

ENV DB_SERVERNAME=bcc63l03xd1znxj153qc-mysql.services.clever-cloud.com
ENV DB_USERNAME=ur2crl9wmg5jemi9
ENV DB_PASSWORD=h7kGTQvuNYzVBDxaxOxH
ENV DB_NAME=bcc63l03xd1znxj153qc


# تشغيل Apache في الوضع الأمامي
CMD ["apache2-foreground"]
