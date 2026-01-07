# API Festivales Chile 2026

API REST desarrollada en Laravel 9 para consultar la programación y competencias de los principales festivales de música de Chile: Festival de Viña del Mar 2026 y Festival del Huaso de Olmué 2026.

## 🎵 Características

- **Autenticación JWT** (JSON Web Tokens)
- **Festival de Viña del Mar 2026**: Acceso protegido con JWT
- **Festival del Huaso de Olmué 2026**: Acceso protegido con JWT
- **Parrilla completa** de artistas por día
- **Competencias Folclóricas e Internacionales**
- **Respuestas en formato JSON**

## 📋 Requisitos

- PHP >= 8.0.2
- Composer
- Laravel 9.52
- MySQL/MariaDB
- JWT Auth (php-open-source-saver/jwt-auth)

## 🚀 Instalación

1. Clonar el repositorio:
```bash
git clone <repository-url>
cd api-vina-2026-laravel
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar el archivo `.env`:
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Configurar la base de datos en `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

5. Ejecutar migraciones y seeders:
```bash
php artisan migrate
php artisan db:seed
```

6. Iniciar el servidor:
```bash
php artisan serve
```

La API estará disponible en: `http://localhost:8000`

## 🔐 Autenticación

### Login
Obtener token JWT para acceder a rutas protegidas.

**Endpoint:** `POST /api/auth/login`

**Request Body:**
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

### Obtener Usuario Autenticado
**Endpoint:** `GET /api/auth/me`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "email_verified_at": null,
    "created_at": "2026-01-06T00:00:00.000000Z",
    "updated_at": "2026-01-06T00:00:00.000000Z"
}
```

### Logout
**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Successfully logged out"
}
```

### Refrescar Token
**Endpoint:** `POST /api/auth/refresh`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

## 🎭 Festival de Viña del Mar 2026

### 📌 Rutas Públicas (No requieren autenticación)

#### Obtener Parrilla Completa
Devuelve toda la información del festival, incluyendo programación, competencias y animadores.

**Endpoint:** `GET /api/vina-2026/parrilla`

**Response:**
```json
{
    "success": true,
    "data": {
        "nombre": "Festival Internacional de la Canción de Viña del Mar",
        "edicion": "LXV (65ª)",
        "animadores": ["Karen Doggenweiler", "Rafael Araneda"],
        "programacion": [
            {
                "dia": "Domingo",
                "fecha": "2026-02-22",
                "artistas": [
                    {"nombre": "Gloria Estefan", "tipo": "Música"},
                    {"nombre": "Matteo Bocelli", "tipo": "Música"},
                    {"nombre": "Stefan Kramer", "tipo": "Humor"}
                ]
            }
            // ... más días
        ],
        "competencia_folclorica": [...],
        "competencia_internacional": [...]
    }
}
```

#### Obtener Programación por Día
Obtiene la programación de un día específico.

**Endpoint:** `GET /api/vina-2026/dia/{dia}`

**Parámetros:**
- `{dia}`: Nombre del día (domingo, lunes, martes, miércoles, jueves, viernes)

**Ejemplo:** `GET /api/vina-2026/dia/lunes`

**Response:**
```json
{
    "success": true,
    "data": {
        "dia": "Lunes",
        "fecha": "2026-02-23",
        "artistas": [
            {"nombre": "Pet Shop Boys", "tipo": "Música"},
            {"nombre": "Bomba Estéreo", "tipo": "Música"},
            {"nombre": "Rodrigo Villegas", "tipo": "Humor"}
        ]
    }
}
```

**Error (404):**
```json
{
    "success": false,
    "error": "Día no encontrado"
}
```

#### Competencia Folclórica
Lista todos los participantes de la competencia folclórica.

