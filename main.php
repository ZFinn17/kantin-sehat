<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kantin Bahagia</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,600;0,700;1,700&display=swap"
        rel="stylesheet" />

    <!-- feather icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- feather end -->

    <link rel="stylesheet" href="style.css" />

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="#" class="navbar-logo">Kantin<span>Sehat</span></a>

        <div class="navbar-nav">
            <a href="#home">Home</a>
            <a href="#about">Tentang Kami</a>
            <a href="#menu">Menu</a>
            <a href="#" id="login-btn">Login Admin</a>
        </div>

        <div class="navbar-extra">
            <a href="#" id="shopping-cart"><i data-feather="shopping-cart"></i></a>
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Hero section -->
    <section class="hero" id="home">
        <main class="content">
            <h1>Mari menikmati sebuah <span>Roti</span></h1>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam,
                voluptatibus.
            </p>
            <a href="#menu" class="cta">Beli Sekarang</a>
        </main>
    </section>
    <!-- Hero section end -->

    <!-- About section -->
    <section id="about" class="about">
        <h2><span>Tentang</span> Kami</h2>

        <div class="row">
            <div class="about-img">
                <img src="img/cnt.jpg" alt="Tentang Kami" />
            </div>
            <div class="content">
                <h3>Kantin Sehat</h3>
                <p> Kantin Sehat adalah layanan kantin yang menyediakan makanan dan minuman bergizi, higienis, serta
                    aman bagi seluruh warga sekolah. Seluruh menu disiapkan dengan memperhatikan kebersihan, kebutuhan
                    gizi, dan harga terjangkau. Kantin Sehat bertujuan mendukung pola hidup sehat dan menciptakan
                    lingkungan sekolah yang lebih baik. </p>
                <p>
                    Dengan adanya sistem ini, proses jual-beli menjadi lebih
                    teratur, higienis, dan modern. Tujuannya untuk menciptakan kebiasaan makan sehat sekaligus
                    meningkatkan kenyamanan dan efektivitas layanan kantin di sekolah.
                </p>
            </div>
        </div>
    </section>
    <!-- About section end -->

    <!-- Menu section -->
    <section id="menu" class="menu">
        <div class="title">
            <h2><span>Menu</span> Kami</h2>
            <p>
                Menu Kantin Sehat terdiri dari berbagai pilihan makanan dan minum disajikan dalam
                card. Setiap menu terdapat nama dan harga menu.
            </p>
        </div>

        <div class="row">

            <div class="menu-jir">
                <div class="header-jir">
                    <img src="img/aqua.jfif" alt="">
                </div>
                <h3>Aqua</h3>
                <p class="p1">Lorem ipsum, dolor sit amet consectetur adipisicing.</p>
                <div class="bjir">
                    <p class="menu-price">Rp4.000</p>
                    <a href="#menu" class="add-to-cart" data-id="1" data-name="Aqua" data-price="4000"
                        data-size="Reguler">
                        <i data-feather="plus"></i>
                    </a>
                </div>
            </div>

            <div class="menu-jir">
                <div class="header-jir">
                    <img src="img/milo.jpg" alt="">
                </div>
                <h3>Milo UHT</h3>
                <p class="p1">Lorem ipsum, dolor sit amet consectetur adipisicing.</p>
                <div class="bjir">
                    <p class="menu-price">Rp6.000</p>
                    <a href="#menu" class="add-to-cart" data-id="2" data-name="Milo-UHT" data-price="6000"
                        data-size="Reguler">
                        <i data-feather="plus"></i>
                    </a>
                </div>
            </div>

            <div class="menu-jir">
                <div class="header-jir">
                    <img src="img/sariroti.jfif" alt="">
                </div>
                <h3>Sari Roti Kupas</h3>
                <p class="p1">Lorem ipsum, dolor sit amet consectetur adipisicing.</p>
                <div class="bjir">
                    <p class="menu-price">Rp12.000</p>
                    <a href="#menu" class="add-to-cart" data-id="3" data-name="Sari-Roti-Tawar-Kupas" data-price="12000"
                        data-size="Reguler">
                        <i data-feather="plus"></i>
                    </a>
                </div>
            </div>

            <div class="menu-jir">
                <div class="header-jir">
                    <img src="img/beng.jpg" alt="">
                </div>
                <h3>Beng Beng Wafel</h3>
                <p class="p1">Lorem ipsum, dolor sit amet consectetur adipisicing.</p>
                <div class="bjir">
                    <p class="menu-price">Rp3.500</p>
                    <a href="#menu" class="add-to-cart" data-id="4" data-name="Beng-Beng-Wafer" data-price="3500"
                        data-size="Reguler">
                        <i data-feather="plus"></i>
                    </a>
                </div>
            </div>

            <div class="menu-jir">
                <div class="header-jir">
                    <img src="img/popmie.jfif" alt="">
                </div>
                <h3>Pop Mie</h3>
                <p class="p1">Lorem ipsum, dolor sit amet consectetur adipisicing.</p>
                <div class="bjir">
                    <p class="menu-price">Rp7.000</p>
                    <a href="#menu" class="add-to-cart" data-id="5" data-name="Pop-Mie" data-price="7000"
                        data-size="Reguler">
                        <i data-feather="plus"></i>
                    </a>
                </div>
            </div>

            </main>
        </div>
    </section>
    <!-- Menu section end -->

    <!-- Sidebar Keranjang -->
    <div id="cart-overlay" class="cart-overlay"></div>

    <div id="cart-sidebar" class="cart-sidebar">
        <div class="cart-header">
            <h3><i data-feather="shopping-cart"></i> Keranjang Belanja</h3>
            <button class="close-cart" id="close-cart">&times;</button>
        </div>

        <div class="cart-items" id="cart-items">
            <!-- Item keranjang akan muncul di sini -->
            <div class="empty-cart">
                <i data-feather="shopping-bag"></i>
                <p>Keranjang masih kosong</p>
            </div>
        </div>

        <div class="cart-footer">
            <div class="cart-total">
                <h4>Total: <span id="cart-total-price">Rp0</span></h4>
            </div>
            <button class="checkout-btn" id="checkout-btn">
                <i data-feather="credit-card"></i> Simpan Transaksi
            </button>
            <button class="clear-cart-btn" id="clear-cart-btn">
                <i data-feather="trash-2"></i> Kosongkan Keranjang
            </button>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="login-modal" class="login-modal">
        <div class="login-modal-content">
            <div class="login-header">
                <h3><i data-feather="lock"></i> Login Admin Kantin Bahagia</h3>
                <button class="close-login">&times;</button>
            </div>

            <form id="login-form" class="login-form" action="login_process.php" method="POST">
                <div class="form-group">
                    <label for="username"><i data-feather="user"></i> Username</label>
                    <input type="text" id="username" name="username" required placeholder="Masukkan username admin">
                </div>

                <div class="form-group">
                    <label for="password"><i data-feather="key"></i> Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="login-submit-btn">
                        <i data-feather="log-in"></i> Login
                    </button>
                    <button type="button" class="login-cancel-btn close-login">
                        <i data-feather="x"></i> Batal
                    </button>
                </div>

                <div class="login-info">
                    <p><small><i data-feather="info"></i> Hanya untuk admin kantin</small></p>
                    <p><small>Demo: admin / admin123</small></p>
                </div>
            </form>

            <div id="login-message" class="login-message"></div>
        </div>
    </div>

    <!-- Footer Minimal -->
    <footer class="footer-minimal">
        <div class="footer-content">
            <div class="footer-brand">
                <i data-feather="coffee"></i>
                <span>Kantin<span class="brand-highlight">Sehat</span></span>
            </div>
            <p class="footer-tagline">
                Menyajikan kebahagiaan dalam setiap sajian
            </p>
        </div>
        <div class="footer-copyright">
            <p>© 2025 Kantin Sehat. All rights reserved.</p>
        </div>
    </footer>

    <!-- feather -->
    <script>
    feather.replace();
    </script>
    <!-- feather end -->

    <script src="script.js"></script>
</body>

</html>