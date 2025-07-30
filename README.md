# Jawaban Soal
Klik tombol di bawah untuk melihat jawaban soal test:

[![Tampilkan Jawaban](https://img.shields.io/badge/Lihat%20Jawaban-PDF-red?style=for-the-badge&logo=adobeacrobatreader&logoColor=white)](https://github.com/riskykurniawan15/sports_app/blob/master/answare.pdf)

# Sports App API

API Laravel 12.x yang komprehensif untuk mengelola tim olahraga, pemain, pertandingan, dan aktivitas pertandingan dengan autentikasi JWT.

## 🚀 Fitur

- **Autentikasi JWT** - Autentikasi API yang aman dengan JSON Web Tokens
- **Manajemen Tim** - Operasi CRUD lengkap untuk tim olahraga
- **Manajemen Pemain** - Registrasi pemain dengan nomor punggung dan posisi
- **Penjadwalan Pertandingan** - Pembuatan pertandingan dengan deteksi konflik
- **Aktivitas Pertandingan** - Event pertandingan real-time (gol, kartu, substitusi, dll)
- **Laporan Pertandingan** - Statistik dan laporan pertandingan yang komprehensif
- **Upload File** - Upload gambar untuk logo tim
- **Data Geografis** - Integrasi dengan API wilayah.id untuk provinsi/kota Indonesia
- **Soft Deletes** - Pelestarian data dengan fungsi soft delete
- **Respons API Terstandarisasi** - Format respons JSON yang konsisten

## 📋 Prasyarat

- PHP 8.2 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Laravel 12.x
- JWT Secret Key

## 🛠 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd sports_app
```

### Install Dependencies
```bash
composer install
```

### Setup Environment
Copy file environment:
```bash
cp .env.example .env
```

### Konfigurasi Environment Variables
Edit file `.env` dengan konfigurasi Anda:

```env
# Konfigurasi Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sports_app
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Konfigurasi JWT
JWT_SECRET=your-jwt-secret-key
JWT_TTL=1440

# API Key untuk Registrasi
API_KEY=your-super-secret-api-key-123

# Konfigurasi App
APP_NAME="Sports App API"
APP_ENV=local
APP_KEY=your-app-key
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### Generate Application Key
```bash
php artisan key:generate
```

### Generate JWT Secret
```bash
php artisan jwt:secret
```

### Jalankan Database Migrations
```bash
php artisan migrate
```

### Jalankan Database Seeder
```bash
php artisan db:seed
```

### Buat Storage Link
```bash
php artisan storage:link
```

### Jalankan Server
```bash
php artisan serve
```

## 🔐 Autentikasi

### User Default - By Seeder
Email: riskykurniawan15@gmail.com
Pass : kurniawan

### Struktur Token JWT
```json
{
  "code": {
    "status": 200,
    "message": "success"
  },
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 1440,
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

### API Key untuk Registrasi
Endpoint registrasi memerlukan header `X-API-Key` untuk keamanan.

## 📡 Endpoint API

### Autentikasi
| Method | Endpoint | Deskripsi | Auth Diperlukan |
|--------|----------|-----------|-----------------|
| POST | `/api/register` | Registrasi user baru | X-API-Key |
| POST | `/api/login` | Login user | Tidak |
| GET | `/api/user-profile` | Ambil profil user | JWT |
| PUT | `/api/user-profile` | Update profil user | JWT |
| PUT | `/api/user-password` | Update password user | JWT |

### Data Geografis (Publik)
| Method | Endpoint | Deskripsi | Auth Diperlukan |
|--------|----------|-----------|-----------------|
| GET | `/api/provinces` | Ambil semua provinsi | Tidak |
| GET | `/api/provinces/{code}` | Ambil kabupaten berdasarkan provinsi | Tidak |
| POST | `/api/provinces/clear-cache` | Bersihkan cache provinsi | X-API-Key |
| POST | `/api/provinces/{code}/clear-cache` | Bersihkan cache kabupaten | X-API-Key |

### Tim (Terproteksi)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/teams` | Daftar semua tim |
| POST | `/api/teams` | Buat tim baru |
| GET | `/api/teams/{id}` | Detail tim |
| PUT | `/api/teams/{id}` | Update tim |
| DELETE | `/api/teams/{id}` | Hapus tim |

### Posisi (Publik)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/positions` | Daftar semua posisi |
| GET | `/api/positions/{id}` | Detail posisi |

### Pemain (Terproteksi)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/players` | Daftar semua pemain |
| POST | `/api/players` | Buat pemain baru |
| GET | `/api/players/{id}` | Detail pemain |
| PUT | `/api/players/{id}` | Update pemain |
| DELETE | `/api/players/{id}` | Hapus pemain |

### Pertandingan (Terproteksi)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/matches` | Daftar semua pertandingan |
| POST | `/api/matches` | Buat pertandingan baru |
| GET | `/api/matches/{id}` | Detail pertandingan |
| PUT | `/api/matches/{id}` | Update pertandingan |
| DELETE | `/api/matches/{id}` | Hapus pertandingan |

### Aktivitas Pertandingan (Terproteksi)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/matches/{matchId}/activities` | Daftar aktivitas pertandingan |
| POST | `/api/matches/{matchId}/activities` | Tambah aktivitas pertandingan |
| GET | `/api/matches/{matchId}/activities/{id}` | Detail aktivitas |
| DELETE | `/api/matches/{matchId}/activities/{id}` | Hapus aktivitas |
| GET | `/api/matches/{matchId}/timeline` | Timeline pertandingan |
| GET | `/api/matches/{matchId}/stats` | Statistik pertandingan |
| GET | `/api/matches/{matchId}/match-report` | Laporan pertandingan lengkap |

### Upload File (Terproteksi)
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/upload/image` | Upload file gambar |

### Akses Gambar Publik
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/image/{filename}` | Akses gambar yang diupload |

## 📊 Model Data

### Tim
- `id` - Primary key
- `name` - Nama tim (max 100 karakter)
- `logo` - URL logo tim (max 250 karakter)
- `established_year` - Tahun berdirinya tim
- `address` - Alamat tim (text)
- `city` - Kode kota (divalidasi dengan API wilayah.id)

### Pemain
- `id` - Primary key
- `name` - Nama pemain (max 100 karakter)
- `team_id` - Foreign key ke tim
- `squad_number` - Nomor punggung unik per tim (1-99)
- `height` - Tinggi dalam cm
- `weight` - Berat dalam kg
- `position_id` - Foreign key ke posisi

### Pertandingan
- `id` - Primary key
- `venue` - Venue/stadion pertandingan
- `match_datetime` - Tanggal dan waktu pertandingan
- `home_team_id` - ID tim tuan rumah
- `away_team_id` - ID tim tamu
- `match_metadata` - JSON untuk skor, pemenang, dll

### Aktivitas Pertandingan
- `id` - Primary key
- `match_id` - Foreign key ke pertandingan
- `team_id` - ID tim (nullable)
- `player_id` - ID pemain (nullable)
- `activity` - Jenis aktivitas (gol, kartu, substitusi, dll)
- `time_activity` - Waktu dalam pertandingan (HH:MM:SS)
- `detail` - Detail tambahan (text)

## 🔧 Contoh Penggunaan

### Registrasi User Baru
```bash
curl -X POST "http://localhost:8000/api/register" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your-super-secret-api-key-123" \
  -d '{
    "name": "Risky Kurniawan",
    "email": "riskykurniawan15@gmail.com",
    "password": "kurniawan",
    "password_confirmation": "kurniawan"
  }'
```

**Response:**
```json
{
  "code": {
    "status": 201,
    "message": "User successfully registered"
  },
  "data": {
    "user": {
      "name": "Risky Kurniawan",
      "email": "riskykurniawan15@gmail.com",
      "updated_at": "2025-07-29T07:22:47.000000Z",
      "created_at": "2025-07-29T07:22:47.000000Z",
      "id": 2
    },
    "authorization": {
      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
      "type": "bearer"
    }
  }
}
```

### Login
```bash
curl -X POST "http://localhost:8000/api/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "riskykurniawan15@gmail.com",
    "password": "kurniawan"
  }'
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Login successful"
  },
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzUzNzczNzc2LCJleHAiOjE3NTM3NzczNzYsIm5iZiI6MTc1Mzc3Mzc3NiwianRpIjoiQUhudGw0MmtxaUlGWnBLRyIsInN1YiI6IjIiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.SFghPV7RPaW_PkIVQbCi4_lD_Oc8dFAZIDbNNpSKjRQ",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
      "id": 2,
      "name": "Risky Kurniawan",
      "email": "riskykurniawan15@gmail.com",
      "email_verified_at": null,
      "created_at": "2025-07-29T07:22:47.000000Z",
      "updated_at": "2025-07-29T07:22:47.000000Z"
    }
  }
}
```

### Ambil Profil User
```bash
curl -X GET "http://localhost:8000/api/user-profile" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "User profile retrieved successfully"
  },
  "data": {
    "id": 2,
    "name": "Risky Kurniawan",
    "email": "riskykurniawan15@gmail.com",
    "email_verified_at": null,
    "created_at": "2025-07-29T07:22:47.000000Z",
    "updated_at": "2025-07-29T07:22:47.000000Z"
  }
}
```

### Update Profil User
```bash
curl -X PUT "http://localhost:8000/api/user-profile" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Risky Updated",
    "email": "risky.updated@gmail.com"
  }'
```

**Response:**
```json
{
    "code": {
        "status": 200,
        "message": "Profile updated successfully"
    },
    "data": {
        "id": 2,
        "name": "Risky Updated",
        "email": "riskykurniawan15@gmail.com",
        "email_verified_at": null,
        "created_at": "2025-07-29T07:22:47.000000Z",
        "updated_at": "2025-07-30T09:55:44.000000Z"
    }
}
```

### Update Password User
```bash
curl -X PUT "http://localhost:8000/api/user-password" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "current_password": "password123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
  }'
```

**Response:**
```json
{
    "code": {
        "status": 200,
        "message": "Password updated successfully"
    },
    "data": null
}
```

### Upload Gambar
```bash
curl -X POST "http://localhost:8000/api/upload/image" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -F "image=@/path/to/your/image.jpg"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Image uploaded successfully"
  },
  "data": {
    "url": "http://localhost:8000/api/image/2_1753783986_n6PqNOTMRq.jpg",
    "filename": "2_1753783986_n6PqNOTMRq.jpg",
    "original_name": "risky.jpg",
    "size": 99344,
    "mime_type": "image/jpeg",
    "storage_path": "logo/2_1753783986_n6PqNOTMRq.jpg"
  }
}
```

### Daftar Provinsi
```bash
curl -X GET "http://localhost:8000/api/provinces"
```

**Response:**
```json
{
    "code": {
        "status": 200,
        "message": "All provinces retrieved successfully"
    },
    "data": [
        {
            "code": "11",
            "name": "Aceh"
        },
        {
            "code": "51",
            "name": "Bali"
        },
        {
            "code": "36",
            "name": "Banten"
        },
        {
            "code": "17",
            "name": "Bengkulu"
        },
        {
            "code": "34",
            "name": "Daerah Istimewa Yogyakarta"
        },
        {
            "code": "31",
            "name": "DKI Jakarta"
        },
        {
            "code": "75",
            "name": "Gorontalo"
        },
        {
            "code": "15",
            "name": "Jambi"
        },
        {
            "code": "32",
            "name": "Jawa Barat"
        },
        {
            "code": "33",
            "name": "Jawa Tengah"
        },
        {
            "code": "35",
            "name": "Jawa Timur"
        },
        {
            "code": "61",
            "name": "Kalimantan Barat"
        },
        {
            "code": "63",
            "name": "Kalimantan Selatan"
        },
        {
            "code": "62",
            "name": "Kalimantan Tengah"
        },
        {
            "code": "64",
            "name": "Kalimantan Timur"
        },
        {
            "code": "65",
            "name": "Kalimantan Utara"
        },
        {
            "code": "19",
            "name": "Kepulauan Bangka Belitung"
        },
        {
            "code": "21",
            "name": "Kepulauan Riau"
        },
        {
            "code": "18",
            "name": "Lampung"
        },
        {
            "code": "81",
            "name": "Maluku"
        },
        {
            "code": "82",
            "name": "Maluku Utara"
        },
        {
            "code": "52",
            "name": "Nusa Tenggara Barat"
        },
        {
            "code": "53",
            "name": "Nusa Tenggara Timur"
        },
        {
            "code": "91",
            "name": "Papua"
        },
        {
            "code": "92",
            "name": "Papua Barat"
        },
        {
            "code": "96",
            "name": "Papua Barat Daya"
        },
        {
            "code": "95",
            "name": "Papua Pegunungan"
        },
        {
            "code": "93",
            "name": "Papua Selatan"
        },
        {
            "code": "94",
            "name": "Papua Tengah"
        },
        {
            "code": "14",
            "name": "Riau"
        },
        {
            "code": "76",
            "name": "Sulawesi Barat"
        },
        {
            "code": "73",
            "name": "Sulawesi Selatan"
        },
        {
            "code": "72",
            "name": "Sulawesi Tengah"
        },
        {
            "code": "74",
            "name": "Sulawesi Tenggara"
        },
        {
            "code": "71",
            "name": "Sulawesi Utara"
        },
        {
            "code": "13",
            "name": "Sumatera Barat"
        },
        {
            "code": "16",
            "name": "Sumatera Selatan"
        },
        {
            "code": "12",
            "name": "Sumatera Utara"
        }
    ],
    "meta": {
        "total": 38,
        "search": null,
        "cache_info": {
            "provinces": {
                "has_cache": true,
                "cache_time": 86400,
                "cache_key": "wilayah_provinces"
            },
            "regencies": {
                "cache_time": 86400,
                "cache_pattern": "wilayah_regencies_*"
            }
        }
    }
}
```

### Daftar Kabupaten
```bash
curl -X GET "http://localhost:8000/api/provinces/32"
```

**Response:**
```json
{
    "code": {
        "status": 200,
        "message": "Regencies retrieved successfully"
    },
    "data": {
        "province": {
            "code": "32",
            "name": "Jawa Barat"
        },
        "regencies": [
            {
                "code": "32.04",
                "name": "Kabupaten Bandung"
            },
            {
                "code": "32.17",
                "name": "Kabupaten Bandung Barat"
            },
            {
                "code": "32.16",
                "name": "Kabupaten Bekasi"
            },
            {
                "code": "32.01",
                "name": "Kabupaten Bogor"
            },
            {
                "code": "32.07",
                "name": "Kabupaten Ciamis"
            },
            {
                "code": "32.03",
                "name": "Kabupaten Cianjur"
            },
            {
                "code": "32.09",
                "name": "Kabupaten Cirebon"
            },
            {
                "code": "32.05",
                "name": "Kabupaten Garut"
            },
            {
                "code": "32.12",
                "name": "Kabupaten Indramayu"
            },
            {
                "code": "32.15",
                "name": "Kabupaten Karawang"
            },
            {
                "code": "32.08",
                "name": "Kabupaten Kuningan"
            },
            {
                "code": "32.10",
                "name": "Kabupaten Majalengka"
            },
            {
                "code": "32.18",
                "name": "Kabupaten Pangandaran"
            },
            {
                "code": "32.14",
                "name": "Kabupaten Purwakarta"
            },
            {
                "code": "32.13",
                "name": "Kabupaten Subang"
            },
            {
                "code": "32.02",
                "name": "Kabupaten Sukabumi"
            },
            {
                "code": "32.11",
                "name": "Kabupaten Sumedang"
            },
            {
                "code": "32.06",
                "name": "Kabupaten Tasikmalaya"
            },
            {
                "code": "32.73",
                "name": "Kota Bandung"
            },
            {
                "code": "32.79",
                "name": "Kota Banjar"
            },
            {
                "code": "32.75",
                "name": "Kota Bekasi"
            },
            {
                "code": "32.71",
                "name": "Kota Bogor"
            },
            {
                "code": "32.77",
                "name": "Kota Cimahi"
            },
            {
                "code": "32.74",
                "name": "Kota Cirebon"
            },
            {
                "code": "32.76",
                "name": "Kota Depok"
            },
            {
                "code": "32.72",
                "name": "Kota Sukabumi"
            },
            {
                "code": "32.78",
                "name": "Kota Tasikmalaya"
            }
        ]
    },
    "meta": {
        "total": 27,
        "search": null,
        "cache_info": {
            "provinces": {
                "has_cache": true,
                "cache_time": 86400,
                "cache_key": "wilayah_provinces"
            },
            "regencies": {
                "cache_time": 86400,
                "cache_pattern": "wilayah_regencies_*"
            }
        }
    }
}
```

### Buat Tim
```bash
curl -X POST "http://localhost:8000/api/teams" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "name": "Persib Bandung",
    "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
    "established_year": 1933,
    "address": "Jl. Soekarno-Hatta No. 402, Bandung",
    "city": "32.73"
  }'
```

**Response:**
```json
{
  "code": {
    "status": 201,
    "message": "Team created successfully"
  },
  "data": {
    "name": "Persib Bandung",
    "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
    "established_year": 1933,
    "address": "Jl. Soekarno-Hatta No. 402, Bandung",
    "city": "32.73",
    "updated_at": "2025-07-30T03:10:48.000000Z",
    "created_at": "2025-07-30T03:10:48.000000Z",
    "id": 2
  }
}
```

### Daftar Tim
```bash
curl -X GET "http://localhost:8000/api/teams" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Teams retrieved successfully"
  },
  "data": [
    {
      "id": 2,
      "name": "Persib Bandung",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:10:48.000000Z",
      "city_name": "Kota Bandung",
      "province_name": "Jawa Barat"
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 10,
      "total": 1,
      "from": 1,
      "to": 1
    },
    "filters": {
      "search": null,
      "city": null
    }
  }
}
```

### Detail Tim
```bash
curl -X GET "http://localhost:8000/api/teams/2" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Team retrieved successfully"
  },
  "data": {
    "id": 2,
    "name": "Persib Bandung",
    "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
    "established_year": 1933,
    "address": "Jl. Soekarno-Hatta No. 402, Bandung",
    "city": "32.73",
    "created_at": "2025-07-30T03:10:48.000000Z",
    "updated_at": "2025-07-30T03:10:48.000000Z",
    "city_name": "Kota Bandung",
    "province_name": "Jawa Barat"
  }
}
```

### Update Tim
```bash
curl -X PUT "http://localhost:8000/api/teams/2" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "name": "Persib Bandung FC",
    "established_year": 1933
  }'
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Team updated successfully"
  },
  "data": {
    "id": 2,
    "name": "Persib Bandung FC",
    "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
    "established_year": 1933,
    "address": "Jl. Soekarno-Hatta No. 402, Bandung",
    "city": "32.73",
    "created_at": "2025-07-30T03:10:48.000000Z",
    "updated_at": "2025-07-30T03:36:23.000000Z",
    "city_name": "Kota Bandung",
    "province_name": "Jawa Barat"
  }
}
```

### Hapus Tim
```bash
curl -X DELETE "http://localhost:8000/api/teams/2" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```http
HTTP/1.1 204 No Content
```

### Daftar Posisi
```bash
curl -X GET "http://localhost:8000/api/positions"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Positions retrieved successfully"
  },
  "data": [
    {
      "id": 1,
      "code": "GK",
      "name": "Goalkeeper",
      "desc": "Penjaga gawang",
      "created_at": null,
      "updated_at": null
    },
    {
      "id": 2,
      "code": "CB",
      "name": "Centre Back",
      "desc": "Bek tengah",
      "created_at": null,
      "updated_at": null
    },
    {
      "id": 25,
      "code": "TQ",
      "name": "Trequartista",
      "desc": "Playmaker bebas di depan",
      "created_at": null,
      "updated_at": null
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 50,
      "total": 38,
      "from": 1,
      "to": 38
    },
    "filters": {
      "search": null,
      "category": null
    }
  }
}
```

### Detail Posisi
```bash
curl -X GET "http://localhost:8000/api/positions/1"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Position retrieved successfully"
  },
  "data": {
    "id": 1,
    "code": "GK",
    "name": "Goalkeeper",
    "desc": "Penjaga gawang",
    "created_at": null,
    "updated_at": null
  }
}
```

### Buat Pemain
```bash
curl -X POST "http://localhost:8000/api/players" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "name": "Lionel Messi",
    "team_id": 2,
    "squad_number": 10,
    "height": 170,
    "weight": 72,
    "position_id": 25
  }'
