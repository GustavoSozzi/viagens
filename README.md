# Sistema de Gerenciamento de Viagens

Sistema para gerenciar motoristas, veículos e viagens com Laravel + React.

## 🚀 Como Rodar

### Backend
```bash
composer install
cp .env.example .env
php artisan key:generate
docker-compose up -d
php artisan migrate --seed
php artisan serve
```

### Queue Worker (obrigatório)
```bash
php artisan queue:work --queue=deleteTrips,default
```

### Reverb WebSocket (obrigatório)
```bash
php artisan reverb:start
```

### Frontend
```bash
cd frontend
npm install
npm run dev
```

## �️ Stack
Laravel 11, PostgreSQL 14, React 19, Reverb WebSocket
