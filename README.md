# Sistema de Gerenciamento de Viagens

Sistema para gerenciar motoristas, veículos e viagens com Laravel + React.

### Backend
```bash
composer install
cp .env.example .env
php artisan key:generate
docker-compose up -d
php artisan migrate --seed
php artisan serve
```
API: http://localhost:8000

### Frontend
```bash
cd frontend
npm install
npm run dev
```
App: http://localhost:5173

## 🗄️ Banco de Dados

**Comandos úteis:**
```bash
docker-compose logs postgres          # Ver logs
docker-compose down                   # Parar container
php artisan migrate:fresh --seed      # Resetar banco
```

## 📋 Endpoints

- `/api/motoristas` - CRUD de motoristas
- `/api/veiculos` - CRUD de veículos
- `/api/viagens` - CRUD de viagens

## 🛠️ Stack

**Backend:** Laravel 11, PostgreSQL 14, Docker  
**Frontend:** React 19, Vite, Axios, React Router