```

**Response:**
```json
{
  "code": {
    "status": 201,
    "message": "Player created successfully"
  },
  "data": {
    "name": "Lionel Messi",
    "team_id": 2,
    "squad_number": 10,
    "height": 170,
    "weight": 72,
    "position_id": 25,
    "updated_at": "2025-07-30T04:34:40.000000Z",
    "created_at": "2025-07-30T04:34:40.000000Z",
    "id": 1,
    "team": {
      "id": 2,
      "name": "Persib Bandung FC",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:39:17.000000Z"
    },
    "position": {
      "id": 25,
      "code": "TQ",
      "name": "Trequartista",
      "desc": "Playmaker bebas di depan",
      "created_at": null,
      "updated_at": null
    }
  }
}
```

### Daftar Pemain
```bash
curl -X GET "http://localhost:8000/api/players" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Players retrieved successfully"
  },
  "data": [
    {
      "id": 1,
      "name": "Lionel Messi",
      "team_id": 2,
      "squad_number": 11,
      "height": 170,
      "weight": 75,
      "position_id": 25,
      "created_at": "2025-07-30T04:34:40.000000Z",
      "updated_at": "2025-07-30T04:35:42.000000Z",
      "team": {
        "id": 2,
        "name": "Persib Bandung FC",
        "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
        "established_year": 1933,
        "address": "Jl. Soekarno-Hatta No. 402, Bandung",
        "city": "32.73",
        "created_at": "2025-07-30T03:10:48.000000Z",
        "updated_at": "2025-07-30T03:39:17.000000Z"
      },
      "position": {
        "id": 25,
        "code": "TQ",
        "name": "Trequartista",
        "desc": "Playmaker bebas di depan",
        "created_at": null,
        "updated_at": null
      }
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 1,
      "from": 1,
      "to": 1
    },
    "filters": {
      "search": null,
      "team_id": null,
      "position_id": null,
      "squad_number": null
    }
  }
}
```

### Detail Pemain
```bash
curl -X GET "http://localhost:8000/api/players/1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Player retrieved successfully"
  },
  "data": {
    "id": 1,
    "name": "Lionel Messi",
    "team_id": 2,
    "squad_number": 11,
    "height": 170,
    "weight": 75,
    "position_id": 25,
    "created_at": "2025-07-30T04:34:40.000000Z",
    "updated_at": "2025-07-30T04:35:42.000000Z",
    "team": {
      "id": 2,
      "name": "Persib Bandung FC",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:39:17.000000Z"
    },
    "position": {
      "id": 25,
      "code": "TQ",
      "name": "Trequartista",
      "desc": "Playmaker bebas di depan",
      "created_at": null,
      "updated_at": null
    }
  }
}
```

### Update Pemain
```bash
curl -X PUT "http://localhost:8000/api/players/1" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "team_id": 2,
    "squad_number": 11,
    "weight": 75
  }'
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Player updated successfully"
  },
  "data": {
    "id": 1,
    "name": "Lionel Messi",
    "team_id": 2,
    "squad_number": 11,
    "height": 170,
    "weight": 75,
    "position_id": 25,
    "created_at": "2025-07-30T04:34:40.000000Z",
    "updated_at": "2025-07-30T04:35:42.000000Z",
    "team": {
      "id": 2,
      "name": "Persib Bandung FC",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:39:17.000000Z"
    },
    "position": {
      "id": 25,
      "code": "TQ",
      "name": "Trequartista",
      "desc": "Playmaker bebas di depan",
      "created_at": null,
      "updated_at": null
    }
  }
}
```

### Hapus Pemain
```bash
curl -X DELETE "http://localhost:8000/api/players/1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```http
HTTP/1.1 204 No Content
```

### Buat Pertandingan
```bash
curl -X POST "http://localhost:8000/api/matches" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "venue": "Stadion Gelora Bung Karno",
    "match_datetime": "2025-07-30 19:30:00",
    "home_team_id": 2,
    "away_team_id": 3
  }'
