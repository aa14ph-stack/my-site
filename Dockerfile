# 1. استخدام نسخة PHP الرسمية المدمج معها سيرفر Apache (مبنية على Debian)
FROM php:8.2-apache

# 2. تفعيل مود الـ Rewrite في الأباتشي (مهم جداً للـ Routing والروابط المخصصة)
RUN a2enmod rewrite

# 3. تحديد الفولدر الرئيسي للشغل جوه السيرفر
WORKDIR /var/www/html

# 4. نسخ كل ملفات مشروعك من الجيت هاب إلى فولدر السيرفر الداخلي
COPY . /var/www/html/

# 5. حل مشكلة 403: إعطاء صلاحيات القراءة والتشغيل للمستخدم (www-data) الخاص بالأباتشي
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 6. فتح بورت 80 اللي بيشتغل عليه السيرفر
EXPOSE 80

# 7. أمر تشغيل الأباتشي في الخلفية بشكل دائم
CMD ["apache2-foreground"]
