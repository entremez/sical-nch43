# 🧰 Configuración completa del entorno de desarrollo

Este proyecto está preparado para funcionar con **WSL (Ubuntu) + Docker + Laravel + Node.js**.  
A continuación se detallan los pasos para replicar el entorno en cualquier equipo nuevo desde cero.

---

## 🐧 Instalación base (una sola vez por equipo)

### 1. Instalar WSL con Ubuntu

En PowerShell (como administrador):

```powershell
wsl --install
```

> Reiniciar si lo solicita. Luego abrir “Ubuntu” desde el menú inicio y crear usuario Linux.

---

### 2. Instalar Docker Desktop

- Descargar desde: [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)
- Durante la instalación:
  - ✅ Activar opción **"Usar WSL 2 como backend"**
- Luego en Docker Desktop:
  - Ir a **Settings → Resources → WSL Integration**
  - ✅ Habilitar Ubuntu como distribución activa

---

### 3. Instalar herramientas base en Ubuntu (WSL)

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y git curl unzip software-properties-common ca-certificates lsb-release apt-transport-https build-essential
```

---

### 4. Instalar PHP 8.2 + extensiones necesarias para Laravel

```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-mysql php8.2-zip php8.2-gd unzip
```

---

### 5. Instalar Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

---

### 6. Instalar Node.js (versión 18 recomendada)

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 🚀 Clonar y preparar el proyecto

```bash
cd ~
mkdir -p projects
cd projects
git clone https://github.com/entremez/sical-nch43.git
cd sical-nch43
```

---

### Instalar dependencias del proyecto Laravel

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

---

## 🐳 Comandos de desarrollo

### Iniciar contenedores

```bash
sudo usermod -aG docker $USER
Run newgrp docker in your terminal session
docker compose up -d
```

> Usar `--build` solo la primera vez o si se cambió el Dockerfile.

### Detener contenedores

```bash
docker compose down
```

---

### Artisan dentro del contenedor

```bash
docker exec -it laravel_app bash
php artisan migrate
php artisan route:list
```

---

### Compilar assets desde Ubuntu (WSL)

```bash
npm run dev
```

---
```bash
sudo chmod -R ug+rw storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```


## ⚡ Script automático (opcional)

Podés crear un archivo llamado `start-dev.bat` en Windows con este contenido para automatizar todo:

```bat
@echo off
set PROJECT_PATH=~/projects/sical-nch43

echo 🔁 Iniciando entorno de desarrollo en WSL (Ubuntu)...
wsl -d Ubuntu -- bash -ic "cd %PROJECT_PATH% && docker compose up -d"
wsl -d Ubuntu -- bash -ic "cd %PROJECT_PATH% && code ."
start "npm run dev" wsl -d Ubuntu -- bash -ic "cd %PROJECT_PATH% && npm run dev"
pause
```

Este script:

- ✅ Levanta los contenedores con Docker
- ✅ Abre VS Code dentro de WSL en la carpeta del proyecto
- ✅ Lanza `npm run dev` en una nueva consola para monitorear assets

---

Con estos pasos podés configurar el entorno de desarrollo completo en cualquier computadora compatible con WSL 2 y Docker Desktop.

Para entrar a phpmyadmin http://localhost:8080/

\\wsl$

php artisan storage:link