```

**Response:**
```json
{
  "code": {
    "status": 201,
    "message": "Match created successfully"
  },
  "data": {
    "venue": "Stadion Gelora Bung Karno",
    "match_datetime": "2025-07-30T19:30:00.000000Z",
    "home_team_id": 2,
    "away_team_id": 3,
    "updated_at": "2025-07-30T06:14:19.000000Z",
    "created_at": "2025-07-30T06:14:19.000000Z",
    "id": 1,
    "home_team": {
      "id": 2,
      "name": "Persib Bandung FC",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:39:17.000000Z"
    },
    "away_team": {
      "id": 3,
      "name": "Persib Bandung 2",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T06:12:22.000000Z",
      "updated_at": "2025-07-30T06:12:22.000000Z"
    }
  }
}
```

### Daftar Pertandingan
```bash
curl -X GET "http://localhost:8000/api/matches" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Matches retrieved successfully"
  },
  "data": [
    {
      "id": 1,
      "venue": "Stadion Gelora Bung Karno",
      "match_datetime": "2025-07-30T19:30:00.000000Z",
      "home_team_id": 2,
      "away_team_id": 3,
      "match_metadata": null,
      "created_at": "2025-07-30T06:14:19.000000Z",
      "updated_at": "2025-07-30T06:14:19.000000Z",
      "home_team": {
        "id": 2,
        "name": "Persib Bandung FC",
        "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
        "established_year": 1933,
        "address": "Jl. Soekarno-Hatta No. 402, Bandung",
        "city": "32.73",
        "created_at": "2025-07-30T03:10:48.000000Z",
        "updated_at": "2025-07-30T03:39:17.000000Z"
      },
      "away_team": {
        "id": 3,
        "name": "Persib Bandung 2",
        "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
        "established_year": 1933,
        "address": "Jl. Soekarno-Hatta No. 402, Bandung",
        "city": "32.73",
        "created_at": "2025-07-30T06:12:22.000000Z",
        "updated_at": "2025-07-30T06:12:22.000000Z"
      }
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 1,
      "from": 1,
      "to": 1
    },
    "filters": {
      "search": null,
      "home_team_id": null,
      "away_team_id": null,
      "team_id": null,
      "start_date": null,
      "end_date": null,
      "status": null
    }
  }
}
```

### Detail Pertandingan
```bash
curl -X GET "http://localhost:8000/api/matches/1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Match retrieved successfully"
  },
  "data": {
    "id": 1,
    "venue": "Stadion Gelora Bung Karno",
    "match_datetime": "2025-07-30T19:30:00.000000Z",
    "home_team_id": 2,
    "away_team_id": 3,
    "match_metadata": {
      "status": "home-win",
      "scores_away": 0,
      "scores_home": 1,
      "winner_team": 2
    },
    "created_at": "2025-07-30T06:14:19.000000Z",
    "updated_at": "2025-07-30T07:12:07.000000Z",
    "home_team": {
      "id": 2,
      "name": "Persib Bandung FC",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:39:17.000000Z"
    },
    "away_team": {
      "id": 3,
      "name": "Persib Bandung 2",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T06:12:22.000000Z",
      "updated_at": "2025-07-30T06:12:22.000000Z"
    }
  }
}
```

### Update Pertandingan
```bash
curl -X PUT "http://localhost:8000/api/matches/1" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "home_team_id": 2,
    "away_team_id": 3,
    "match_datetime": "2025-07-30 19:30:00"
  }'
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Match updated successfully"
  },
  "data": {
    "id": 1,
    "venue": "Stadion Gelora Bung Karno",
    "match_datetime": "2025-07-30T19:30:00.000000Z",
    "home_team_id": 2,
    "away_team_id": 3,
    "match_metadata": null,
    "created_at": "2025-07-30T06:14:19.000000Z",
    "updated_at": "2025-07-30T06:14:19.000000Z",
    "home_team": {
      "id": 2,
      "name": "Persib Bandung FC",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:39:17.000000Z"
    },
    "away_team": {
      "id": 3,
      "name": "Persib Bandung 2",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T06:12:22.000000Z",
      "updated_at": "2025-07-30T06:12:22.000000Z"
    }
  }
}
```

### Hapus Pertandingan
```bash
curl -X DELETE "http://localhost:8000/api/matches/1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```http
HTTP/1.1 204 No Content
```

### Daftar Jenis Aktivitas
```bash
curl -X GET "http://localhost:8000/api/matches/activity-types" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Activity types retrieved successfully"
  },
  "data": {
    "match_start": "Pertandingan dimulai",
    "match_end": "Pertandingan berakhir",
    "half_time": "Istirahat babak pertama",
    "second_half": "Babak kedua dimulai",
    "extra_time_start": "Perpanjangan waktu dimulai",
    "extra_time_end": "Perpanjangan waktu berakhir",
    "penalty_shootout_start": "Adu penalti dimulai",
    "penalty_shootout_end": "Adu penalti berakhir",
    "goal": "Gol",
    "own_goal": "Gol bunuh diri",
    "penalty_goal": "Gol dari penalti",
    "free_kick_goal": "Gol dari tendangan bebas",
    "header_goal": "Gol sundulan",
    "volley_goal": "Gol voli",
    "long_range_goal": "Gol jarak jauh",
    "assist": "Assist",
    "yellow_card": "Kartu kuning",
    "red_card": "Kartu merah",
    "second_yellow": "Kartu kuning kedua (merah)",
    "yellow_red_card": "Kartu kuning-merah",
    "foul": "Pelanggaran",
    "dangerous_play": "Permainan berbahaya",
    "handball": "Handball",
    "offside": "Offside",
    "diving": "Simulasi/diving",
    "violent_conduct": "Kekerasan",
    "unsporting_behavior": "Perilaku tidak sportif",
    "dissent": "Protes kepada wasit",
    "time_wasting": "Membuang waktu",
    "substitution_in": "Pemain masuk",
    "substitution_out": "Pemain keluar",
    "tactical_substitution": "Substitusi taktis",
    "injury_substitution": "Substitusi cedera",
    "injury": "Cedera",
    "medical_attention": "Pertolongan medis",
    "concussion": "Gegar otak",
    "blood_injury": "Cedera berdarah",
    "corner": "Tendangan sudut",
    "free_kick": "Tendangan bebas",
    "penalty_awarded": "Penalti diberikan",
    "penalty_missed": "Penalti meleset",
    "penalty_saved": "Penalti diselamatkan",
    "throw_in": "Lemparan ke dalam",
    "goal_kick": "Tendangan gawang",
    "save": "Penyelamatan",
    "catch": "Menangkap bola",
    "punch": "Meninju bola",
    "goalkeeper_foul": "Pelanggaran kiper",
    "goalkeeper_handball": "Handball kiper",
    "ball_out": "Bola keluar",
    "ball_in_play": "Bola dalam permainan",
    "referee_decision": "Keputusan wasit",
    "var_check": "Pengecekan VAR",
    "var_decision": "Keputusan VAR",
    "goal_disallowed": "Gol dibatalkan",
    "goal_allowed": "Gol disahkan"
  }
}
```

### Mulai Pertandingan
```bash
curl -X POST "http://localhost:8000/api/matches/1/activities" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "activity": "match_start",
    "time_activity": "00:00:00"
  }'
