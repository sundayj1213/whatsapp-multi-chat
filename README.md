
# WhatsApp Multi Client Dashboard

Access and Chat from Multiple WhatsApp numbers on one dashboard
## Run Locally

Clone the project

```bash
  git clone https://github.com/sundayj1213/whatsapp-multi-chat
```

Go to the project directory

```bash
  cd whatsapp-multi-chat
```
Install Composer dependencies

```bash
  composer install
```

Migrate, Seed and Start Server

```bash
  php artisan migrate:fresh --seed && php artisan serve
```

Install NPM dependencies

```bash
  npm install
```

Start the Socket.IO/Express server

```bash
  node server
```


## Running Tests

To run tests, run the following command

```bash
  npm run test
```


## Tech Stack

**Client:** VueJs, TailwindCSS, Socket.IO

**Server:** Node, Express, Socket.IO

**Framework:** Laravel


## Authors

- [@Sunday Johnson](https://www.upwork.com/freelancers/~019fb991cf334b5944)


## Support

For support, email dev@yoodule.com
