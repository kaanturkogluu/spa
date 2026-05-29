<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Spa Masaj</title>
    <link rel="icon" href="{{ asset('logo.jpg') }}" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #faf9f6; }
        h1, h2, h3, .font-serif { font-family: 'Playfair Display', serif; }
        .glass-nav { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
        .hero-bg {
            background-image: linear-gradient(rgba(17, 24, 39, 0.6), rgba(17, 24, 39, 0.6)), url('https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .service-card { transition: all 0.3s ease; }
        .service-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="text-gray-800 antialiased overflow-x-hidden">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold font-serif text-green-800 flex items-center tracking-wide">
                        <img src="{{ asset('logo.jpg') }}" alt="Royal Spa" class="h-12 w-auto mr-2 rounded-full shadow-sm" onerror="this.outerHTML='<i class=\'fa-solid fa-leaf text-green-600 mr-2\'></i>'"> 
                        ROYAL SPA <span class="text-gray-500 font-light ml-2 text-xl hidden sm:block">MASAJ</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-600 hover:text-green-700 font-medium transition-colors">Ana Sayfa</a>
                    <a href="#about" class="text-gray-600 hover:text-green-700 font-medium transition-colors">Hakkımızda</a>
                    <a href="#services" class="text-gray-600 hover:text-green-700 font-medium transition-colors">Masaj Paketleri</a>
                    <a href="#team" class="text-gray-600 hover:text-green-700 font-medium transition-colors">Uzmanlarımız</a>
                    <a href="#contact" class="text-gray-600 hover:text-green-700 font-medium transition-colors">İletişim</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-full font-medium transition-colors shadow-lg">Panele Git</a>
                        @else
                            <a href="{{ route('reception.dashboard') }}" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-full font-medium transition-colors shadow-lg">Panele Git</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-green-800 font-medium border border-green-800 hover:bg-green-50 px-5 py-2 rounded-full transition-colors hidden sm:block">Giriş Yap</a>
                    @endauth
                    
                    <button class="md:hidden text-gray-700 focus:outline-none" id="mobile-menu-btn">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="md:hidden hidden bg-white border-t" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#home" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-md">Ana Sayfa</a>
                <a href="#about" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-md">Hakkımızda</a>
                <a href="#services" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-md">Masaj Paketleri</a>
                <a href="#team" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-md">Uzmanlarımız</a>
                <a href="#contact" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-700 hover:bg-green-50 rounded-md">İletişim</a>
                @guest
                <a href="{{ route('login') }}" class="block px-3 py-2 text-base font-medium text-green-700 font-bold hover:bg-green-50 rounded-md">Personel Girişi</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen flex items-center hero-bg">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[rgba(17,24,39,0.9)]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full text-center mt-16">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight drop-shadow-lg">Ruhunuzu ve Bedeninizi <br><span class="text-green-400 italic">Arındırın</span></h1>
            <p class="mt-4 text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto font-light drop-shadow mb-10">Günlük hayatın stresinden uzaklaşın. Profesyonel terapistlerimiz eşliğinde eşsiz bir SPA deneyimi sizi bekliyor.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#services" class="bg-green-600 hover:bg-green-500 text-white px-8 py-4 rounded-full text-lg font-medium transition-colors shadow-lg">Paketlerimizi İnceleyin</a>
                <a href="https://wa.me/905444716589" target="_blank" class="bg-[#25D366] hover:bg-green-500 text-white px-8 py-4 rounded-full text-lg font-medium transition-colors shadow-lg flex items-center justify-center">
                    <i class="fa-brands fa-whatsapp text-2xl mr-2"></i> WhatsApp'tan Rezervasyon
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Spa İç Mekan" class="w-full h-auto transform hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-green-900 opacity-10"></div>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <h4 class="text-green-600 font-semibold tracking-wider uppercase mb-2">Hakkımızda</h4>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 font-serif">Huzurun <br><span class="italic text-green-800">Yeni Adresi</span></h2>
                    <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                        Royal Spa Masaj, bedeninizin ve ruhunuzun ihtiyaç duyduğu yenilenmeyi sağlamak için tasarlandı. Modern şehir hayatının yorgunluğunu geride bırakacağınız, tamamen doğal yağlar ve uzman dokunuşlarla hazırlanan konseptimizle hizmetinizdeyiz.
                    </p>
                    <div class="grid grid-cols-2 gap-6 mt-8">
                        <div>
                            <h3 class="text-3xl font-bold text-green-700 mb-2 font-serif">10+</h3>
                            <p class="text-gray-600 font-medium">Yıllık Tecrübe</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-green-700 mb-2 font-serif">100%</h3>
                            <p class="text-gray-600 font-medium">Müşteri Memnuniyeti</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-[#faf9f6]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h4 class="text-green-600 font-semibold tracking-wider uppercase mb-2">Hizmetlerimiz</h4>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 font-serif mb-4">Masaj Paketleri</h2>
                <p class="text-gray-600 text-lg">İhtiyacınıza en uygun masaj terapisini seçin. Tüm paketlerimiz uzman terapistlerimiz tarafından uygulanmaktadır.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($packages as $index => $package)
                <div class="service-card bg-white rounded-3xl overflow-hidden shadow-md border border-gray-100 flex flex-col h-full relative group">
                    <div class="absolute top-0 right-0 bg-green-600 text-white px-4 py-1 rounded-bl-xl font-medium z-10">Popüler</div>
                    <div class="h-48 overflow-hidden">
                        <!-- Dynamic dummy images based on index -->
                        @php
                            $images = [
                                'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                                'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                                'https://images.unsplash.com/photo-1544161513-01f11e97bb1e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                                'https://images.unsplash.com/photo-1552693673-1bf958298935?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
                            ];
                            $img = $images[$index % count($images)];
                        @endphp
                        <img src="{{ $img }}" alt="{{ $package->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-2xl font-bold text-gray-900 font-serif">{{ $package->name }}</h3>
                            <span class="text-2xl font-bold text-green-700">{{ number_format($package->price, 0) }}₺</span>
                        </div>
                        <p class="text-gray-500 mb-6 flex-1">
                            Vücudunuzun ihtiyacı olan rahatlamayı sağlar. Kas gerginliklerini azaltır ve kan dolaşımını hızlandırır.
                        </p>
                        <ul class="space-y-2 mb-8">
                            <li class="flex items-center text-gray-600"><i class="fa-solid fa-check text-green-500 mr-2"></i> 60 Dakika Seans</li>
                            <li class="flex items-center text-gray-600"><i class="fa-solid fa-check text-green-500 mr-2"></i> Doğal Aromaterapi Yağları</li>
                            <li class="flex items-center text-gray-600"><i class="fa-solid fa-check text-green-500 mr-2"></i> Sıcak İçecek İkramı</li>
                        </ul>
                        <a href="https://wa.me/905444716589" target="_blank" class="flex justify-center items-center w-full py-3 px-4 bg-[#25D366] hover:bg-green-600 text-white rounded-xl font-medium transition-colors border border-transparent shadow-sm">
                            <i class="fa-brands fa-whatsapp text-xl mr-2"></i> Rezervasyon Yap
                        </a>
                    </div>
                </div>
                @endforeach
                
                @if($packages->isEmpty())
                <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                    <i class="fa-solid fa-spa text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-lg">Şu anda sistemde ekli masaj paketi bulunmamaktadır.</p>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="team" class="py-24 bg-green-900 text-white relative">
        <!-- Decorative pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h4 class="text-green-300 font-semibold tracking-wider uppercase mb-2">Ekibimiz</h4>
                <h2 class="text-4xl md:text-5xl font-bold font-serif mb-4">Uzman Terapistlerimiz</h2>
                <p class="text-green-100 text-lg">Alanında uzman, sertifikalı ve tecrübeli ekibimizle size en iyi hizmeti sunmak için buradayız.</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-8">
                @foreach($staffs as $staff)
                <div class="w-full sm:w-64 bg-green-800 bg-opacity-50 rounded-2xl p-6 text-center backdrop-blur-sm border border-green-700 hover:bg-green-700 transition-colors">
                    <div class="w-24 h-24 mx-auto rounded-full bg-green-100 flex items-center justify-center text-green-800 text-3xl font-bold font-serif mb-4 shadow-lg border-4 border-green-600">
                        {{ mb_substr($staff->first_name, 0, 1) }}{{ mb_substr($staff->last_name, 0, 1) }}
                    </div>
                    <h3 class="text-xl font-bold mb-1">{{ $staff->first_name }} {{ $staff->last_name }}</h3>
                    <p class="text-green-300 text-sm font-medium uppercase tracking-wider mb-3">Kıdemli Terapist</p>
                    <div class="flex justify-center space-x-3 text-green-400">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                @endforeach
                
                @if($staffs->isEmpty())
                <div class="w-full text-center py-8 text-green-200">
                    Uzman kadromuz yakında eklenecektir.
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <div>
                    <h4 class="text-green-600 font-semibold tracking-wider uppercase mb-2">İletişim</h4>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 font-serif mb-6">Bize Ulaşın</h2>
                    <p class="text-gray-600 text-lg mb-8">Rezervasyon yaptırmak veya detaylı bilgi almak için bizimle iletişime geçebilirsiniz.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl mr-4">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Adres</h4>
                                <p class="text-gray-600 mt-1">Dörtyol Royal Spa ve Masaj<br>Dörtyol / Hatay</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl mr-4">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Telefon & WhatsApp</h4>
                                <p class="text-gray-600 mt-1">0544 471 65 89</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl mr-4">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Çalışma Saatleri</h4>
                                <p class="text-gray-600 mt-1">Haftanın Her Günü: 10:00 - 24:00</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-3xl border border-gray-100 shadow-lg relative overflow-hidden min-h-[400px]">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3193.271674630446!2d36.2213254!3d36.8359691!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x152f3f5cd3e1ff69%3A0x873fb2729a3c2756!2sD%C3%B6rtyol%20Royal%20Spa%20ve%20Masaj!5e0!3m2!1str!2str!4v1780073799715!5m2!1str!2str" class="w-full h-full absolute inset-0 border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-6 md:mb-0">
                <a href="#" class="text-2xl font-bold font-serif text-white flex items-center">
                    <img src="{{ asset('logo.jpg') }}" alt="Royal Spa" class="h-10 w-auto mr-2 rounded-full" onerror="this.outerHTML='<i class=\'fa-solid fa-leaf text-green-500 mr-2\'></i>'">
                    ROYAL SPA
                </a>
                <p class="mt-2 text-sm text-gray-500">© 2026 Royal Spa Masaj. Tüm hakları saklıdır.</p>
            </div>
            <div class="flex space-x-6">
                <a href="https://www.instagram.com/royalspadortyol?igsh=MTllZXk3OWh2OG41YQ==" target="_blank" class="text-gray-400 hover:text-white transition-colors text-xl"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://wa.me/905444716589" target="_blank" class="text-gray-400 hover:text-white transition-colors text-xl"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        
        // Navbar Scrolled Effect
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 10) {
                nav.classList.add('shadow-md');
                nav.classList.replace('glass-nav', 'bg-white');
            } else {
                nav.classList.remove('shadow-md');
                nav.classList.replace('bg-white', 'glass-nav');
            }
        });
    </script>
</body>
</html>
