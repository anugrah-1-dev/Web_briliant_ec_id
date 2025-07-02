<!-- Navbar -->
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<script>
    const logo1URL = "{{ asset('asset/img/logo1.png') }}";
    const logo2URL = "{{ asset('asset/img/logo2-removebg-preview.png') }}";
</script>
<script src="{{ asset('js/landingpage.js') }}"></script>


<nav id="navbar">
    <div class="logo">
       <img src="{{ asset('asset/img/logo1.png') }}" alt="Logo" style="height: 60px;">


    </div>
    <div class="nav-links">
        {{-- <a href="{{ url('/landingpage') }}">DASHBOARD</a> --}}
        <a href="#tentang">TENTANG KAMI</a>
        <a href="#program">PROGRAM & HARGA</a>
        <a href="#galeri">GALERI</a>
        <a href="#kontak">KONTAK</a>
        <a href="#daftar" class="btn">PENDAFTARAN</a>
    </div>
</nav>
