*Password Akun:
Superadmin (Dapat CRUD Map dan User)
username: superadmin@roles.id
password: 123456

Admin (Dapat CRUD Map)
username: admin@roles.id
password: 123456

Superadmin (Dapat menambah Transaksi dan melihat fitur lain)
username: user@roles.id
password: 123456

*Dalam menjalankan kode perlu menjalankan beberapa command berikut pada terminal

composer install

npm install

*Perlu Melakukan Migrasi Database dengan Melakukan Command Berikut Pada Terminal

php artisan migrate:fresh --seed

jangan lupa menyesuaikan .env pada file kode (bila tidak ada .env maka ubahlah .env.example menjadi .env)

*Dalam menjalankan kodingan, perlu melakukan beberapa command berikut pada terminal

php artisan serve--port=8080

*Hostingan sistem ini menggunakan NGROK maka harus melakukan instalasi ngrok pada device Anda, dan setelah melakukan instalasi maka dapat menjalankan perintah berikut pada terminal

ngrok http 8080