**Endpoint:** `GET /api/vina-2026/competencia-folclorica`

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "pais": "Argentina",
            "artista": "Campedrinos",
            "cancion": "La Zamba"
        },
        {
            "pais": "Chile",
            "artista": "A Los 4 Vientos",
            "cancion": "Valoración"
        },
        {
            "pais": "Colombia",
            "artista": "Rebolú",
            "cancion": "Los Herederos"
        },
        {
            "pais": "Ecuador",
            "artista": "Brenda",
            "cancion": "Capullito"
        },
        {
            "pais": "México",
            "artista": "Majo Cornejo",
            "cancion": "Ningún Color Tiene Dueño"
        },
        {
            "pais": "España",
            "artista": "María Peláe",
            "cancion": "Que Vengan A Por Mi"
        }
    ]
}
```

#### Competencia Internacional
Lista todos los participantes de la competencia internacional.

**Endpoint:** `GET /api/vina-2026/competencia-internacional`

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "pais": "Estonia",
            "artista": "Vanilla Ninja",
            "cancion": "Ready To Go"
        },
        {
            "pais": "España",
            "artista": "Antoñito Molina",
            "cancion": "Me Prometo"
        },
        {
            "pais": "Italia",
            "artista": "Chiara Grispo",
            "cancion": "Grazie A(d)dio"
        },
        {
            "pais": "Chile",
            "artista": "Son Del Valle",
            "cancion": "El Ciclo"
        },
        {
            "pais": "República Dominicana",
            "artista": "Johnny Sky",
            "cancion": "Call On Me"
        },
        {
            "pais": "México",
            "artista": "Trex",
            "cancion": "La Ruta Correcta"
        }
    ]
}
```

### 📅 Programación Completa Viña 2026

| Día | Fecha | Artistas |
|-----|-------|----------|
| **Domingo** | 22/02/2026 | Gloria Estefan, Matteo Bocelli, Stefan Kramer |
| **Lunes** | 23/02/2026 | Pet Shop Boys, Bomba Estéreo, Rodrigo Villegas |
| **Martes** | 24/02/2026 | Jesse & Joy, NMIXX, Esteban Düch |
| **Miércoles** | 25/02/2026 | Juanes, Ke Personajes, Asskha Sumathra |
| **Jueves** | 26/02/2026 | Mon Laferte, Yandel Sinfónico, Piare con Pe |
| **Viernes** | 27/02/2026 | Paulo Londra, Pablo Chill-E, Milo J, Pastor Rocha |

## 🎪 Festival del Huaso de Olmué 2026

### 🔒 Rutas Protegidas (Requieren autenticación JWT)

Todas las rutas del Festival de Olmué requieren incluir el token JWT en los headers:
```
Authorization: Bearer {token}
```

#### Obtener Parrilla Completa
Devuelve toda la información del Festival de Olmué 2026.

**Endpoint:** `GET /api/olmue-2026/parrilla`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "nombre": "LV Festival del Huaso de Olmué 2026",
        "ubicacion": "Anfiteatro El Patagual, Olmué, Chile",
        "animadores": ["María Luisa Godoy", "Eduardo Fuentes"],
        "programacion": [
            {
                "fecha": "2026-01-15",
                "dia": "Jueves",
                "artistas": [
                    {"nombre": "Bafona", "tipo": "Obertura"},
                    {"nombre": "Myriam Hernández", "tipo": "Música"},
                    {"nombre": "Paul Vásquez \"El Flaco\"", "tipo": "Humor"},
                    {"nombre": "Nicole", "tipo": "Música"}
                ]
            }
            // ... más días
        ],
        "competencia_folclorica": [...],
        "jurado": [...]
    }
}
```

#### Obtener Competencia Folclórica
Lista todos los participantes de la competencia folclórica de Olmué.

**Endpoint:** `GET /api/olmue-2026/competencia`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "titulo": "Misiones",
            "interprete": "Martín Acertijo"
        },
        {
            "titulo": "Se enamoró la paloma",
            "interprete": "María Teresa Lagos y Voces del Río"
        },
        {
            "titulo": "Vuelve a mi lado",
            "interprete": "Huamancuri"
        },
        {
            "titulo": "La curandera María",
            "interprete": "Los Mismos de Siempre"
        },
        {
            "titulo": "Me voy pa' Chile",
            "interprete": "Fernanda Riffo"
        },
        {
            "titulo": "Vamos juntos a Chiloé",
            "interprete": "Los Palmeros de Rancagua"
        },
        {
            "titulo": "Cuando me voy pa' la quinta",
            "interprete": "Ignacio Hernández y Los de Chile"
        },
        {
            "titulo": "Diablo Miguel",
            "interprete": "Jilatas"
        }
    ]
}
```

### 📅 Programación Completa Olmué 2026

