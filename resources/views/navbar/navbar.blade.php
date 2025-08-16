<!-- Navbar -->
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<script>
    const logo1URL = "{{ asset('asset/img/logo1.png') }}";
    const logo2URL = "{{ asset('asset/img/logo2-removebg-preview.png') }}";
</script>
<script src="{{ asset('js/landingpage.js') }}"></script>

<!-- Navbar -->
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

<nav id="navbar">
    <div class="logo">
        <img src="{{ asset('asset/img/logo1.png') }}" alt="Logo" style="height: 60px;">
    </div>

    <!-- Burger Menu -->
    <div class="burger" onclick="toggleNavbar()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="nav-links" id="navLinks">

        <a href="#">TENTANG KAMI</a>

     <!-- Dropdown PROGRAM -->
<div class="dropdown">
    <a href="#" class="dropbtn">
      PROGRAM <span class="arrow">▼</span>
    </a>
    <div class="dropdown-content">
      <a href="#program" >Program Bahasa Inggris</a>
      <a href="#program" >Program Bahasa Jerman</a>
      <a href="#program">Program Bahasa Mandarin</a>
      <a href="#program">Program Bahasa Arab</a>
    </div>
    
  </div>
        <a href="#camp">CAMP</a>
        <a href="#galeri">GALLERI</a>
        <a href="#sosmed">SOSMED</a>
        <a href="#kontak">KONTAK</a>
        <a href="{{ route('tracking.index') }}" class="btn">Tracking Transaksi</a>
    </div>
    <script>
        function toggleDropdown() {
          document.getElementById("dropdownMenu").classList.toggle("show");
        }
        
        // Tutup dropdown kalau klik di luar
        window.onclick = function(event) {
          if (!event.target.matches('.dropbtn') && !event.target.closest('.dropdown')) {
            document.querySelectorAll(".dropdown-content").forEach(el => el.classList.remove("show"));
          }
        }
        </script>
    <style>
        /* === Dropdown Container === */
        .dropdown {
          position: relative;
          display: inline-block;
          font-family: sans-serif;
        }
        
        /* Tombol utama */
        .dropbtn {
          text-decoration: none;
          color: #333;
          font-weight: 500;
          padding: 8px 12px;
          display: inline-flex;
          align-items: center;
          cursor: pointer;
        }
        
        /* Panah ▼ */
        .dropbtn .arrow {
          font-size: 0.7em;
          margin-left: 5px;
        }
        
        /* Isi dropdown */
        .dropdown-content {
          display: none;
          position: absolute;
          background-color: #fff;
          min-width: 200px;
          box-shadow: 0px 4px 8px rgba(0,0,0,0.15);
          border-radius: 6px;
          margin-top: 4px;
          z-index: 1000;
        }
        
        /* Item di dalam dropdown */
        .dropdown-content a {
          color: #333;
          padding: 10px 15px;
          text-decoration: none;
          display: block;
          font-size: 0.9rem;
        }
        
        .dropdown-content a:hover {
          background-color: #f5f5f5;
        }
        
        /* Muncul saat hover */
        .dropdown:hover .dropdown-content {
          display: block;
        }
        
        /* Efek hover pada tombol */
        .dropdown:hover .dropbtn {
          background-color: #f9f9f9;
          border-radius: 4px;
        }
        </style>
        
      
</nav>