```

**Response:**
```json
{
  "code": {
    "status": 201,
    "message": "Match activity created successfully"
  },
  "data": {
    "activity": "match_start",
    "time_activity": "00:00:00",
    "match_id": "1",
    "updated_at": "2025-07-30T07:02:08.000000Z",
    "created_at": "2025-07-30T07:02:08.000000Z",
    "id": 1,
    "match": {
      "id": 1,
      "venue": "Stadion Gelora Bung Karno",
      "match_datetime": "2025-07-30T19:30:00.000000Z",
      "home_team_id": 2,
      "away_team_id": 3,
      "match_metadata": null,
      "created_at": "2025-07-30T06:14:19.000000Z",
      "updated_at": "2025-07-30T06:26:19.000000Z"
    },
    "team": null,
    "player": null
  }
}
```

### Akhiri Pertandingan
```bash
curl -X POST "http://localhost:8000/api/matches/1/activities" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "activity": "match_end",
    "time_activity": "01:30:00"
  }'
```

**Response:**
```json
{
  "code": {
    "status": 201,
    "message": "Match activity created successfully"
  },
  "data": {
    "activity": "match_end",
    "time_activity": "01:30:00",
    "match_id": "1",
    "updated_at": "2025-07-30T07:02:20.000000Z",
    "created_at": "2025-07-30T07:02:20.000000Z",
    "id": 2,
    "match": {
      "id": 1,
      "venue": "Stadion Gelora Bung Karno",
      "match_datetime": "2025-07-30T19:30:00.000000Z",
      "home_team_id": 2,
      "away_team_id": 3,
      "match_metadata": {
        "status": "draw",
        "scores_away": 0,
        "scores_home": 0
      },
      "created_at": "2025-07-30T06:14:19.000000Z",
      "updated_at": "2025-07-30T07:02:20.000000Z"
    },
    "team": null,
    "player": null
  }
}
```

### Tambah Aktivitas (Gol)
```bash
curl -X POST "http://localhost:8000/api/matches/1/activities" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "team_id": 2,
    "player_id": 1,
    "activity": "goal",
    "time_activity": "00:15:30",
    "detail": "Gol spektakuler dari tendangan bebas"
  }'
