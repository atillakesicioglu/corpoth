# Corpoth — Kurumsal Esenlik Sitesi

CORPOTH için PHP + MySQL tabanlı kurumsal esenlik landing page'i ve admin paneli.

## Mimari

- **Frontend**: PHP şablon + Tailwind CSS (CDN; opsiyonel CLI build) + vanilla JS (IntersectionObserver animasyonlar)
- **Backend**: Vanilla PHP 8.x (procedural + PDO), framework yok
- **DB**: MySQL/MariaDB (UTF-8 mb4)
- **Admin**: `/admin/` altında session tabanlı, CSRF korumalı CRUD

## Klasör Yapısı

```
/
├─ index.php             Anasayfa (DB'den dinamik)
├─ kvkk.php, gizlilik.php   Yasal sayfalar
├─ submit-lead.php       Lead form endpoint
├─ install.php           Tek seferlik kurulum (silinmeli)
├─ assets/               CSS / JS / görseller
├─ includes/
│  ├─ bootstrap.php
│  ├─ db.php             PDO singleton
│  ├─ helpers.php        e(), csrf, redirect, vs.
│  ├─ config.example.php → config.php (gitignored)
│  ├─ models/            Her DB tablosu için CRUD
│  └─ render/            Public sayfa bileşenleri
├─ admin/                Yönetim paneli
│  ├─ index.php          Login
│  ├─ dashboard.php
│  ├─ leads.php, hero.php, service.php, ...
│  ├─ partials/
│  └─ assets/
├─ db/
│  ├─ schema.sql         Tablo yapıları
│  └─ seed.sql           Başlangıç verileri
├─ uploads/              Admin'den yüklenen medya (gitignored)
└─ .htaccess, sitemap.xml, robots.txt
```

## İlk Kurulum (Sunucuda)

1. Git/cPanel ile dosyaları yükleyin (cPanel kullanılıyorsa `.cpanel.yml` otomatik dağıtım yapar).
2. cPanel → MySQL Veritabanları → yeni bir DB ve kullanıcı oluşturun, kullanıcıya tüm yetkileri verin.
3. Tarayıcıdan `https://corpoth.com/install.php` adresini açın:
   - Adım 1: DB bağlantı bilgilerini girin (config.php otomatik oluşturulur).
   - Adım 2: `db/schema.sql` ve `db/seed.sql` import edilir.
   - Adım 3: Admin kullanıcı adı ve şifresini belirleyin.
4. **Kurulum tamamlandığında `install.php` dosyasını sunucudan SİLİN.**
5. `https://corpoth.com/admin/` adresinden giriş yapın.

## Yerel Geliştirme

```bash
# PHP yerleşik sunucu (DB yi cPanel'deki ile uzaktan kullanabilirsiniz)
php -S localhost:8080

# Veya XAMPP/MAMP gibi bir local stack kullanabilirsiniz.
```

`includes/config.php` dosyasını `includes/config.example.php`'den kopyalayıp DB bilgilerinizi girin.

### Tailwind Build (opsiyonel)

Şu an Tailwind CDN kullanılıyor (anında çalışır). Production'da CLI build'e geçmek için:

```bash
npm install
npm run build:css      # bir kez
npm run watch:css      # gelistirme sirasinda
```

`assets/css/dist.css` üretilir. Ardından `includes/render/head.php` içindeki Tailwind CDN script'ini kaldırıp `<link rel="stylesheet" href="/assets/css/dist.css">` ekleyebilirsiniz.

## Admin Modülleri

| Modül | Yönetilen İçerik |
|---|---|
| Panel | Lead istatistikleri ve son kayıtlar |
| Lead'ler | Form gönderileri, filtre, CSV export |
| Hero | Ana banner başlığı, görseli, CTA |
| Hizmet | "Kurumsal Omurga Terapisi Nedir?" + 4 özellik |
| Kimler İçin | 3 hedef kitle kartı |
| Faydalar | 4 fayda kartı |
| İstatistikler | %25 / %40 gibi animasyonlu rakamlar |
| Süreç Adımları | 4 adımlık "Nasıl Çalışır?" |
| Neden Corpoth | Blok başlığı + görseli + 3 değer önerisi |
| Senaryolar | 4 kullanım senaryosu kartı |
| Referanslar | Logo bandı |
| Yorumlar | Müşteri testimonial'ları |
| SSS | FAQ schema ile |
| İletişim Bilgileri | Telefon, WhatsApp, e-posta, sosyal medya |
| Genel Ayarlar | SEO meta, GA, Clarity, KVKK metni |
| Medya | Yüklenen dosyalar yöneticisi |
| Hesabım | Şifre değiştir |

## Güvenlik

- Admin login session + bcrypt + CSRF + IP başına rate limit (15dk içinde 5 başarısız giriş)
- `/includes/`, `/db/` direkt erişime kapalı (`.htaccess`)
- `/uploads/` PHP yürütmesi engelli
- HTTPS zorunlu (`.htaccess` 301 yönlendirme)

## Lisans

Tescilli — yalnızca CORPOTH için.