| Día | Fecha | Artistas |
|-----|-------|----------|
| **Jueves** | 15/01/2026 | Bafona, Myriam Hernández, Paul Vásquez "El Flaco", Nicole |
| **Viernes** | 16/01/2026 | Los Patiperros y Hijos de Mariana de Osorio, Luck Ra, Erwin Padilla, Alanys Lagos y Toly Fu |
| **Sábado** | 17/01/2026 | Los de San Pablo, Américo, León Murillo, Gepe |
| **Domingo** | 18/01/2026 | Silvanita y Los del Quincho, Ráfaga, Felipe Parra, Entremares |

**Jurado:** Carolina Urrejola, Gonzalo Fouilloux, Manuel Caro "Dunga", Pablo Flamm, Wladimir Campos

## 📝 Ejemplos de Uso

### Con cURL

#### Viña - Obtener parrilla completa:
```bash
curl -X GET http://localhost:8000/api/vina-2026/parrilla
```

#### Viña - Obtener programación del martes:
```bash
curl -X GET http://localhost:8000/api/vina-2026/dia/martes
```

#### Olmué - Login y obtener parrilla:
```bash
# 1. Login
TOKEN=$(curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  | jq -r '.access_token')

# 2. Obtener parrilla
curl -X GET http://localhost:8000/api/olmue-2026/parrilla \
  -H "Authorization: Bearer $TOKEN"
```

### Con JavaScript (Fetch API)

```javascript
// Viña - Parrilla completa
fetch('http://localhost:8000/api/vina-2026/parrilla')
  .then(response => response.json())
  .then(data => console.log(data));

// Olmué - Con autenticación
// 1. Login
fetch('http://localhost:8000/api/auth/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    email: 'admin@example.com',
    password: 'password'
  })
})
.then(response => response.json())
.then(data => {
  const token = data.access_token;
  
  // 2. Obtener parrilla de Olmué
  return fetch('http://localhost:8000/api/olmue-2026/parrilla', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
})
.then(response => response.json())
.then(data => console.log(data));
```

### Con Python (Requests)

```python
import requests

# Viña - Parrilla completa
response = requests.get('http://localhost:8000/api/vina-2026/parrilla')
data = response.json()
print(data)

# Olmué - Con autenticación
# 1. Login
login_response = requests.post(
    'http://localhost:8000/api/auth/login',
    json={'email': 'admin@example.com', 'password': 'password'}
)
token = login_response.json()['access_token']

# 2. Obtener parrilla
headers = {'Authorization': f'Bearer {token}'}
olmue_response = requests.get(
    'http://localhost:8000/api/olmue-2026/parrilla',
    headers=headers
)
olmue_data = olmue_response.json()
print(olmue_data)
```

## 🔧 Estructura del Proyecto

```
api-vina-2026-laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php       # Autenticación JWT
│   │       ├── VinaController.php       # Festival de Viña 2026
│   │       └── FestivalController.php   # Festival de Olmué 2026
│   └── Models/
│       └── User.php
├── config/
│   ├── jwt.php                          # Configuración JWT
│   └── database.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── AdminUserSeeder.php
├── routes/
│   └── api.php                          # Definición de rutas API
└── .env                                  # Variables de entorno
```

## 🛡️ Códigos de Estado HTTP

| Código | Descripción |
|--------|-------------|
| 200 | Solicitud exitosa |
| 401 | No autenticado o token inválido |
| 404 | Recurso no encontrado |
| 422 | Error de validación |
| 500 | Error interno del servidor |

## ⚠️ Errores Comunes

### 401 Unauthorized
```json
{
    "error": "Unauthorized"
}
```
**Solución:** Verificar que el token JWT sea válido y esté incluido en el header `Authorization`.

### 404 Not Found
```json
{
    "success": false,
    "error": "Día no encontrado"
}
```
**Solución:** Verificar que el parámetro enviado sea correcto (ej: "lunes", "martes", etc.).

## 📚 Tecnologías Utilizadas

- **Laravel 9.52** - Framework PHP
- **JWT Auth** - Autenticación con JSON Web Tokens
- **PHP 8.0+** - Lenguaje de programación
- **MySQL** - Base de datos
- **Composer** - Gestor de dependencias

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT.

## 👥 Contacto

Para consultas o sugerencias, por favor abre un issue en el repositorio.

---

**Desarrollado con ❤️ para los amantes de los festivales de música chilenos** 🎵🇨🇱