```

**Response:**
```json
{
  "code": {
    "status": 201,
    "message": "Match activity created successfully"
  },
  "data": {
    "team_id": 2,
    "player_id": 1,
    "activity": "goal",
    "time_activity": "00:15:30",
    "detail": "Gol spektakuler dari tendangan bebas",
    "match_id": "1",
    "updated_at": "2025-07-30T07:10:39.000000Z",
    "created_at": "2025-07-30T07:10:39.000000Z",
    "id": 3,
    "match": {
      "id": 1,
      "venue": "Stadion Gelora Bung Karno",
      "match_datetime": "2025-07-30T19:30:00.000000Z",
      "home_team_id": 2,
      "away_team_id": 3,
      "match_metadata": {
        "status": "draw",
        "scores_away": 0,
        "scores_home": 0
      },
      "created_at": "2025-07-30T06:14:19.000000Z",
      "updated_at": "2025-07-30T07:02:20.000000Z"
    },
    "team": {
      "id": 2,
      "name": "Persib Bandung FC",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:39:17.000000Z"
    },
    "player": {
      "id": 1,
      "name": "Lionel Messi",
      "team_id": 2,
      "squad_number": 11,
      "height": 170,
      "weight": 72,
      "position_id": 25,
      "created_at": "2025-07-30T04:43:10.000000Z",
      "updated_at": "2025-07-30T04:43:10.000000Z"
    }
  }
}
```

### Daftar Aktivitas Pertandingan
```bash
curl -X GET "http://localhost:8000/api/matches/1/activities" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Match activities retrieved successfully"
  },
  "data": [
    {
      "id": 4,
      "match_id": 1,
      "team_id": null,
      "player_id": null,
      "activity": "match_end",
      "time_activity": "01:30:00",
      "detail": null,
      "created_at": "2025-07-30T07:12:07.000000Z",
      "updated_at": "2025-07-30T07:12:07.000000Z",
      "match": {
        "id": 1,
        "venue": "Stadion Gelora Bung Karno",
        "match_datetime": "2025-07-30T19:30:00.000000Z",
        "home_team_id": 2,
        "away_team_id": 3,
        "match_metadata": {
          "status": "home-win",
          "scores_away": 0,
          "scores_home": 1,
          "winner_team": 2
        },
        "created_at": "2025-07-30T06:14:19.000000Z",
        "updated_at": "2025-07-30T07:12:07.000000Z"
      },
      "team": null,
      "player": null
    },
    {
      "id": 3,
      "match_id": 1,
      "team_id": 2,
      "player_id": 1,
      "activity": "goal",
      "time_activity": "00:15:30",
      "detail": "Gol spektakuler dari tendangan bebas",
      "created_at": "2025-07-30T07:10:39.000000Z",
      "updated_at": "2025-07-30T07:10:39.000000Z",
      "match": {
        "id": 1,
        "venue": "Stadion Gelora Bung Karno",
        "match_datetime": "2025-07-30T19:30:00.000000Z",
        "home_team_id": 2,
        "away_team_id": 3,
        "match_metadata": {
          "status": "home-win",
          "scores_away": 0,
          "scores_home": 1,
          "winner_team": 2
        },
        "created_at": "2025-07-30T06:14:19.000000Z",
        "updated_at": "2025-07-30T07:12:07.000000Z"
      },
      "team": {
        "id": 2,
        "name": "Persib Bandung FC",
        "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
        "established_year": 1933,
        "address": "Jl. Soekarno-Hatta No. 402, Bandung",
        "city": "32.73",
        "created_at": "2025-07-30T03:10:48.000000Z",
        "updated_at": "2025-07-30T03:39:17.000000Z"
      },
      "player": {
        "id": 1,
        "name": "Lionel Messi",
        "team_id": 2,
        "squad_number": 11,
        "height": 170,
        "weight": 72,
        "position_id": 25,
        "created_at": "2025-07-30T04:43:10.000000Z",
        "updated_at": "2025-07-30T04:43:10.000000Z"
      }
    },
    {
      "id": 1,
      "match_id": 1,
      "team_id": null,
      "player_id": null,
      "activity": "match_start",
      "time_activity": "00:00:00",
      "detail": null,
      "created_at": "2025-07-30T07:02:08.000000Z",
      "updated_at": "2025-07-30T07:02:08.000000Z",
      "match": {
        "id": 1,
        "venue": "Stadion Gelora Bung Karno",
        "match_datetime": "2025-07-30T19:30:00.000000Z",
        "home_team_id": 2,
        "away_team_id": 3,
        "match_metadata": {
          "status": "home-win",
          "scores_away": 0,
          "scores_home": 1,
          "winner_team": 2
        },
        "created_at": "2025-07-30T06:14:19.000000Z",
        "updated_at": "2025-07-30T07:12:07.000000Z"
      },
      "team": null,
      "player": null
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 3,
      "from": 1,
      "to": 3
    },
    "filters": {
      "search": null,
      "match_id": "1",
      "team_id": null,
      "player_id": null,
      "activity": null,
      "start_time": null,
      "end_time": null,
      "type": null
    }
  }
}
```

### Detail Aktivitas
```bash
curl -X GET "http://localhost:8000/api/matches/1/activities/3" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Match activity retrieved successfully"
  },
  "data": {
    "id": 3,
    "match_id": 1,
    "team_id": 2,
    "player_id": 1,
    "activity": "goal",
    "time_activity": "00:15:30",
    "detail": "Gol spektakuler dari tendangan bebas",
    "created_at": "2025-07-30T07:10:39.000000Z",
    "updated_at": "2025-07-30T07:10:39.000000Z",
    "match": {
      "id": 1,
      "venue": "Stadion Gelora Bung Karno",
      "match_datetime": "2025-07-30T19:30:00.000000Z",
      "home_team_id": 2,
      "away_team_id": 3,
      "match_metadata": {
        "status": "home-win",
        "scores_away": 0,
        "scores_home": 1,
        "winner_team": 2
      },
      "created_at": "2025-07-30T06:14:19.000000Z",
      "updated_at": "2025-07-30T07:12:07.000000Z"
    },
    "team": {
      "id": 2,
      "name": "Persib Bandung FC",
      "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
      "established_year": 1933,
      "address": "Jl. Soekarno-Hatta No. 402, Bandung",
      "city": "32.73",
      "created_at": "2025-07-30T03:10:48.000000Z",
      "updated_at": "2025-07-30T03:39:17.000000Z"
    },
    "player": {
      "id": 1,
      "name": "Lionel Messi",
      "team_id": 2,
      "squad_number": 11,
      "height": 170,
      "weight": 72,
      "position_id": 25,
      "created_at": "2025-07-30T04:43:10.000000Z",
      "updated_at": "2025-07-30T04:43:10.000000Z"
    }
  }
}
```

### Hapus Aktivitas
```bash
curl -X DELETE "http://localhost:8000/api/matches/1/activities/3" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```http
HTTP/1.1 204 No Content
```

