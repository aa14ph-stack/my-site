FROM php:8.2-apache

# تثبيت وتفعيل إضافة ميزا الماي سيكول داخل الدوكر عشان الكود يشتغل
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# نسخ كل ملفات مشروعك إلى مجلد السيرفر الداخلي
COPY . /var/www/html/

# تفعيل مود إعادة الكتابة للمسارات لحمايتها
RUN a2enmod rewrite

EXPOSE 80
