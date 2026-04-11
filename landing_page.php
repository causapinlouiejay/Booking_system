<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <title>Grand Hotel | Experience True Luxury</title>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .gold-text {
            background: linear-gradient(135deg, #c5a059 0%, #ecd4a4 50%, #c5a059 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased selection:bg-blue-600 selection:text-white">

    <nav class="fixed w-full z-50 transition-all duration-300 bg-black/50 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-widest text-white uppercase font-serif">
                Grand<span class="text-[#c5a059]">Hotel</span>
            </h1>
            <div class="hidden md:flex items-center space-x-8 text-sm font-bold uppercase tracking-widest text-gray-300">
                <a href="#experience" class="hover:text-white transition">Experience</a>
                <a href="index.php" class="hover:text-white transition">Sign In</a>
                <a href="register.php" class="bg-white/10 hover:bg-white/20 text-white px-6 py-2 rounded-full border border-white/20 transition">Register</a>
            </div>
        </div>
    </nav>

    <header class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1542314831-c6a4d2748610?auto=format&fit=crop&q=80&w=2070" 
                 alt="Grand Hotel Exterior" 
                 class="w-full h-full object-cover scale-105 animate-[pulse_20s_ease-in-out_infinite_alternate]">
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-slate-900"></div>
        </div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto mt-20">
            <p class="text-[#ecd4a4] tracking-[0.4em] text-xs md:text-sm font-bold uppercase mb-6">Welcome to the extraordinary</p>
            <h2 class="text-5xl md:text-7xl text-white font-serif mb-8 leading-tight">
                Discover a New Level of <span class="italic gold-text">Luxury</span>
            </h2>
            <p class="text-gray-300 text-lg md:text-xl font-light mb-10 max-w-2xl mx-auto">
                An oasis of comfort and elegance in the heart of the city. Immerse yourself in breathtaking views, world-class dining, and unparalleled service.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="index.php" class="bg-[#c5a059] text-gray-900 font-black px-10 py-4 rounded-full uppercase tracking-widest hover:bg-[#ecd4a4] hover:scale-105 transition-all shadow-[0_0_20px_rgba(197,160,89,0.3)]">
                    Login to Book
                </a>
                <a href="register.php" class="bg-white/10 text-white backdrop-blur-sm border border-white/20 font-black px-10 py-4 rounded-full uppercase tracking-widest hover:bg-white/20 transition-all">
                    Create Account
                </a>
            </div>
        </div>
    </header>

    <section id="experience" class="py-24 px-6 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-16 items-center">
            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#c5a059] mb-4">The Grand Experience</h3>
                <h2 class="text-4xl font-serif mb-6 leading-snug">Designed for the modern traveler seeking timeless elegance.</h2>
                <p class="text-gray-400 leading-relaxed mb-8 font-light">
                    From our impeccably designed suites to our state-of-the-art wellness facilities, every detail at Grand Hotel has been curated to provide an unforgettable experience. Whether you are here for business or leisure, our dedicated staff is committed to exceeding your expectations.
                </p>
                <a href="index.php" class="text-white border-b border-[#c5a059] pb-1 uppercase tracking-widest text-xs font-bold hover:text-[#c5a059] transition">View Amenities &rarr;</a>
            </div>
            <div class="relative">
                <div class="absolute inset-0 bg-[#c5a059] translate-x-4 translate-y-4 rounded-[2rem] opacity-20"></div>
                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&q=80&w=1000" alt="Hotel Interior" class="relative z-10 rounded-[2rem] shadow-2xl">
            </div>
        </div>
    </section>

    <footer class="bg-black py-12 px-6 border-t border-white/10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h1 class="text-xl font-bold tracking-widest text-white uppercase font-serif mb-2">Grand<span class="text-[#c5a059]">Hotel</span></h1>
                <p class="text-gray-500 text-[10px] uppercase tracking-[0.2em]">&copy; <?php echo date('Y'); ?> Grand Hotel International. All Rights Reserved.</p>
            </div>
            
            <div class="flex items-center space-x-6 text-[10px] font-bold uppercase tracking-widest text-gray-500">
                <a href="index.php" class="hover:text-[#c5a059] transition">Sign In</a>
                <span class="w-1 h-1 rounded-full bg-gray-700"></span>
                <a href="register.php" class="hover:text-[#c5a059] transition">Register</a>
            </div>
        </div>
    </footer>

</body>
</html>