### Timeline Pertandingan
```bash
curl -X GET "http://localhost:8000/api/matches/1/timeline" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Match timeline retrieved successfully"
  },
  "data": [
    {
      "id": 7,
      "match_id": 1,
      "team_id": null,
      "player_id": null,
      "activity": "match_end",
      "time_activity": "01:30:00",
      "detail": null,
      "created_at": "2025-07-30T07:22:26.000000Z",
      "updated_at": "2025-07-30T07:22:26.000000Z",
      "team": null,
      "player": null
    },
    {
      "id": 5,
      "match_id": 1,
      "team_id": 2,
      "player_id": 1,
      "activity": "goal",
      "time_activity": "00:25:30",
      "detail": "Gol spektakuler dari tendangan bebas",
      "created_at": "2025-07-30T07:22:12.000000Z",
      "updated_at": "2025-07-30T07:22:12.000000Z",
      "team": {
        "id": 2,
        "name": "Persib Bandung FC",
        "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
        "established_year": 1933,
        "address": "Jl. Soekarno-Hatta No. 402, Bandung",
        "city": "32.73",
        "created_at": "2025-07-30T03:10:48.000000Z",
        "updated_at": "2025-07-30T03:39:17.000000Z"
      },
      "player": {
        "id": 1,
        "name": "Lionel Messi",
        "team_id": 2,
        "squad_number": 11,
        "height": 170,
        "weight": 72,
        "position_id": 25,
        "created_at": "2025-07-30T04:43:10.000000Z",
        "updated_at": "2025-07-30T04:43:10.000000Z"
      }
    },
    {
      "id": 6,
      "match_id": 1,
      "team_id": 2,
      "player_id": 1,
      "activity": "own_goal",
      "time_activity": "00:21:30",
      "detail": "Gol spektakuler dari tendangan bebas",
      "created_at": "2025-07-30T07:22:21.000000Z",
      "updated_at": "2025-07-30T07:22:21.000000Z",
      "team": {
        "id": 2,
        "name": "Persib Bandung FC",
        "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
        "established_year": 1933,
        "address": "Jl. Soekarno-Hatta No. 402, Bandung",
        "city": "32.73",
        "created_at": "2025-07-30T06:12:22.000000Z",
        "updated_at": "2025-07-30T06:12:22.000000Z"
      },
      "player": {
        "id": 1,
        "name": "Lionel Messi",
        "team_id": 2,
        "squad_number": 11,
        "height": 170,
        "weight": 72,
        "position_id": 25,
        "created_at": "2025-07-30T04:43:10.000000Z",
        "updated_at": "2025-07-30T04:43:10.000000Z"
      }
    },
    {
      "id": 3,
      "match_id": 1,
      "team_id": 2,
      "player_id": 1,
      "activity": "goal",
      "time_activity": "00:15:30",
      "detail": "Gol spektakuler dari tendangan bebas",
      "created_at": "2025-07-30T07:10:39.000000Z",
      "updated_at": "2025-07-30T07:15:35.000000Z",
      "team": {
        "id": 2,
        "name": "Persib Bandung FC",
        "logo": "http://localhost:8000/api/image/2_1753844830_V4m4Rc83uM.png",
        "established_year": 1933,
        "address": "Jl. Soekarno-Hatta No. 402, Bandung",
        "city": "32.73",
        "created_at": "2025-07-30T03:10:48.000000Z",
        "updated_at": "2025-07-30T03:39:17.000000Z"
      },
      "player": {
        "id": 1,
        "name": "Lionel Messi",
        "team_id": 2,
        "squad_number": 11,
        "height": 170,
        "weight": 72,
        "position_id": 25,
        "created_at": "2025-07-30T04:43:10.000000Z",
        "updated_at": "2025-07-30T04:43:10.000000Z"
      }
    },
    {
      "id": 1,
      "match_id": 1,
      "team_id": null,
      "player_id": null,
      "activity": "match_start",
      "time_activity": "00:00:00",
      "detail": null,
      "created_at": "2025-07-30T07:02:08.000000Z",
      "updated_at": "2025-07-30T07:02:08.000000Z",
      "team": null,
      "player": null
    }
  ]
}
```

