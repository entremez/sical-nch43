@echo off
set PROJECT_PATH=~/projects/sical-nch43

echo 🔁 Iniciando entorno de desarrollo en WSL (Ubuntu)...

REM 👉 Levantar contenedores
wsl -d Ubuntu -- bash -ic "cd %PROJECT_PATH% && echo ✅ Carpeta encontrada && docker compose up -d"

REM 👉 Abrir VS Code dentro de WSL en la carpeta correcta
wsl -d Ubuntu -- bash -ic "cd %PROJECT_PATH% && code ."

REM 👉 Abrir nueva terminal con npm run dev en foreground
start "npm run dev" wsl -d Ubuntu -- bash -ic "cd %PROJECT_PATH% && npm run dev"

echo 🧩 Entorno listo. Podés cerrar esta ventana o dejarla abierta.
pause