### Statistik Pertandingan
```bash
curl -X GET "http://localhost:8000/api/matches/1/stats" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Match statistics retrieved successfully"
  },
  "data": {
    "goals": 3,
    "yellow_cards": 0,
    "red_cards": 0,
    "substitutions": 0,
    "corners": 0,
    "penalties": 0,
    "fouls": 0
  }
}
```

### Laporan Pertandingan
```bash
curl -X GET "http://localhost:8000/api/matches/1/match-report" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response:**
```json
{
  "code": {
    "status": 200,
    "message": "Match report retrieved successfully"
  },
  "data": {
    "match_info": {
      "id": 1,
      "venue": "Stadion Gelora Bung Karno",
      "match_datetime": "2025-07-30T19:30:00.000000Z",
      "home_team": {
        "id": 2,
        "name": "Persib Bandung FC"
      },
      "away_team": {
        "id": 3,
        "name": "Persib Bandung 2"
      }
    },
    "match_result": {
      "final_score": "2 - 1",
      "home_score": 2,
      "away_score": 1,
      "result": "home-win",
      "winner_team": 2
    },
    "team_performance": {
      "home_team": {
        "wins": 1,
        "losses": 0,
        "draws": 0,
        "total_matches": 1,
        "regular_goals": 2,
        "own_goals_conceded": 1
      },
      "away_team": {
        "wins": 0,
        "losses": 1,
        "draws": 0,
        "total_matches": 1,
        "regular_goals": 0,
        "own_goals_conceded": 0
      }
    },
    "goal_timeline": [
      {
        "time": "00:15:30",
        "player_id": 1,
        "player_name": "Lionel Messi",
        "team_id": 2,
        "team_name": "Persib Bandung FC",
        "activity": "goal",
        "activity_description": "Gol",
        "detail": "Gol spektakuler dari tendangan bebas",
        "is_own_goal": false,
        "own_goal_player": null
      },
      {
        "time": "00:21:30",
        "player_id": 1,
        "player_name": "Lionel Messi",
        "team_id": 3,
        "team_name": "Persib Bandung 2",
        "activity": "own_goal",
        "activity_description": "Gol bunuh diri",
        "detail": "Gol spektakuler dari tendangan bebas",
        "is_own_goal": true,
        "own_goal_player": "Lionel Messi"
      },
      {
        "time": "00:25:30",
        "player_id": 1,
        "player_name": "Lionel Messi",
        "team_id": 2,
        "team_name": "Persib Bandung FC",
        "activity": "goal",
        "activity_description": "Gol",
        "detail": "Gol spektakuler dari tendangan bebas",
        "is_own_goal": false,
        "own_goal_player": null
      }
    ],
    "top_scorers": [
      {
        "player_id": 1,
        "player_name": "Lionel Messi",
        "team_id": 2,
        "team_name": "Persib Bandung FC",
        "goals": 2
      }
    ],
    "match_metadata": {
      "status": "home-win",
      "scores_away": 1,
      "scores_home": 2,
      "winner_team": 2
    }
  }
}
```

## 📋 Jenis Aktivitas

Aktivitas pertandingan yang tersedia:
- `match_start` - Pertandingan dimulai
- `match_end` - Pertandingan berakhir
- `half_time` - Istirahat babak pertama
- `second_half` - Babak kedua dimulai
- `extra_time_start` - Perpanjangan waktu dimulai
- `extra_time_end` - Perpanjangan waktu berakhir
- `penalty_shootout_start` - Adu penalti dimulai
- `penalty_shootout_end` - Adu penalti berakhir
- `goal` - Gol
- `own_goal` - Gol bunuh diri
- `penalty_goal` - Gol dari penalti
- `free_kick_goal` - Gol dari tendangan bebas
- `header_goal` - Gol sundulan
- `volley_goal` - Gol voli
- `long_range_goal` - Gol jarak jauh
- `assist` - Assist
- `yellow_card` - Kartu kuning
- `red_card` - Kartu merah
- `second_yellow` - Kartu kuning kedua (merah)
- `yellow_red_card` - Kartu kuning-merah
- `foul` - Pelanggaran
- `dangerous_play` - Permainan berbahaya
- `handball` - Handball
- `offside` - Offside
- `diving` - Simulasi/diving
- `violent_conduct` - Kekerasan
- `unsporting_behavior` - Perilaku tidak sportif
- `dissent` - Protes kepada wasit
- `time_wasting` - Membuang waktu
- `substitution_in` - Pemain masuk
- `substitution_out` - Pemain keluar
- `tactical_substitution` - Substitusi taktis
- `injury_substitution` - Substitusi cedera
- `injury` - Cedera
- `medical_attention` - Pertolongan medis
- `concussion` - Gegar otak
- `blood_injury` - Cedera berdarah
- `corner` - Tendangan sudut
- `free_kick` - Tendangan bebas
- `penalty_awarded` - Penalti diberikan
- `penalty_missed` - Penalti meleset
- `penalty_saved` - Penalti diselamatkan
- `throw_in` - Lemparan ke dalam
- `goal_kick` - Tendangan gawang
- `save` - Penyelamatan
- `catch` - Menangkap bola
- `punch` - Meninju bola
- `goalkeeper_foul` - Pelanggaran kiper
- `goalkeeper_handball` - Handball kiper
- `ball_out` - Bola keluar
- `ball_in_play` - Bola dalam permainan
- `referee_decision` - Keputusan wasit
- `var_check` - Pengecekan VAR
- `var_decision` - Keputusan VAR
- `goal_disallowed` - Gol dibatalkan
- `goal_allowed` - Gol disahkan

## 🔒 Fitur Keamanan

- **Autentikasi JWT** - Autentikasi berbasis token yang aman
- **Proteksi X-API-Key** - Endpoint registrasi dilindungi dengan API key
- **Validasi Input** - Validasi komprehensif untuk semua input
- **Soft Deletes** - Pelestarian dan pemulihan data
- **Deteksi Konflik** - Mencegah konflik penjadwalan
- **Keamanan Upload File** - Upload gambar yang aman dengan validasi

## 📁 Struktur File

```
sports_app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ApiController.php
│   │   │   ├── AuthController.php
│   │   │   ├── FileUploadController.php
│   │   │   ├── MatchActivityController.php
│   │   │   ├── MatchController.php
│   │   │   ├── PlayerController.php
│   │   │   ├── PositionController.php
│   │   │   ├── TeamController.php
│   │   │   └── WilayahController.php
│   │   ├── Middleware/
│   │   │   ├── ApiKeyMiddleware.php
│   │   │   └── JsonResponseMiddleware.php
│   │   └── Rules/
│   │       ├── ValidCityCode.php
│   │       ├── ValidMatchActivity.php
│   │       ├── ValidMatchSchedule.php
│   │       └── ValidSquadNumber.php
│   ├── Models/
│   │   ├── GameMatch.php
│   │   ├── MatchActivity.php
│   │   ├── Player.php
│   │   ├── Position.php
│   │   ├── Team.php
│   │   └── User.php
│   ├── Services/
│   │   └── WilayahService.php
│   └── Traits/
│       └── ApiResponse.php
├── config/
│   ├── app.php
│   ├── auth.php
│   └── jwt.php
├── database/
│   └── migrations/
├── routes/
│   └── api.php
└── storage/
    └── app/
        └── images/
```

## 🚨 Standarisasi API

Semua respons API mengikuti format terstandarisasi:

```json
{
  "code": {
    "status": 200,
    "message": "success"
  },
  "data": {
    // Data respons
  },
  "meta": {
    // Pagination, filter, dll
  }
}
```

Kode Status HTTP Umum:
- `200` - Sukses
- `201` - Dibuat
- `204` - Tidak Ada Konten (Operasi hapus)
- `400` - Bad Request
- `401` - Tidak Diotorisasi
- `403` - Dilarang
- `404` - Tidak Ditemukan
- `422` - Error Validasi
- `500` - Error Server

## 🔧 Kustomisasi

### Menambah Jenis Aktivitas Baru
Edit `app/Models/MatchActivity.php` dan tambahkan jenis aktivitas baru ke method `getActivityTypes()`.

### Memodifikasi Aturan Validasi
Update aturan validasi di file model terkait atau buat aturan validasi kustom baru.

### Mengubah Format Respons API
Modifikasi `app/Traits/ApiResponse.php` untuk mengubah struktur respons.

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur Anda (`git checkout -b feature/amazing-feature`)
3. Commit perubahan Anda (`git commit -m 'Add some amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buka Pull Request

## 📮 Import ke Postman
Klik tombol di bawah untuk langsung import collection ke Postman:

[![Import ke Postman](https://run.pstmn.io/button.svg)](https://raw.githubusercontent.com/riskykurniawan15/sports_app/refs/heads/master/sports.postman_collection.json)
